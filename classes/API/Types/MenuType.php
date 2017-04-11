<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class MenuType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'Menu',
            'description' => 'Details about the menu',
            'fields' => function() {
                return [
                    'prefix' => Types::string(),
                    'key' => Types::string()
                ];
            },
        ];
        parent::__construct($config);
    }

}
