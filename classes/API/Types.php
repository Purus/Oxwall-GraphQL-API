<?php

namespace GraphQL\Oxwall;

use GraphQL\Oxwall\Types\Scalar\UrlType;
use GraphQL\Oxwall\Types\Scalar\EmailType;
use GraphQL\Oxwall\Types\Enum\UserListEnum;
use GraphQL\Oxwall\Types\Enum\BlogListEnum;
use GraphQL\Oxwall\Types\Enum\PhotoListEnum;
use GraphQL\Oxwall\Types\Enum\GroupListEnum;
use GraphQL\Oxwall\Types\Enum\VideoListEnum;
use GraphQL\Oxwall\Types\Enum\BirthdayEnum;
use GraphQL\Oxwall\Types\Enum\EventListEnum;
use GraphQL\Type\Definition\ListOfType;
use GraphQL\Type\Definition\NonNull;
use GraphQL\Type\Definition\Type;
use GraphQL\Oxwall\Types\QueryType;
use GraphQL\Oxwall\Types\UserType;
use GraphQL\Oxwall\Types\SiteInfoType;
use GraphQL\Oxwall\Types\BlogType;
use GraphQL\Oxwall\Types\PluginType;
use GraphQL\Oxwall\Types\PhotoType;
use GraphQL\Oxwall\Types\PhotoAlbumType;
use GraphQL\Oxwall\Types\UserProfileType;
use GraphQL\Oxwall\Types\MenuType;
use GraphQL\Oxwall\Types\NewsfeedType;
use GraphQL\Oxwall\Types\GroupType;
use GraphQL\Oxwall\Types\VideoType;
use GraphQL\Oxwall\Types\EventType;
use GraphQL\Oxwall\Types\ForumType;

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
    private static $photo;
    private static $photoalbum;
    private static $profile;
    private static $menu;
    private static $newsfeed;
    private static $group;
    private static $video;
    private static $event;
    private static $forum;

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
     * @return PhotoType
     */
    public static function photo() {
        return self::$photo ?: (self::$photo = new PhotoType());
    }

    /**
     * @return PhotoAlbumType
     */
    public static function photoAlbum() {
        return self::$photoalbum ?: (self::$photoalbum = new PhotoAlbumType());
    }

    /**
     * @return UserProfileType
     */
    public static function profile() {
        return self::$profile ?: (self::$profile = new UserProfileType());
    }

    /**
     * @return MenuType
     */
    public static function menu() {
        return self::$menu ?: (self::$menu = new MenuType());
    }

    /**
     * @return NewsfeedType
     */
    public static function newsfeed() {
        return self::$newsfeed ?: (self::$newsfeed = new NewsfeedType());
    }

    /**
     * @return GroupType
     */
    public static function group() {
        return self::$group ?: (self::$group = new GroupType());
    }

    /**
     * @return VideoType
     */
    public static function video() {
        return self::$video ?: (self::$video = new VideoType());
    }

    /**
     * @return EventType
     */
    public static function event() {
        return self::$event ?: (self::$event = new EventType());
    }

    /**
     * @return ForumType
     */
    public static function forum() {
        return self::$forum ?: (self::$forum = new ForumType());
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
    private static $photoListEnum;
    private static $groupListEnum;
    private static $videoListEnum;
    private static $birthdayEnum;
    private static $eventListEnum;

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
     * @return PhotoListEnum
     */
    public static function photoListEnum() {
        return self::$photoListEnum ?: (self::$photoListEnum = new PhotoListEnum());
    }

    /**
     * @return GroupListEnum
     */
    public static function groupListEnum() {
        return self::$groupListEnum ?: (self::$groupListEnum = new GroupListEnum());
    }

    /**
     * @return VideoListEnum
     */
    public static function videoListEnum() {
        return self::$videoListEnum ?: (self::$videoListEnum = new VideoListEnum());
    }

    /**
     * @return BirthdayEnum
     */
    public static function birthdayEnum() {
        return self::$birthdayEnum ?: (self::$birthdayEnum = new BirthdayEnum());
    }

    /**
     * @return EventListEnum
     */
    public static function eventListEnum() {
        return self::$eventListEnum ?: (self::$eventListEnum = new EventListEnum());
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
