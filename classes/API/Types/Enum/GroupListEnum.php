<?php

namespace GraphQL\Oxwall\Types\Enum;

use GraphQL\Type\Definition\EnumType;

class GroupListEnum extends EnumType {

    public function __construct() {
        $config = [
            'name' => 'GroupListEnum',
            'values' => [
                'POPULAR' => [
                    'value' => 'most_popular',
                    'description' => 'Popular Groups'
                ],
                'LATEST' => [
                    'value' => 'latest',
                    'description' => 'Latest Groups'
                ],
                'ALL' => [
                    'value' => 'all',
                    'description' => 'All Groups'
                ]]
        ];
        parent::__construct($config);
    }

}
