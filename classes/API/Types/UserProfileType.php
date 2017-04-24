<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class UserProfileType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'UserProfile',
            'description' => 'Profile details of an user',
            'fields' => function() {
                return [
                    'section' => [
                        'type' => Types::string(),
                        'description' => 'Profile Question Section'
                    ],
                    'question' => [
                        'type' => Types::string(),
                        'description' => 'Profile Question'
                    ],
                    'value' => [
                        'type' => Types::string(),
                        'description' => 'Profile Question Value'
                    ],
                    'name' => [
                        'type' => Types::string(),
                        'description' => 'Profile Question Name'
                    ]					
                ];
            },
        ];
        parent::__construct($config);
    }

}
