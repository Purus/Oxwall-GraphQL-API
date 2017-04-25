<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class ForumType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'Forum',
            'description' => 'Forum Information',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::int(),
                        'description' => 'Group Id'
                    ],
                    'title' => [
                        'type' => Types::string(),
                        'description' => 'Group title'
                    ],
                    'sticky' => [
                        'type' => Types::boolean(),
                        'description' => 'Is sticky?'
                    ],
                    'locked' => [
                        'type' => Types::boolean(),
                        'description' => 'Is locked?'
                    ],    
                    'viewCount' => [
                        'type' => Types::int(),
                        'description' => 'Total view count'
                    ],
                    'postCount' => [
                        'type' => Types::int(),
                        'description' => 'Total post count'
                    ],                    
                    'status' => [
                        'type' => Types::string(),
                        'description' => 'Status of the post'
                    ],                     
                    'lastPostUser' => [
                        'type' => Types::user(),
                        'description' => 'Last post update user'
                    ], 
                    'lastPostText' => [
                        'type' => Types::string(),
                        'description' => 'Last post text'
                    ], 
                    'lastPostTimestamp' => [
                        'type' => Types::int(),
                        'description' => 'Last post update timestamp'
                    ],                                         
                    'topicUrl' => [
                        'type' => Types::url(),
                        'description' => 'Last topic URL'
                    ],                                                                              
                ];
            },
        ];
        parent::__construct($config);
    }

}
