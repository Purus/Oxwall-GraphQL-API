<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class BlogType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Blog',
            'description' => 'Blog posts',
            'fields' => function() {
                return [
                    'id' => Type::id(),
                    'user' => new UserType(),
                    'title' => Type::string(),
                    'post' => Type::string(),
                    'timestamp' => Type::string(),
                    'isDraft' => Type::boolean(),
                    'privacy' => Type::string(),
                ];
            },
            'resolve' => function($value, $args, $context, ResolveInfo $info) {
            }
        ];
        parent::__construct($config);
    }
}
