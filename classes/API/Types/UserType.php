<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class UserType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'User',
            'description' => 'Social network users',
            'fields' => function() {
                return [
                    'id' => Types::id(),
                    'email' => Types::string(),
                    'avatar' => [
                        'type' => Types::string(),
                        'description' => 'User profile avatar image URL'
                    ],
                    'userName' => Types::string(),
                    'joinStamp' => Types::int(),
                    'activityStamp' => Types::int(),
                    'emailVerify' => Types::boolean(),
                    'joinIp' => Types::string(),
                    'url' => Types::string(),
                    'title' => Types::string(),
                     'online' => Types::boolean()
                ];
            }
        ];
        parent::__construct($config);
    }

}
