<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class PhotoType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Photo',
            'description' => 'Photos uploaded',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::id(),
                        'description' => 'Photo Id'
                    ],
                    'user' => [
                        'type' => Types::user(),
                        'description' => 'User who uploaded the photo'
                    ],
                    'description' => [
                        'type' => Types::string(),
                        'description' => 'Description of the photo'
                    ],
                    'timestamp' => [
                        'type' => Types::int(),
                        'description' => 'Timestamp of photo upload'
                    ],
                    'album' => [
                        'type' => Types::photoAlbum(),
                        'description' => 'Photo Album'
                    ],
                    'privacy' => [
                        'type' => Types::string(),
                        'description' => 'Privacy of the photo'
                    ],
                    'url' => [
                        'type' => Types::url(),
                        'description' => 'Web Url of the photo'
                    ]
                ];
            },
        ];
        parent::__construct($config);
    }
}
