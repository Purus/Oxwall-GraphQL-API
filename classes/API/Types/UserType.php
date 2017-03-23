<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class UserType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'User',
            'description' => 'Social network users',
            'fields' => function() {
                return [
                    'id' => Type::id(),
                    'email' => Type::string(),
                    'photo' => [
                        'type' => Type::string(),
                        'description' => 'User profile photo URL'
                    ],
                    'userName' => Type::string(),
                    'joinStamp' => Type::string(),
                    'activityStamp' => Type::string(),
                    'emailVerify' => Type::boolean(),
                    'joinIp' => Type::string(),
                ];
            },
            'resolve' => function($value, $args, $context, ResolveInfo $info) {
                $users = $context->users->findList(0, 50);
                $allUsers = array();
                $i = 0;
                foreach ($users as $user) {
                    $allUsers[$i]['id'] = $user->id;
                    $i++;
                }

                return $allUsers;
            }
        ];
        parent::__construct($config);
    }

}
