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
class GRAPHQL_BOL_UserService {

    private static $classInstance;

    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct() {
        
    }

    private function getUserDeatils($users) {
        $allUsers = $avatarsList = $idList = array();

        foreach ($users as $user) {
            $id = $user->id;
            $idList[] = $id;

            $allUsers[$id]['id'] = $user->id;
            $allUsers[$id]['userName'] = $user->username;
            $allUsers[$id]['email'] = $user->email;
            $allUsers[$id]['joinStamp'] = $user->joinStamp;
            $allUsers[$id]['joinIp'] = long2ip($user->joinIp);
            $allUsers[$id]['activityStamp'] = $user->activityStamp;
            $allUsers[$id]['emailVerify'] = (int) $user->emailVerify == 1;
        }

        $avatars = BOL_AvatarService::getInstance()->getDataForUserAvatars($idList);
        $onlineInfo = BOL_UserService::getInstance()->findOnlineStatusForUserList($idList);

        foreach ($avatars as $userId => $avatarData) {
            $allUsers[$userId]['avatar'] = isset($avatarData['src']) ? $avatarData['src'] : '';
            $allUsers[$userId]['url'] = isset($avatarData['url']) ? $avatarData['url'] : '';
            $allUsers[$userId]['title'] = isset($avatarData['title']) ? $avatarData['title'] : '';
        }

        foreach ($onlineInfo as $userId => $isOnline) {
            $allUsers[$userId]['online'] = $isOnline;
        }

        return $allUsers;
    }

    public function getUserById($id) {
        $user = BOL_UserService::getInstance()->findUserById($id);

        if (!$user) {
            return [];
        }

        return $this->getUserDeatils(array($user));
    }

    public function getUserByEmail($email) {
        $user = BOL_UserService::getInstance()->findByEmail($email);

        if (!$user) {
            return [];
        }

        return $this->getUserDeatils(array($user));
    }

    public function getUserByUsername($username) {
        $user = BOL_UserService::getInstance()->findByUsername($username);

        if (!$user) {
            return [];
        }

        return $this->getUserDeatils(array($user));
    }

    public function getAllUsers($type, $offset, $limit) {
        list($users, $count) = BOL_UserService::getInstance()->getDataForUsersList($type, $offset, $limit);

        return $this->getUserDeatils($users);
    }

}
