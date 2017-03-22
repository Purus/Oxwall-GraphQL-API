<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class SiteInfoType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'SiteInfo',
            'description' => 'Information about the social network',
            'fields' => function() {
                return [
                    'url' => [
                        'type' => Type::string(),
                        'resolve' => function() {
                            return OW_URL_HOME;
                        }
                    ],
                    'name' => [
                        'type' => Type::string(),
                        'resolve' => function($entity, $args, $context, ResolveInfo $info) {
                            return $context->config->getValue('base', 'site_name');
                        }
                    ],
                    'description' => [
                        'type' => Type::string(),
                        'resolve' => function($entity, $args, $context, ResolveInfo $info) {
                            return $context->config->getValue('base', 'site_description');
                        }
                    ],
                    'tagline' => [
                        'type' => Type::string(),
                        'resolve' => function($entity, $args, $context, ResolveInfo $info) {
                            return $context->config->getValue('base', 'site_tagline');
                        }
                    ],
                    'email' => [
                        'type' => Type::string(),
                        'resolve' => function($entity, $args, $context, ResolveInfo $info) {
                            return $context->config->getValue('base', 'site_email');
                        }
                    ],
                    'maintenance' => [
                        'type' => Type::boolean(),
                        'resolve' => function($entity, $args, $context, ResolveInfo $info) {
                            return (int) $context->config->getValue('base', 'maintenance') == 1;
                        }
                    ],
                    'currency' => [
                        'type' => Type::string(),
                        'resolve' => function($entity, $args, $context, ResolveInfo $info) {
                            return $context->config->getValue('base', 'billing_currency');
                        }
                    ],                            
                    'version' => [
                        'type' => Type::string(),
                        'resolve' => function($entity, $args, $context, ResolveInfo $info) {
                            return $context->config->getValue('base', 'soft_version');
                        }
                    ],
                    'activePlugins' => [
                        'type' => Type::listOf(new PluginType()),
                        'resolve' => function($entity, $args, $context, ResolveInfo $info) {
                            $plugins = $context->plugin->findActivePlugins();

                            $activePlugins = array();
                            $i = 0;

                            foreach ($plugins as $plugin) {
                                if (!$plugin->isSystem()) {
                                    $activePlugins[$i]['key'] = $plugin->getKey();
                                    $activePlugins[$i]['name'] = $plugin->getTitle();
                                    $i++;
                                }
                            }

                            return $activePlugins;
                        }
                    ]
                ];
            }
        ];
        parent::__construct($config);
    }

}
