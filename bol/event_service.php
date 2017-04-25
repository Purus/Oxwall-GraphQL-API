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
class GRAPHQL_BOL_EventService {
    private static $classInstance;
    
    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }
        return self::$classInstance;
    }
    
    private function __construct() {
        
    }
    
    public function getEventById($id, $hasMembers = false) {
        $event = EVENT_BOL_EventService::getInstance()->findEvent($id);
        
        if (!$event) {
            return [];
        }
        
        $events = $this->processEvents(array($event), $hasMembers);
        
        return $events[$id];
    }
    
    public function findEventsList($listType, $userId, $hasMembers, $page, $count) {
    
        switch ($listType){
          case 'created':
            $events = EVENT_BOL_EventService::getInstance()->findUserEvents($userId, $page, null, true);
            break;
          case 'joined':
            $events = EVENT_BOL_EventService::getInstance()->findUserParticipatedEvents($userId, $page, null, true);
            break;
          case 'latest':
            $events = EVENT_BOL_EventService::getInstance()->findPublicEvents($page, $count);
            break;
          case 'past':
            $events = EVENT_BOL_EventService::getInstance()->findPublicEvents($page, null, true);
            break;          
        }
        
        if(!$events){
            return [];
        }

        return $this->processEvents($events, $hasMembers);
    }
     
    public function processEvents($events, $hasMembers) {
        $defaultImage = EVENT_BOL_EventService::getInstance()->generateDefaultImageUrl();
        
        $allEvents = $idList = array();
        
        foreach ($events as $event) {
            $id = $event->id;
            $idList[] = $id;
            $userList[] = $event->getUserId();
            
            $allEvents[$id]['id'] = $id;
            $allEvents[$id]['title'] = strip_tags($event->getTitle());
            $allEvents[$id]['description'] = strip_tags($event->getDescription());
            $allEvents[$id]['createTimestamp'] = $event->getCreateTimeStamp();
            $allEvents[$id]['startTimestamp'] = $event->getStartTimeStamp();            
            $allEvents[$id]['endTimestamp'] = $event->getEndTimeStamp();
            $allEvents[$id]['startTimeDisable'] = $event->getStartTimeDisable();
            $allEvents[$id]['endTimeDisable'] = $event->getEndTimeDisable();
            $allEvents[$id]['location'] = $event->getLocation();
            
            $image = $event->getImage() ?EVENT_BOL_EventService::getInstance()->generateImageUrl($event->getImage(), true) : $defaultImage;
            $allEvents[$id]['image'] = $image;
            
            $allEvents[$id]['url'] = OW::getRouter()->urlForRoute('event.view', array('eventId' => $id));
           
           if ($hasMembers) {
                $allGroups[$id]['members'] = [];
            }
        }
        
        $users = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($userList);
        
        foreach ($users as $id => $user) {
            $allEvents[$id]['user'] = $user;
        }
        
        return $allEvents;
    }
}
