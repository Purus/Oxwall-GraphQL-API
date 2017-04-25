<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Oxwall\Types;

class QueryType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'Query',
            'fields' => [
                'site' => [
                    'type' => Types::site(),
                    'description' => 'Returns all detail about the social network',
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                        return $context->service->getSiteInfo();
                    }
                ],
                'photoAlbum' => [
                    'type' => Types::listOf(Types::photoAlbum()),
                    'description' => 'Returns all photo albums',
                    'args' => [
                        'id' => [
                            'type' => Types::int(),
                            'description' => 'Id for which the album details are requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'userId' => [
                            'type' => Types::int(),
                            'description' => 'User id for which the photo albums are requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'offset' => [
                            'type' => Types::int(),
                            'description' => 'Offset to fetch data from',
                            'defaultValue' => 1
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Data limit to fetch',
                            'defaultValue' => 50
                        ],
                    ],
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                        if ($args['id'] > 0) {
                            return $context->photoService->getAlbumInfoById($args['id']);
                        } else if ($args['userId'] > 0) {
                            return $context->photoService->getAlbums($args['userId'], $args['offset'], $args['limit']);
                        } else {
                            return $context->photoService->getAlbums(0, $args['offset'], $args['limit']);
                        }
                    }
                ],
                'newsfeed' => [
                    'type' => Types::listOf(Types::newsfeed()),
                    'description' => 'Returns all newsfeed items',
                    'args' => [
                        'offset' => [
                            'type' => Types::int(),
                            'description' => 'Offset to fetch data from',
                            'defaultValue' => 1
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Data limit to fetch',
                            'defaultValue' => 50
                        ],
                    ],
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                        return $context->newsfeedService->getSiteNewsfeed();
                    }
                ],
                'birthday' => [
                    'type' => Types::listOf(Types::user()),
                    'description' => 'Returns all users with birthday',
                    'args' => [
                        'key' => [
                            'type' => Types::birthdayEnum(),
                            'description' => 'Birthdays for current day or current week',
                            'defaultValue' => 'today'
                        ]
                    ],
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                        return $context->birthdayService->getBirthdayUsers($args['key'],20);
                    }
                ],                
                'photo' => [
                    'type' => Types::listOf(Types::photo()),
                    'description' => 'Returns all blog posts',
                    'args' => [
                        'id' => [
                            'type' => Types::id(),
                            'description' => 'Id for which the photos are requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'userId' => [
                            'type' => Types::id(),
                            'description' => 'User id for which the photos are requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'key' => [
                            'type' => Types::photoListEnum(),
                            'description' => 'Type of photo list to get',
                            'defaultValue' => 'latest'
                        ],
                        'offset' => [
                            'type' => Types::int(),
                            'description' => 'Offset to fetch data from',
                            'defaultValue' => 1
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Data limit to fetch',
                            'defaultValue' => 50
                        ],
                    ],
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                        $context->profileService->getProfileById(1);
                        if ($args['id'] > 0) {
                            return $context->photoService->getPhotoById($args['id']);
                        } else if ($args['userId'] > 0) {
                            return $context->photoService->getPhotoByUserId($args['userId'], $args['offset'], $args['limit']);
                        } else {
                            return $context->photoService->getPhotoList($args['key'], $args['offset'], $args['limit']);
                        }
                    }
                ],
                'group' => [
                    'type' => Types::listOf(Types::group()),
                    'description' => 'Returns all groups',
                    'args' => [
                        'id' => [
                            'type' => Types::id(),
                            'description' => 'Id for which the group is requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'userId' => [
                            'type' => Types::id(),
                            'description' => 'User id for which the groups are requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'key' => [
                            'type' => Types::groupListEnum(),
                            'description' => 'Type of group list to get',
                            'defaultValue' => 'latest'
                        ],
                        'offset' => [
                            'type' => Types::int(),
                            'description' => 'Offset to fetch data from',
                            'defaultValue' => 1
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Data limit to fetch',
                            'defaultValue' => 50
                        ],
                    ],
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                        if ($args['id'] > 0) {
                            return $context->groupService->getGroupById($args['id'],true);
                        } else if ($args['userId'] > 0) {
                            return $context->groupService->findUserGroupList($args['userId'], true,$args['offset'], $args['limit']);
                        } else {
                            return $context->groupService->findGroupList($args['key'],true, $args['offset'], $args['limit']);
                        }
                    }
                ],
                'event' => [
                    'type' => Types::listOf(Types::event()),
                    'description' => 'Returns all events',
                    'args' => [
                        'id' => [
                            'type' => Types::id(),
                            'description' => 'Id for which the event is requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'userId' => [
                            'type' => Types::id(),
                            'description' => 'User id for which the events are requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'key' => [
                            'type' => Types::eventListEnum(),
                            'description' => 'Type of event list to get',
                            'defaultValue' => 'latest'
                        ],
                        'offset' => [
                            'type' => Types::int(),
                            'description' => 'Offset to fetch data from',
                            'defaultValue' => 1
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Data limit to fetch',
                            'defaultValue' => 50
                        ],
                    ],
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                        if ($args['id'] > 0) {
                            return $context->eventService->getEventById($args['id'],true);
                        } else if ($args['userId'] > 0) {
                            return $context->eventService->findUserGroupList('created',$args['userId'], false,$args['offset'], $args['limit']);
                        } else {
                            return $context->eventService->findEventsList($args['key'],0, false,$args['offset'], $args['limit']);
                        }
                    }
                ], 
                'forum' => [
                    'type' => Types::listOf(Types::forum()),
                    'description' => 'Returns all forum posts',
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                            return $context->forumService->getForumPosts();
                    }
                ],                                
                'video' => [
                    'type' => Types::listOf(Types::video()),
                    'description' => 'Returns all videos',
                    'args' => [
                        'id' => [
                            'type' => Types::id(),
                            'description' => 'Id for which the video is requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'userId' => [
                            'type' => Types::id(),
                            'description' => 'User id for which the videos are requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'key' => [
                            'type' => Types::videoListEnum(),
                            'description' => 'Type of group list to get',
                            'defaultValue' => 'latest'
                        ],
                        'offset' => [
                            'type' => Types::int(),
                            'description' => 'Offset to fetch data from',
                            'defaultValue' => 1
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Data limit to fetch',
                            'defaultValue' => 50
                        ],
                    ],
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                        if ($args['id'] > 0) {
                            return $context->videoService->getVideoById($args['id']);
                        } else if ($args['userId'] > 0) {
                            return $context->videoService->findUserVideos($args['userId'],$args['offset'], $args['limit']);
                        } else {
                            return $context->videoService->findVideosList($args['key'], $args['offset'], $args['limit']);
                        }
                    }
                ],                
                'blog' => [
                    'type' => Types::listOf(Types::blog()),
                    'description' => 'Returns all blog posts',
                    'args' => [
                        'id' => [
                            'type' => Types::id(),
                            'description' => 'User Id for which the blog posts are requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'tag' => [
                            'type' => Types::string(),
                            'description' => 'Tag for which the blog posts are requested. Other arguments will be ignored',
                            'defaultValue' => ''
                        ],
                        'key' => [
                            'type' => Types::blogListEnum(),
                            'description' => 'Type of blog posts to get',
                            'defaultValue' => 'latest'
                        ],
                        'offset' => [
                            'type' => Types::int(),
                            'description' => 'Offset to fetch data from',
                            'defaultValue' => 0
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Data limit to fetch',
                            'defaultValue' => 50
                        ],
                    ],
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                        if ($args['id'] > 0) {
                            return $context->blogService->getBlogPostById($args['id']);
                        } else if ($args['tag'] != '') {
                            return $context->blogService->getBlogPosts('browse-by-tag', 0, 50, $args['tag']);
                        } else {
                            return $context->blogService->getBlogPosts($args['key'], $args['offset'], $args['limit']);
                        }
                    }
                ],
                'user' => [
                    'type' => Types::listOf(Types::user()),
                    'description' => 'Returns all users',
                    'args' => [
                        'key' => [
                            'type' => Types::userListEnum(),
                            'description' => 'User type to fetch',
                            'defaultValue' => 'latest'
                        ],
                        'id' => [
                            'type' => Types::id(),
                            'description' => 'User Id for which data is requested. Other arguments will be ignored',
                            'defaultValue' => 0
                        ],
                        'email' => [
                            'type' => Types::string(),
                            'description' => 'Find user based on email. Other arguments will be ignored',
                            'defaultValue' => '0'
                        ],
                        'username' => [
                            'type' => Types::string(),
                            'description' => 'Find user based on username. Other arguments will be ignored',
                            'defaultValue' => '0'
                        ],
                        'offset' => [
                            'type' => Types::int(),
                            'description' => 'Offset to fetch data from',
                            'defaultValue' => 0
                        ],
                        'limit' => [
                            'type' => Types::int(),
                            'description' => 'Data limit to fetch',
                            'defaultValue' => 50
                        ],
                    ],
                    'resolve' => function($value, $args, $context, ResolveInfo $info) {
                        if ($args['id'] > 0) {
                            return $context->userService->getUserById($args['id']);
                        } else if ($args['email'] != '0') {
                            return $context->userService->getUserByEmail($args['email']);
                        } else if ($args['username'] != '0') {
                            return $context->userService->getUserByUsername($args['username']);
                        } else {
                            return $context->userService->getAllUsers($args['key'], $args['offset'], $args['limit']);
                        }
                    }
                ],
                'hello' => [
                    'type' => Types::string(),
                    'description' => 'Default root query',
                    'resolve' => function() {
                        return 'Your graphql-php endpoint is ready! Use GraphiQL to browse API';
                    }
                ]
            ]
        ];
        parent::__construct($config);
    }

}
