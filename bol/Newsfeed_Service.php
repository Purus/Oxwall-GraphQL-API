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
class GRAPHQL_BOL_NewsfeedService {

    private static $classInstance;
    private $sharedData = array();

    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }
        return self::$classInstance;
    }

    private function __construct() {
        //  $commentList =  BOL_CommentService::getInstance()->findFullCommentList($params['entityType'], $params['entityId']);
    }

    public function getSiteNewsfeed() {

        $actionList = NEWSFEED_BOL_ActionDao::getInstance()->findSiteFeed();

        $actionIdList = $feeds = $actionIds = $userIds = array();

        foreach ($actionList as $actionDto) {
            $actionIdList[$actionDto->entityType . ':' . $actionDto->entityId] = $actionDto->id;
        }

        $activityList = NEWSFEED_BOL_ActivityDao::getInstance()->findSiteFeedActivity($actionIdList);

        $actionActivityList = array();
        foreach ($activityList as $activity) {
            $actionActivityList[$activity->actionId][$activity->id] = $activity;
        }

        $createActivityIdList = array();

        foreach ($actionList as $actionDto) {
            $aList = empty($actionActivityList[$actionDto->id]) ? array() : $actionActivityList[$actionDto->id];

            $action = $this->makeAction($actionDto, $aList);

            if ($action !== null) {
                $this->actionList[$actionDto->id] = $action;

                $createActivity = $action->getCreateActivity();

                if (!empty($createActivity)) {
                    $createActivityIdList[] = $createActivity->id;
                }
            }
        }

        $feedList = NEWSFEED_BOL_Service::getInstance()->findFeedListByActivityids($createActivityIdList);

        foreach ($this->actionList as $action) {
            $createActivity = $action->getCreateActivity();

            if (!empty($createActivity) && isset($feedList[$createActivity->id])) {
                $action->setFeedList($feedList[$createActivity->id]);
            }
        }

        $this->sharedData['configs'] = OW::getConfig()->getValues('newsfeed');

        $this->userIds = array();
        $entityList = array();
        foreach ($this->actionList as $action) {
            $this->userIds[$action->getUserId()] = $action->getUserId();
            $entityList[] = array(
                'entityType' => $action->getEntity()->type,
                'entityId' => $action->getEntity()->id,
                'pluginKey' => $action->getPluginKey(),
                'userId' => $action->getUserId(),
                'countOnPage' => $this->sharedData['configs']['comments_count']
            );
        }

        $this->sharedData['commentsData'] = BOL_CommentService::getInstance()->findBatchCommentsData($entityList);
        $this->sharedData['likesData'] = NEWSFEED_BOL_Service::getInstance()->findLikesByEntityList($entityList);

//        printVar($this->sharedData['commentsData']);
//        printVar($this->sharedData['likesData']);

        $i = 0;
        $outputData = array();

        foreach ($this->actionList as $action) {

            $this->sharedData['feedAutoId'] = $i++;
            $this->sharedData['displayType'] = NEWSFEED_CMP_Feed::DISPLAY_TYPE_ACTIVITY;
            $this->sharedData['feedType'] = 'site';
            $this->sharedData['feedId'] = 1;
            $outputData[$action->getId()] = $this->getTplData($action);
        }

        $users = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($this->userIds);

        foreach ($outputData as $id => $data) {

            $usersList = array();
            foreach ($data['users'] as $key => $userId) {
                $usersList[] = $users[$userId];
            }
            $outputData[$id]['user'] = $usersList;
        }

        return $outputData;
    }

    private function getTplData($action) {
        $data = $this->getActionData($action);

        $data['content'] = json_encode($data['content'], true);

        $permalink = empty($data['permalink']) ? NEWSFEED_BOL_Service::getInstance()->getActionPermalink($action->getId(), $this->sharedData['feedType'], $this->sharedData['feedId']) : null;

        $userIds = $data['action']['userIds'];

        foreach ($userIds as $key => $userId) {
            $this->userIds[] = $userId;
        }

        $item = array(
            'id' => $action->getId(),
            'content' => $data['content'],
            'entityType' => $data['action']['entityType'],
            'entityId' => $data['action']['entityId'],
            'createTime' => $data['action']['createTime'],
            'updateTime' => $action->getUpdateTime(),
            'users' => $userIds,
            'permalink' => $permalink,
            'activity' => $data['lastActivity'],
        );

        $item['autoId'] = $action->getId();

        return $item;
    }

    private function renderFormat($format, $vars) {
        return NEWSFEED_CLASS_FormatManager::getInstance()->renderFormat($format, $vars);
    }

    private function getActionData(NEWSFEED_CLASS_Action $action) {
        $activity = array();
        $createActivity = $action->getCreateActivity();
        $lastActivity = null;

        foreach ($action->getActivityList() as $a) {
            $activity[$a->id] = array(
                'activityType' => $a->activityType,
                'activityId' => $a->activityId,
                'id' => $a->id,
                'data' => json_decode($a->data, true),
                'timeStamp' => $a->timeStamp,
                'privacy' => $a->privacy,
                'userId' => $a->userId,
                'visibility' => $a->visibility
            );

            if ($lastActivity === null && !in_array($activity[$a->id]['activityType'], NEWSFEED_BOL_Service::getInstance()->SYSTEM_ACTIVITIES)) {
                $lastActivity = $activity[$a->id];
            }
        }

        $creatorIdList = $action->getCreatorIdList();
        $data = $this->mergeData($action->getData(), $action);

        $sameFeed = false;
        $feedList = array();
        foreach ($action->getFeedList() as $feed) {
            if (!$sameFeed) {
                $sameFeed = $this->sharedData['feedType'] == $feed->feedType && $this->sharedData['feedId'] == $feed->feedId;
            }

            $feedList[] = array(
                "feedType" => $feed->feedType,
                "feedId" => $feed->id
            );
        }

        $eventParams = array(
            'action' => array(
                'id' => $action->getId(),
                'entityType' => $action->getEntity()->type,
                'entityId' => $action->getEntity()->id,
                'pluginKey' => $action->getPluginKey(),
                'createTime' => $action->getCreateTime(),
                'userId' => $action->getUserId(), // backward compatibility with desktop version
                "userIds" => $creatorIdList,
                'format' => $action->getFormat(),
                'data' => $data,
                "feeds" => $feedList,
                "onOriginalFeed" => $sameFeed
            ),
            'activity' => $activity,
            'createActivity' => $createActivity,
            'lastActivity' => $lastActivity,
            'feedType' => $this->sharedData['feedType'],
            'feedId' => $this->sharedData['feedId'],
            'feedAutoId' => $this->sharedData['feedAutoId'],
            'autoId' => 1
        );

        $data['action'] = array(
            'userId' => $action->getUserId(), // backward compatibility with desktop version
            "userIds" => $creatorIdList,
            'createTime' => $action->getCreateTime()
        );

//        if ($lastActivity !== null) {
//            $data = $this->extendAction($data, $lastActivity);
//            $data = $this->extendActionData($data, $lastActivity);
//        }

        $event = new OW_Event('feed.on_item_render', $eventParams, $data);
        OW::getEventManager()->trigger($event);

        $outData = $event->getData();
        $outData["lastActivity"] = $lastActivity;

        return $this->mergeData($outData, $action);
    }

    private function makeAction($actionDto, $activityList) {
        if (empty($activityList)) {
            return null;
        }

        $action = new NEWSFEED_CLASS_Action();
        $action->setId($actionDto->id);
        $action->setData(json_decode($actionDto->data, true));
        $action->setEntity($actionDto->entityType, $actionDto->entityId);
        $action->setPluginKey($actionDto->pluginKey);
        $action->setFormat($actionDto->format);

        $action->setActivityList($activityList);

        return $action;
    }

    private function mergeData($data, NEWSFEED_CLASS_Action $_action) {
        $data = empty($data) ? array() : $data;

        $action = array(
            'userId' => $_action->getUserId(),
            'createTime' => $_action->getCreateTime(),
            'entityType' => $_action->getEntity()->type,
            'entityId' => $_action->getEntity()->id,
            'pluginKey' => $_action->getPluginKey(),
            'format' => $_action->getFormat()
        );

        $view = array('iconClass' => 'ow_ic_info', 'class' => '', 'style' => '');
        $defaults = array(
            'line' => null, 'string' => null, 'content' => null, 'context' => array(),
            'features' => array('comments', 'likes'), 'contextMenu' => array()
        );

        foreach ($defaults as $key => $value) {
            if (!isset($data[$key])) {
                $data[$key] = $value;
            }
        }

        if (!isset($data['view']) || !is_array($data['view'])) {
            $data['view'] = array();
        }

        $data['view'] = array_merge($view, $data['view']);

        if (!isset($data['action']) || !is_array($data['action'])) {
            $data['action'] = array();
        }

        $data['action'] = array_merge($action, $data['action']);

        $data['action']["userIds"] = empty($data['action']["userIds"]) ? array($data['action']["userId"]) : $data['action']["userIds"];

        return $data;
    }

}
