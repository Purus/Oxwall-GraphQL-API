<?php

namespace GraphQL\Oxwall\Types\Enum;

use GraphQL\Type\Definition\EnumType;

class BlogListEnum extends EnumType {

    public function __construct() {
        $config = [
            'name' => 'BlogListEnum',
            'values' => [
                'LATEST' => [
                    'value' => 'latest',
                    'description' => 'Latest blog posts'
                ],
                'MOST_DISCUSSED' => [
                    'value' => 'most-discussed',
                    'description' => 'Most discussed blog posts'
                ],
                'TOP_RATED' => [
                    'value' => 'top-rated',
                    'description' => 'Top rated blog posts'
                ]]
        ];
        parent::__construct($config);
    }

}
