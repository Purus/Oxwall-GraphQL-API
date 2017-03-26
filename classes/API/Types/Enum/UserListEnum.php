<?php

namespace GraphQL\Oxwall\Types\Enum;

use GraphQL\Type\Definition\EnumType;

class UserListEnum extends EnumType {

    public function __construct() {
        $config = [
            'name' => 'UserListEnum',
            'values' => [
                'LATEST' => [
                    'value' => 'latest',
                    'description' => 'Latest users'
                ],
                'ONLINE' => [
                    'value' => 'online',
                    'description' => 'Online users'
                ],
                'FEATURED' => [
                    'value' => 'featured',
                    'description' => 'Featured users'
                ],
                'PENDING' => [
                    'value' => 'waiting-for-approval',
                    'description' => 'Users pending for admin approval'
                ]]
        ];
        parent::__construct($config);
    }

}
