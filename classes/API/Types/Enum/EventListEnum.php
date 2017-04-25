<?php

namespace GraphQL\Oxwall\Types\Enum;

use GraphQL\Type\Definition\EnumType;

class EventListEnum extends EnumType {

    public function __construct() {
        $config = [
            'name' => 'EventListEnum',
            'values' => [
                'PAST' => [
                    'value' => 'past',
                    'description' => 'Past Events'
                ],
                'LATEST' => [
                    'value' => 'latest',
                    'description' => 'Latest Events'
                ]]
        ];
        parent::__construct($config);
    }

}
