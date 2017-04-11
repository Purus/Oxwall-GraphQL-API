<?php
namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class NewsfeedType extends ObjectType {
    public function __construct() {
        $config = [
            'name' => 'Newsfeed',
            'description' => 'Newesfeed Details',
            'fields' => function() {
                return [
                    'id' => Types::int(),
                    'content' => Types::string(),
                    'activityStamp' => Types::int(),
                    'feedUrl' => Types::url(),
                    'user' => Types::user(),
                    'likesCount' => Types::int(),
                    'commentsCount' => Types::int()
                ];
            }
        ];
        parent::__construct($config);
    }
}
