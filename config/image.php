<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Image Driver
    |--------------------------------------------------------------------------
    |
    | Intervention Image supports "GD Library" and "Imagick" to process images
    | internally. You may choose one of them according to your PHP
    | configuration. By default PHP's "GD Library" implementation is used.
    |
    | Supported: "gd", "imagick"
    |
    */

    'driver' => 'gd',
    'defaultImg' => [
        'max' => ['with' => 1500, 'height' => 1500], //for validate
        'size'=> [
            'original' => ['width' => 0, 'height' => 0],
            'small' => ['width' => 80, 'height' => 0],
            'medium' => ['width' => 250, 'height' => 0],
            'large' => ['width' => 800, 'height' => 0],
        ]
    ],
    'data' => [
        'news' => [
            'size'=> [
                'small' => ['width' => 80, 'height' => 0],
                'medium' => ['width' => 238, 'height' => 0],
                'medium2' => ['width' => 350, 'height' => 0],
                'large' => ['width' => 570, 'height' => 0],
            ]
        ],
        'collection' => [
            'size'=> [
                'small' => ['width' => 80, 'height' => 0],
                'medium' => ['width' => 250, 'height' => 0],
                'mediumx2' => ['width' => 350, 'height' => 0],
                'large' => ['width' => 630, 'height' => 0],
                'largex2' => ['width' => 800, 'height' => 0],
            ]
        ],
        'products' => [
            'size'=> [
                'tiny' => ['width' => 50, 'height' => 0],
                'small' => ['width' => 80, 'height' => 0],
                'medium' => ['width' => 250, 'height' => 0],
                'mediumx2' => ['width' => 350, 'height' => 0],
                'large' => ['width' => 630, 'height' => 0],
                'largex2' => ['width' => 800, 'height' => 0],
            ]
        ],
        'feature' => [
            'dir' => 'feature',
            'max' => ['with' => 1500, 'height' => 1500], //for validate
            'size'=> [
                'original' => ['width' => 0, 'height' => 0],
                'small' => ['width' => 100, 'height' => 0],
                'mediumx2' => ['width' => 350, 'height' => 0],
                'slide_custome' => ['width' => 750, 'height' => 0],
                'slide' => ['width' => 600, 'height' => 400],
                'large' => ['width' => 1300, 'height' => 0],
            ]
        ],
        'config' => [
            'max' => ['with' => 845, 'height' => 845], //for validate
            'size'=> [
                'medium_seo' => ['width' => 250, 'height' => 0],
                'seo' => ['width' => 800, 'height' => 800],
            ]
        ],
        'category' => [],
        'file' => [],
        'avatar' => [
            'max' => ['with' => 500, 'height' => 500], //for validate
            'size'=> [
                'small2' => ['width' => 40, 'height' => 0],
                'large' => ['width' => 200, 'height' => 0]
            ]
        ],
        'gallery' => [
            'dir' => 'gallery',
            'max' => ['with' => 1500, 'height' => 1500], //for validate
            'size'=> [
                'original' => ['width' => 0, 'height' => 0],
                'small' => ['width' => 150, 'height' => 0],
                'slide' => ['width' => 350, 'height' => 0],
                'large' => ['width' => 640, 'height' => 0],
            ]
        ],
        'customergroup' => [
            'size'=> [
                'original' => ['width' => 0, 'height' => 0],
                'tiny' => ['width' => 13, 'height' => 0],
                'small' => ['width' => 45, 'height' => 0]
            ]
        ],
        'avatar_supports' => [
            'size'=> [
                'original' => ['width' => 0, 'height' => 0],
                'tiny' => ['width' => 13, 'height' => 0],
                'small' => ['width' => 45, 'height' => 0]
            ]
        ],
        'filters' => [
            'size'=> [
                'original' => ['width' => 0, 'height' => 0],
                'tiny' => ['width' => 13, 'height' => 0],
                'small' => ['width' => 45, 'height' => 0]
            ]
        ],
//        'installment_bank' => [
//            'size'=> [
//                'original' => ['width' => 0, 'height' => 0],
//                'tiny' => ['width' => 143, 'height' => 0],
//                'small' => ['width' => 200, 'height' => 0],
//
//            ]
//        ],
    ]

];
