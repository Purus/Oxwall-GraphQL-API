<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class SiteInfoType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'SiteInfo',
            'description' => 'Information about the social network',
            'fields' => function() {
                return [
                    'url' => [
                        'type' => Types::string(),
                        'description' => 'URL of the social network'
                    ],
                    'name' => [
                        'type' => Types::string(),
                        'description' => 'Description of the social network'
                    ],
                    'description' => [
                        'type' => Types::string(),
                        'description' => 'Tagline of the social network'
                    ],
                    'tagline' => [
                        'type' => Types::string(),
                        'description' => 'Tagline of the social network'
                    ],
                    'email' => [
                        'type' => Types::string(),
                        'description' => 'Email of the social network'
                    ],
                    'maintenance' => [
                        'type' => Types::boolean(),
                        'description' => 'Is website in maintenance mode?'
                    ],
                    'currency' => [
                        'type' => Types::string(),
                        'description' => 'Base billing currency of the social network'
                    ],
                    'version' => [
                        'type' => Types::string(),
                        'description' => 'Oxwall version'
                    ],
                    'activePlugins' => [
                        'type' => Types::listOf(Types::plugin()),
                        'description' => 'List of all active plugins'
                    ],
                    'primaryMenu' => [
                        'type' => Types::listOf(Types::menu()),
                        'description' => 'List of all primay menu items'
                    ],
                    'secondaryMenu' => [
                        'type' => Types::listOf(Types::menu()),
                        'description' => 'List of all secondary menu items'
                    ]                       
                ];
            }
        ];
        parent::__construct($config);
    }

}
