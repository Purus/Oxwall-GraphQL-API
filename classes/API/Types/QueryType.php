<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\AppContext;
use GraphQL\Oxwall\Types\UserType;
use GraphQL\Oxwall\Types\SiteInfoType;
use GraphQL\Oxwall\Data\DataSource;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;

class QueryType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'Query',
            'fields' => [
                'site' => [
                    'type' => new SiteInfoType(),
                    'description' => 'Returns all detail about the social network'
                ],
                'user' => [
                    'type' => Type::listOf(new UserType()),
                    'description' => 'Returns all users or by id',
                ],
                'hello' => Type::string(),
            ],
            'resolveField' => function($val, $args, $context, ResolveInfo $info) {
                return $info;
            }
        ];
        parent::__construct($config);
    }

    public function user($rootValue, $args) {
        return DataSource::findUser($args['id']);
    }

    public function viewer($rootValue, $args, AppContext $context) {
        return $context->viewer;
    }

    public function stories($rootValue, $args) {
        $args += ['after' => null];
        return DataSource::findStories($args['limit'], $args['after']);
    }

    public function lastStoryPosted() {
        return DataSource::findLatestStory();
    }

    public function hello() {
        return 'Your graphql-php endpoint is ready! Use GraphiQL to browse API';
    }

    public function deprecatedField() {
        return 'You can request deprecated field, but it is not displayed in auto-generated documentation by default.';
    }

}
