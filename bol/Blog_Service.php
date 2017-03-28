<?php

/**
 * This software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is a proprietary licensed product. 
 * For more information see License.txt in the plugin folder.

 * ---
 * Copyright (c) 2012, Purusothaman Ramanujam
 * All rights reserved.

 * Redistribution and use in source and binary forms, with or without modification, are not permitted provided.

 * This plugin should be bought from the developer by paying money to PayPal account (purushoth.r@gmail.com).

 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
 * INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT HOLDER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO,
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
class GRAPHQL_BOL_BlogService {

    private static $classInstance;

    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct() {
        
    }

    public function getBlogPostById($id) {
        $dto = PostService::getInstance()->findById($id);

        if (!$dto) {
            return [];
        }

        $posts = array();

        $user = GRAPHQL_BOL_UserService::getInstance()->getUserById($id);
        
        $posts[$id]['id'] = $dto->getId();
        $posts[$id]['title'] = $dto->getTitle();
        $posts[$id]['post'] = $dto->getPost();
        $posts[$id]['privacy'] = $dto->getPrivacy();
        $posts[$id]['timestamp'] = $dto->getTimestamp();
        $posts[$id]['isDraft'] = $dto->isDraft();
        $posts[$id]['user'] = $user[$id];
        $posts[$id]['url'] = OW::getRouter()->urlForRoute('user-post', array('id' => $id));

        return $posts;
    }

    public function getBlogPosts($case, $first, $count, $tag = '') {
        list($list, $itemsCount) = $this->getData($case, $first, $count, $tag);
        $posts = $idList = array();

        foreach ($list as $item) {
            $dto = $item['dto'];
            $id = $dto->getId();
            $idList[] = $id;

            $posts[$id]['id'] = $dto->getId();
            $posts[$id]['title'] = $dto->getTitle();
            $posts[$id]['post'] = $dto->getPost();
            $posts[$id]['privacy'] = $dto->getPrivacy();
            $posts[$id]['timestamp'] = $dto->getTimestamp();
            $posts[$id]['isDraft'] = $dto->isDraft();
            $posts[$id]['url'] = OW::getRouter()->urlForRoute('user-post', array('id' => $id));
        }

        $users = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($idList);

        foreach ($users as $id => $user) {
            $posts[$id]['user'] = $user;
        }

        return $posts;
    }

    private function getData($case, $first, $count, $tag) {
        $service = PostService::getInstance();

        $list = array();
        $itemsCount = 0;

        switch ($case) {
            case 'most-discussed':
                $commentService = BOL_CommentService::getInstance();

                $info = array();

                $info = $commentService->findMostCommentedEntityList('blog-post', $first, $count);

                $idList = array();

                foreach ($info as $item) {
                    $idList[] = $item['id'];
                }

                if (empty($idList)) {
                    break;
                }

                $dtoList = $service->findListByIdList($idList);

                foreach ($dtoList as $dto) {
                    if ($dto->isDraft()) {
                        continue;
                    }
                    $info[$dto->id]['dto'] = $dto;

                    $list[] = array(
                        'dto' => $dto,
                        'commentCount' => $info[$dto->id] ['commentCount'],
                    );
                }

                function sortMostCommented($e, $e2) {

                    return $e['commentCount'] < $e2['commentCount'];
                }

                usort($list, 'sortMostCommented');

                $itemsCount = $commentService->findCommentedEntityCount('blog-post');

                break;

            case 'top-rated':
                $info = array();

                $info = BOL_RateService::getInstance()->findMostRatedEntityList('blog-post', $first, $count);

                $idList = array();

                foreach ($info as $item) {
                    $idList[] = $item['id'];
                }

                if (empty($idList)) {
                    break;
                }

                $dtoList = $service->findListByIdList($idList);

                foreach ($dtoList as $dto) {
                    if ($dto->isDraft()) {
                        continue;
                    }
                    $list[] = array(
                        'dto' => $dto,
                        'avgScore' => $info[$dto->id] ['avgScore'],
                        'ratesCount' => $info[$dto->id] ['ratesCount']
                    );
                }

                function sortTopRated($e, $e2) {
                    if ($e['avgScore'] == $e2['avgScore']) {
                        if ($e['ratesCount'] == $e2['ratesCount']) {
                            return 0;
                        }

                        return $e['ratesCount'] < $e2['ratesCount'];
                    }
                    return $e['avgScore'] < $e2['avgScore'];
                }

                usort($list, 'sortTopRated');

                $itemsCount = BOL_RateService::getInstance()->findMostRatedEntityCount('blog-post');

                break;

            case 'browse-by-tag':
                if (empty($tag)) {
                    $mostPopularTagsArray = BOL_TagService::getInstance()->findMostPopularTags('blog-post', 20);
                    $mostPopularTags = "";

                    foreach ($mostPopularTagsArray as $tag) {
                        $mostPopularTags .= $tag['label'] . ", ";
                    }

                    break;
                }

                $info = BOL_TagService::getInstance()->findEntityListByTag('blog-post', UTIL_HtmlTag::stripTags($tag), $first, $count);

                $itemsCount = BOL_TagService::getInstance()->findEntityCountByTag('blog-post', UTIL_HtmlTag::stripTags($tag));

                foreach ($info as $item) {
                    $idList[] = $item;
                }

                if (empty($idList)) {
                    break;
                }

                $dtoList = $service->findListByIdList($idList);

                function sortByTimestamp($post1, $post2) {
                    return $post1->timestamp < $post2->timestamp;
                }

                usort($dtoList, 'sortByTimestamp');


                foreach ($dtoList as $dto) {
                    if ($dto->isDraft()) {
                        continue;
                    }
                    $list[] = array('dto' => $dto);
                }

                break;

            case 'latest':

                $arr = $service->findList($first, $count);

                foreach ($arr as $item) {
                    $list[] = array('dto' => $item);
                }

                $itemsCount = $service->countPosts();

                break;
        }

        return array($list, $itemsCount);
    }

}
