<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class PluginType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'Plugin',
            'description' => 'Details about the social network',
            'fields' => function() {
                return [
                    'name' => Type::string(),
                    'key' => Type::string()
                ];
            },
        ];
        parent::__construct($config);
    }

}
