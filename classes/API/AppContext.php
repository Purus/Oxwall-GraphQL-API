<?php

namespace GraphQL\Oxwall;

/**
 * Class AppContext
 * Instance available in all GraphQL resolvers as 3rd argument
 *
 * @package GraphQL\Oxwall
 */
class AppContext {

    /**
     * @var string
     */
    public $rootUrl;

    /**
     * @var User
     */
    public $viewer;

    /**
     * @var \mixed
     */
    public $request;

    /**
     * @var Generic Service instance
     */
    public $service;

    /**
     * @var User Service instance
     */
    public $userService;

    /**
     * @var Blog Service instance
     */
    public $blogService;

    /**
     * @var Blog Service instance
     */
    public $photoService;

    /**
     * @var User Profile Service instance
     */
    public $profileService;

    /**
     * @var Newsfeed Service instance
     */
    public $newsfeedService;

    /**
     * @var Group Service instance
     */
    public $groupService;

    /**
     * @var Video Service instance
     */
    public $videoService;

    /**
     * @var Birthday Service instance
     */
public $birthdayService;

    /**
     * @var Events Service instance
     */
public $eventService;

    /**
     * @var Forum Service instance
     */
public $forumService;
}
