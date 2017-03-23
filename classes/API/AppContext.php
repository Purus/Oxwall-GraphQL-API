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
     * @var Oxwall Configuration object
     */
    public $config;

    public $plugin;
    
    public $users;
}
