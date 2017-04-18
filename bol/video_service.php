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
class GRAPHQL_BOL_VideoService {
    private static $classInstance;
    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }
        return self::$classInstance;
    }
    private function __construct() {
        
    }
    public function getGroupById($id) {
        $video = VIDEO_BOL_ClipService::getInstance()->findClipById($id);
        
        if (!$video) {
            return [];
        }
        
         $list =  $this->processVideos(array($video));
         
         return $list[$id];
    }
    
    public function findVideosList($case, $first, $count) {
        $videos = VIDEO_BOL_ClipService::getInstance()->findClipsList($case, $first, $count);
        
        if (!$videos) {
            return [];
        } 
        
        return $this->processVideos($videos);
    }
    
    public function findUserVideos($userId, $first, $count) {
        $videos = VIDEO_BOL_ClipService::getInstance()->findUserClipsList($userId, $first, $count);

        if (!$videos) {
            return [];
        } 
        
        return $this->processVideos($videos);
    }
    
    public function processVideos($videos) {
        $defaultImage = VIDEO_BOL_ClipService::getInstance()->getClipDefaultThumbUrl();
        
        $videoInfo = $idList = array();
        
        foreach ($videos as $video) {
            $id = $video->id;
            $idList[] = $id;
            $userList[] = $video->userId;
            $videoInfo[$id]['id'] = $id;
            $videoInfo[$id]['title'] = strip_tags($video->title);
            $videoInfo[$id]['description'] = strip_tags($video->description);
            $videoInfo[$id]['timestamp'] = $video->addDatetime;
            $videoInfo[$id]['code'] = $video->code;
            $videoInfo[$id]['url'] = OW::getRouter()->urlForRoute('view_clip', array('id' => $video->id));
            $videoInfo[$id]['user'] = GRAPHQL_BOL_UserService::getInstance()->getUserById($video->userId);
            
            $thumbnail = VIDEO_BOL_ClipService::getInstance()->getClipThumbUrl($video->id)
            
            if ( $thumbnail == "undefined" ){
                $videoInfo[$id]["thumbnail"] = $defaultImage;
            }else{
                $videoInfo[$id]["thumbnail"] = $thumbnail;
            }
        }
        
        $users = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($userList);
        
        foreach ($users as $id => $user) {
            $videoInfo[$id]['user'] = $user;
        }
        
        return $videoInfo;
    }
}
