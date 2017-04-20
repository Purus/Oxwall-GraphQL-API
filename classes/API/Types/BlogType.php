<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class BlogType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'Blog',
            'description' => 'Blog posts',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::id(),
                        'description' => 'Blog Id'
                    ],
                    'user' => [
                        'type' => Types::user(),
                        'description' => 'User who crated the blog post'
                    ],
                    'title' => [
                        'type' => Types::string(),
                        'description' => 'Title of the blog post'
                    ],
                    'post' => [
                        'type' => Types::string(),
                        'description' => 'Content of the blog post'
                    ],
                    'summary' => [
                        'type' => Types::string(),
                        'description' => 'Summary content of the blog post'
                    ],                    
                    'timestamp' => [
                        'type' => Types::int(),
                        'description' => 'Blog post creation timestamp'
                    ],
                    'isDraft' => [
                        'type' => Types::boolean(),
                        'description' => 'Is the blog post in draft status?'
                    ],
                    'privacy' => [
                        'type' => Types::string(),
                        'description' => 'Privacy of the blog post'
                    ],
                    'url' => [
                        'type' => Types::url(),
                        'description' => 'Web Url of the blog post'
                    ]
                ];
            },
        ];
        parent::__construct($config);
    }

}
