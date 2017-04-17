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
    private $service;

    public static function getInstance() {
        if (self::$classInstance === null) {
            self::$classInstance = new self();
        }

        return self::$classInstance;
    }

    private function __construct() {
        $this->service = PHOTO_BOL_PhotoService::getInstance();
    }

    public function getPhotoByUserId($userId, $page, $limit) {
        $photos = $this->service->findPhotoListByUserId($userId, $page, $limit);

        if (!$photos) {
            return [];
        }

        $allPhotos = $userIdList = $albumIdList = array();

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
            $allPhotos[$id]['previewPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'preview', $photo);
            $allPhotos[$id]['originalPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'original', $photo);
            $allPhotos[$id]['fullPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'fullscreen', $photo);
            $allPhotos[$id]['mainPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'main', $photo);
            $allPhotos[$id]['smallPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'small', $photo);
            $allPhotos[$id]['userId'] = $photo['userId'];

            $albums = $this->getAlbumInfoById($photo['albumId']);

            $allPhotos[$id]['album'] = $albums[0];
        }

        $users = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($userIdList);

        foreach ($allPhotos as $id => $photo) {
            $allPhotos[$id]['user'] = $users[$photo['userId']];
        }

        return $allPhotos;
    }

    public function getPhotoById($id) {
        $photo = $this->service->findPhotoById($id);

        if (!$photo) {
            return [];
        }

        $allPhotos = $userIdList = $albumIdList = array();

        $id = $photo->id;
        $photoInfo = get_object_vars($photo);

        $allPhotos[$id]['id'] = $id;
        $allPhotos[$id]['description'] = $photo->description;
        $allPhotos[$id]['timestamp'] = $photo->addDatetime;
        $allPhotos[$id]['status'] = $photo->status;
        $allPhotos[$id]['hasFullsize'] = $photo->hasFullsize;
        $allPhotos[$id]['privacy'] = $photo->privacy;
        $allPhotos[$id]['hash'] = $photo->hash;
        $allPhotos[$id]['uploadKey'] = $photo->uploadKey;
        $allPhotos[$id]['dimension'] = $photo->dimension;
        $allPhotos[$id]['previewPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'preview', $photoInfo);
        $allPhotos[$id]['originalPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'original', $photoInfo);
        $allPhotos[$id]['fullPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'fullscreen', $photoInfo);
        $allPhotos[$id]['mainPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'main', $photoInfo);
        $allPhotos[$id]['smallPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'small', $photoInfo);
        $allPhotos[$id]['userId'] = 0;

        $albums = $this->getAlbumInfoById($photo->albumId);

        $allPhotos[$id]['album'] = $albums[0];

        return $allPhotos;
    }

    public function getPhotoList($listType, $page, $limit) {
        $photos = $this->service->findPhotoList($listType, $page, $limit);

        if (!$photos) {
            return [];
        }

        $allPhotos = $userIdList = $albumIdList = array();

        foreach ($photos as $photo) {
//            printVar($photo);
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
            $allPhotos[$id]['previewPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'preview', $photo);
            $allPhotos[$id]['originalPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'original', $photo);
            $allPhotos[$id]['fullPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'fullscreen', $photo);
            $allPhotos[$id]['mainPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'main', $photo);
            $allPhotos[$id]['smallPhoto'] = $this->service->getPhotoUrlByPhotoInfo($id, 'small', $photo);
            $allPhotos[$id]['userId'] = $photo['userId'];

            $albums = $this->getAlbumInfoById($photo['albumId']);
            $allPhotos[$id]['album'] = $albums[0];
        }

        $users = GRAPHQL_BOL_UserService::getInstance()->getUsersListByIdList($userIdList);

        foreach ($allPhotos as $id => $photo) {
            $allPhotos[$id]['user'] = $users[$photo['userId']];
        }

        return $allPhotos;
    }

    public function getAlbums($userId, $page, $limit) {
        $first = ( $page - 1 ) * $limit;

        $example = new OW_Example();

        if ($userId > 0) {
            $example->andFieldEqual('userId', $userId);
        }

        $example->setLimitClause($first, $limit);

        $albums = PHOTO_BOL_PhotoAlbumDao::getInstance()->findListByExample($example);

        $allAlbums = array();

        if (!$albums) {
            return [];
        }

        foreach ($albums as $album) {
            $allAlbums = $this->getAlbumInfoById($album->id);
        }

        return $allAlbums;
    }

    public function getAlbumInfoById($albumId) {
        $albumInfo = array();
        $dto = PHOTO_BOL_PhotoAlbumService::getInstance()->findAlbumById($albumId);

        if (!$dto) {
            return [];
        }

        $covers = PHOTO_BOL_PhotoAlbumCoverDao::getInstance()->getAlbumCoverUrlListForAlbumIdList(array($albumId));
        $counters = PHOTO_BOL_PhotoAlbumService::getInstance()->countAlbumPhotosForList(array($albumId));

        $user = GRAPHQL_BOL_UserService::getInstance()->getUserById($dto->userId);

        $albumInfo['name'] = $dto->name;
        $albumInfo['description'] = $dto->description;
        $albumInfo['timestamp'] = $dto->createDatetime;
        $albumInfo['id'] = $dto->id;
        $albumInfo['cover'] = $covers[$albumId];
        $albumInfo['photosCount'] = $counters[$albumId];
        $albumInfo['user'] = $user[$dto->userId];

        return [$albumInfo];
    }

}
