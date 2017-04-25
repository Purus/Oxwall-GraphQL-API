<?php





/**
is software is intended for use with Oxwall Free Community Software http://www.oxwall.org/ and is a proprietary licensed product. 
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
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS;
OR BUSINESS INTERRUPTION) HOWEVER CAUSED
 * AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 */
class GRAPHQL_BOL_ForumService {
	
	private static $classInstance;
	
	public static function getInstance() {
		if (self::$classInstance === null) {
			self::$classInstance = new self();
		}
		return self::$classInstance;
	}
	
	private function __construct() {
		
	}
	
	public function getForumPosts() {
		$topics =   $this->getLatestTopicList(50,array());
		$allTopics = array();
		
		if (!$topics ){
			return [];
		}
		
		$userIdList = array();
		
		foreach ( $topics as $topic ){
			$userIdList[$topic['lastPost']['topicId']] = $topic['lastPost']['userId'];
			
			$id = $topic['id'];
			
			$allTopics[$id]['id'] = $id;
			$allTopics[$id]['title'] = $topic['title'];
			$allTopics[$id]['sticky'] = (boolean)$topic['sticky'];
			$allTopics[$id]['locked'] = (boolean)$topic['locked'];
			$allTopics[$id]['viewCount'] = $topic['viewCount'];
			$allTopics[$id]['status'] = $topic['status'];
			$allTopics[$id]['postCount'] = $topic['postCount'];
			$allTopics[$id]['topicUrl'] = $topic['topicUrl'];
			$allTopics[$id]['lastPostText'] = $topic['lastPost']['text'];
			$allTopics[$id]['lastPostTimestamp'] = $topic['lastPost']['createStamp'];
		}
		
		$users = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($userIdList);

        foreach ($userIdList as $topicId => $userId) {
            $allTopics[$topicId]['lastPostUser'] = $users[$userId];
        }

		// printVar($allTopics);
		return $allTopics;
	}
	
	
	private function getLatestTopicList( $topicLimit, $excludeGroupIdList = null ){
		$topicList = FORUM_BOL_TopicDao::getInstance()->findLastTopicList($topicLimit, $excludeGroupIdList);
		
		if ( !$topicList )
		        {
			return array();
		}
		
		$postIds = array();
		foreach ( $topicList as $topic )   {
			$postIds[] = $topic['lastPostId'];
		}
		
		$postList = $this->getTopicLastReplyList($postIds);
		
		$topics = array();
		foreach ( $topicList as $topic ) {
			if ( empty($postList[$topic['id']]) )   {
				continue;
			}
			
			//p			repare topic info
			            $topic['lastPost'] = $postList[$topic['id']];
			$topic['topicUrl'] = OW::getRouter()->urlForRoute('topic-default', array('topicId' => $topic['id']));
			$topics[] = $topic;
		}
		
		return $topics;
	}
	
	private function getTopicLastReplyList( $postIds ){
		$postDtoList = FORUM_BOL_PostDao::getInstance()->findByIdList($postIds);
		
		$postList = array();
		foreach ( $postDtoList as $postDto )
		        {
			// 			printVAr($postDto);
			
			$postInfo = array(
			                'postId' => $postDto->id,
			                'topicId' => $postDto->topicId,
			                'userId' => $postDto->userId,
			                'text' => strip_tags($postDto->text),
			                'createStamp' => $postDto->createStamp
			            );
			$postList[$postDto->topicId] = $postInfo;
		}
		
		return $postList;
	}
}
