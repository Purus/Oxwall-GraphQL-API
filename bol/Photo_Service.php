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
class GRAPHQL_BOL_PhotoService {

    private static $classInstance;

    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct() {
        
    }

    public function getPhotoList($listType, $page, $limit) {
        $photos = PHOTO_BOL_PhotoService::getInstance()->findPhotoList($listType, $page, $limit);

        if (!$photos) {
            return [];
        }

        $allPhotos = $userIdList = array();

        foreach ($photos as $photo) {
            $id = $photo['id'];
            $userIdList[] = $photo['userId'];

            $allPhotos[$id]['id'] = $id;
            $allPhotos[$id]['description'] = $photo['description'];
            $allPhotos[$id]['timestamp'] = $photo['addDatetime'];
            $allPhotos[$id]['status'] = $photo['status'];
            $allPhotos[$id]['hasFullsize'] = $photo['hasFullsize'];
            $allPhotos[$id]['privacy'] = $photo['privacy'];
            $allPhotos[$id]['hash'] = $photo['hash'];
            $allPhotos[$id]['uploadKey'] = $photo['uploadKey'];
            $allPhotos[$id]['dimension'] = $photo['dimension'];
            $allPhotos[$id]['url'] = $photo['url'];
        }

        $users = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($userIdList);

        foreach ($users as $id => $user) {
            $allPhotos[$id]['user'] = $user;
        }

        return $allPhotos;
    }

}
