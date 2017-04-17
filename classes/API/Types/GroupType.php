<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class GroupType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'Group',
            'description' => 'Group Information',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::int(),
                        'description' => 'Group Id'
                    ],
                    'user' => [
                        'type' => Types::user(),
                        'description' => 'User who uploaded the photo'
                    ],
                    'title' => [
                        'type' => Types::string(),
                        'description' => 'Group title'
                    ],
                    'description' => [
                        'type' => Types::string(),
                        'description' => 'Description of the group'
                    ],
                    'timestamp' => [
                        'type' => Types::int(),
                        'description' => 'Timestamp of group creation'
                    ],
                    'url' => [
                        'type' => Types::url(),
                        'description' => 'URL of the group'
                    ],
                    'imageSmall' => [
                        'type' => Types::url(),
                        'description' => 'Web Url of the small group image'
                    ],
                    'imageBig' => [
                        'type' => Types::url(),
                        'description' => 'Web Url of the big group image'
                    ],
                    'userCount' => [
                        'type' => Types::int(),
                        'description' => 'Total number of users in the group'
                    ],
                    'members' => [
                        'type' => Types::listOf(Types::user()),
                        'description' => 'Members list of the group'
                    ],
                ];
            },
        ];
        parent::__construct($config);
    }

}
