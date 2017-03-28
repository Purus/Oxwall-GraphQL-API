<?php

namespace GraphQL\Oxwall\Types\Enum;

use GraphQL\Type\Definition\EnumType;

class PhotoListEnum extends EnumType {

    public function __construct() {
        $config = [
            'name' => 'PhotoListEnum',
            'values' => [
                'LATEST' => [
                    'value' => 'latest',
                    'description' => 'Latest photos'
                ],
                'FEATURED' => [
                    'value' => 'featured',
                    'description' => 'Featured photos'
                ],                
                'MOST_DISCUSSED' => [
                    'value' => 'most_discussed',
                    'description' => 'Most discussed photos'
                ],
                'TOP_RATED' => [
                    'value' => 'toprated',
                    'description' => 'Top rated photos'
                ]]
        ];
        parent::__construct($config);
    }

}
