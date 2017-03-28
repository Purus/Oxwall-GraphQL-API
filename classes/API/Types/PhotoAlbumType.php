<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class PhotoAlbumType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'PhotoAlbum',
            'description' => 'Photo Album',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::id(),
                        'description' => 'Photo Id'
                    ],
                    'name' => [
                        'type' => Types::string(),
                        'description' => 'User who uploaded the photo'
                    ],
                    'description' => [
                        'type' => Types::string(),
                        'description' => 'Description of the photo'
                    ],
                    'timestamp' => [
                        'type' => Types::int(),
                        'description' => 'Timestamp of photo upload'
                    ]
                ];
            },
        ];
        parent::__construct($config);
    }

}
