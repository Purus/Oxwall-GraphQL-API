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
                        'type' => Types::int(),
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
                    ],
                    'cover' => [
                        'type' => Types::string(),
                        'description' => 'Cover image of the album'
                    ],
                    'user' => [
                        'type' => Types::user(),
                        'description' => 'Owner of the album'
                    ],
                    'photosCount' => [
                        'type' => Types::int(),
                        'description' => 'Total images in the album'
                    ],
                    'photo' => [
                        'type' => Types::listOf(Types::photo()),
                        'description' => 'Photos of the album'
                    ]
                ];
            },
        ];
        parent::__construct($config);
    }

}
