<?php

namespace GraphQL\Oxwall;

use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use GraphQL\Oxwall\Types\QueryType;
use GraphQL\Oxwall\Types\UserType;
use GraphQL\Oxwall\Types\SiteInfoType;
use GraphQL\Oxwall\Types\BlogType;
use GraphQL\Oxwall\Types\PluginType;
use GraphQL\Oxwall\Types\Scalar\UrlType;
use GraphQL\Oxwall\Types\Scalar\EmailType;
use GraphQL\Oxwall\Types\Enum\UserListEnum;
use GraphQL\Oxwall\Types\Enum\BlogListEnum;

/**
 * Class Types
 *
 * Acts as a registry and factory for your types.
 *
 * @package GraphQL\Oxwall
 */
class Types {

    // Object types:
    private static $user;
    private static $site;
    private static $plugin;
    private static $blog;
    private static $query;
    
    /**
     * @return UserType
     */
    public static function user() {
        return self::$user ?: (self::$user = new UserType());
    }

    /**
     * @return SitInfoType
     */
    public static function site() {
        return self::$site ?: (self::$site = new SiteInfoType());
    }

    /**
     * @return PluginType
     */
    public static function plugin() {
        return self::$plugin ?: (self::$plugin = new PluginType());
    }

    /**
     * @return ImageType
     */
    public static function blog() {
        return self::$blog ?: (self::$blog = new BlogType());
    }

    /**
     * @return QueryType
     */
    public static function query() {
        return self::$query ?: (self::$query = new QueryType());
    }

    // Custom Scalar types:
    private static $urlType;
    private static $emailType;

    public static function email() {
        return self::$emailType ?: (self::$emailType = EmailType::create());
    }

    /**
     * @return UrlType
     */
    public static function url() {
        return self::$urlType ?: (self::$urlType = new UrlType());
    }

        private static $userListEnum;
        private static $blogListEnum;
        
        /**
     * @return UserListEnum
     */
    public static function userListEnum() {
        return self::$userListEnum ?: (self::$userListEnum = new UserListEnum());
    }
    
            /**
     * @return BlogListEnum
     */
    public static function blogListEnum() {
        return self::$blogListEnum ?: (self::$blogListEnum = new BlogListEnum());
    }
    
    /**
     * @param $name
     * @param null $objectKey
     * @return array
     */
    public static function htmlField($name, $objectKey = null) {
        return HtmlField::build($name, $objectKey);
    }

    public static function boolean() {
        return Type::boolean();
    }

    /**
     * @return \GraphQL\Type\Definition\FloatType
     */
    public static function float(){
    return Type::float();
    }

/**
 * @return \GraphQL\Type\Definition\IDType
 */
public static function id() {
    return Type::id();
}

/**
 * @return \GraphQL\Type\Definition\IntType
 */
public static function int(){
return Type::int();
    }

/**
 * @return \GraphQL\Type\Definition\StringType
 */
public static function string(){
return Type::string();
    }

/**
 * @param Type $type
 * @return ListOfType
 */
public static function listOf($type) {
return new ListOfType($type);
}

/**
 * @param Type $type
 * @return NonNull
 */
public static function nonNull($type) {
return new NonNull($type);
}

}