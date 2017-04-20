<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class VideoType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'Video',
            'description' => 'Video Information',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::int(),
                        'description' => 'Video Id'
                    ],
                    'user' => [
                        'type' => Types::user(),
                        'description' => 'User who added the video'
                    ],
                    'title' => [
                        'type' => Types::string(),
                        'description' => 'Video title'
                    ],
                    'description' => [
                        'type' => Types::string(),
                        'description' => 'Description of the video'
                    ],
                    'timestamp' => [
                        'type' => Types::int(),
                        'description' => 'Timestamp of group creation'
                    ],
                    'url' => [
                        'type' => Types::url(),
                        'description' => 'URL of the video'
                    ],                    
                    'thumbnail' => [
                        'type' => Types::url(),
                        'description' => 'URL of the video thumbnail'
                    ],
                    'provider' => [
                        'type' => Types::string(),
                        'description' => 'Video provider'
                    ],                    
                    'code' => [
                        'type' => Types::string(),
                        'description' => 'Playable video code'
                    ]
                ];
            },
        ];
        parent::__construct($config);
    }

}
