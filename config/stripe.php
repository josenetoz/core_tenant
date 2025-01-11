<?php

declare(strict_types=1);



return [
    'trial_days' => 7,

    'allow_promotion_codes' => true,

    'billed_periods' => [
        'monthly' => 'Mensal',
        'yearly' => 'Anual',
    ],

    'plans' => [
        'default' => [
            'type' => 'default',
            'name' => 'Standard',
            'short_description' => 'Assinatura Sistema',
            'product_id' => 'prod_RWCCkVcOSdx4Bk',
            'prices' => [
                'monthly' => [
                    'period' => 'monthly',
                    'periodo' => 'Mês',
                    'id' => 'price_1Qd9nrL3qJV3bURYu4fuEVOx',
                    'price' => 10000,
                    
                ],
                'yearly' => [
                    'period' => 'yearly',
                    'periodo' => 'Mês',
                    'id' => 'price_1Qd9siL3qJV3bURYx1Ne2Fzw',
                    'price' => 89900,
                ],
            ],
            'features' => [
                'Grow fans and followers on multiple social platforms',
                'Promote more content with email automation',
                'Showcase your content to thousands of fans in New Finds',
            ],
        ],
    ],
];