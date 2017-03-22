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
                    'id' => Type::nonNull(Type::id()),
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
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                if (method_exists($this, $info->fieldName)) {
                    return $this->{$info->fieldName}($value, $args, $context, $info);
                } else {
                    return $value->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }

}
