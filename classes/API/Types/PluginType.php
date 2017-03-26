<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class PluginType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'Plugin',
            'description' => 'Details about the social network',
            'fields' => function() {
                return [
                    'name' => Types::string(),
                    'key' => Types::string()
                ];
            },
        ];
        parent::__construct($config);
    }

}
