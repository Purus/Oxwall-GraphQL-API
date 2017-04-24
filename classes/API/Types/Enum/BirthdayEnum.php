<?php

namespace GraphQL\Oxwall\Types\Enum;

use GraphQL\Type\Definition\EnumType;

class BirthdayEnum extends EnumType {

    public function __construct() {
        $config = [
            'name' => 'BirthdayEnum',
            'values' => [
                'TODAY' => [
                    'value' => 'today',
                    'description' => 'Get users with birthday falling today'
                ],
                'WEEK' => [
                    'value' => 'week',
                    'description' => 'Get users with birthday falling current week'
                ]]
        ];
        parent::__construct($config);
    }

}
