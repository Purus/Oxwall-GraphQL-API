<?php

namespace GraphQL\Oxwall\Types;

use GraphQL\Oxwall\Types;
use GraphQL\Type\Definition\ObjectType;

class EventType extends ObjectType {

    public function __construct() {
        $config = [
            'name' => 'Event',
            'description' => 'Event Information',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::int(),
                        'description' => 'Event Id'
                    ],
                    'user' => [
                        'type' => Types::user(),
                        'description' => 'User who created event'
                    ],
                    'title' => [
                        'type' => Types::string(),
                        'description' => 'Event title'
                    ],
                    'description' => [
                        'type' => Types::string(),
                        'description' => 'Description of the event'
                    ],
                    'createTimestamp' => [
                        'type' => Types::int(),
                        'description' => 'Timestamp of event creation'
                    ],
                    'startTimestamp' => [
                        'type' => Types::int(),
                        'description' => 'Start timestamp of event'
                    ],
                    'endTimestamp' => [
                        'type' => Types::int(),
                        'description' => 'End timestamp of event'
                    ], 
                    'startTimeDisable' => [
                        'type' => Types::boolean(),
                        'description' => 'Start time disabled'
                    ],
                    'endTimeDisable' => [
                        'type' => Types::boolean(),
                        'description' => 'End time disabled?'
                    ], 
                    'location' => [
                        'type' => Types::string(),
                        'description' => 'Event location'
                    ],                                                                               
                    'url' => [
                        'type' => Types::url(),
                        'description' => 'URL of the event'
                    ],
                    'image' => [
                        'type' => Types::url(),
                        'description' => 'Web Url of the event image'
                    ],
                    'members' => [
                        'type' => Types::listOf(Types::user()),
                        'description' => 'Members list of the event'
                    ],
                ];
            },
        ];
        parent::__construct($config);
    }

}
