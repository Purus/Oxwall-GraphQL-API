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
class GRAPHQL_BOL_GroupsService {

    private static $classInstance;

    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }
        return self::$classInstance;
    }

    private function __construct() {
        
    }

    public function getGroupById($id, $hasMembers = false) {

        $group = GROUPS_BOL_Service::getInstance()->findGroupById($id);

        if (!$dto) {
            return [];
        }

        $groupInfo = array();

        $groupInfo[$id]['id'] = $id;
        $groupInfo[$id]['title'] = strip_tags($group->title);
        $groupInfo[$id]['description'] = strip_tags($group->description);
        $groupInfo[$id]['timestamp'] = $group->timeStamp;
        $groupInfo[$id]['imageSmall'] = GROUPS_BOL_Service::getInstance()->getGroupImageUrl($group, 0);
        $groupInfo[$id]['imageBig'] = GROUPS_BOL_Service::getInstance()->getGroupImageUrl($group, 1);
        $groupInfo[$id]['url'] = OW::getRouter()->urlForRoute('groups-view', array('groupId' => $group->id));
        $groupInfo[$id]['user'] = GRAPHQL_BOL_UserService::getInstance()->getUserById($group->userId);
        $groupInfo[$id]['userCount'] = GROUPS_BOL_Service::getInstance()->findUserListCount($id);

        if ($hasMembers) {
            $groupUsersList = GROUPS_BOL_Service::getInstance()->findGroupUserIdList($id);

            $gUsers = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($groupUsersList);
            $groupInfo[$id]['members'] = $gUsers;
        }

        return $groupInfo;
    }

    public function findGroupList($case, $hasMembers, $first, $count) {
        $groups = GROUPS_BOL_Service::getInstance()->findGroupList($case, $first, $count);
        
        return $this->processGroups($groups, $hasMembers);
    }
    
    public function findUserGroupList($userId, $hasMembers, $first, $count) {
        $groups = GROUPS_BOL_Service::getInstance()->findMyGroups($userId, $first, $count);
        
        return $this->processGroups($groups, $hasMembers);
    }
    
    public function processGroups($groups, $hasMembers) {
        
        $allGroups = $idList = array();

        foreach ($groups as $group) {
            $id = $group->id;

            $idList[] = $id;
            $userList[] = $group->userId;

            $allGroups[$id]['id'] = $id;
            $allGroups[$id]['title'] = strip_tags($group->title);
            $allGroups[$id]['description'] = strip_tags($group->description);
            $allGroups[$id]['timestamp'] = $group->timeStamp;
            $allGroups[$id]['imageSmall'] = GROUPS_BOL_Service::getInstance()->getGroupImageUrl($group, 0);
            $allGroups[$id]['imageBig'] = GROUPS_BOL_Service::getInstance()->getGroupImageUrl($group, 1);
            $allGroups[$id]['url'] = OW::getRouter()->urlForRoute('groups-view', array('groupId' => $group->id));

            if ($hasMembers) {
                $groupUsersList = GROUPS_BOL_Service::getInstance()->findGroupUserIdList($id);

                $gUsers = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($groupUsersList);
                $allGroups[$id]['members'] = $gUsers;
            }
        }

        $usersCount = GROUPS_BOL_Service::getInstance()->findUserCountForList($idList);
        foreach ($usersCount as $id => $count) {
            $allGroups[$id]['userCount'] = $count;
        }

        $users = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($userList);
        foreach ($users as $id => $user) {
            $allGroups[$id]['user'] = $user;
        }

        return $allGroups;
    }

}
