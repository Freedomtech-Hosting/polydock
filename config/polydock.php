<?php

return [
    'ui' => [
        'theme' => [
            'logoSquare' => env('POLYDOCK_UI_THEME_LOGO_SQUARE', '/polydock-assets/theme/aio/aio-square.png'),
        ],
    ],
    'engines' => [
        'lagoonProjectPrefix' => env('POLYDOC_PROJECT_PREFIX', 'polydocdev'),
        'LightningNode' => [
            'lagoonProjectPrefix' => env('POLYDOC_PROJECT_PREFIX_LIGHTNING', env('POLYDOC_PROJECT_PREFIX', 'polydocdev-ln')),
        ],
        'NostryRelay' => [
            'lagoonProjectPrefix' => env('POLYDOC_PROJECT_PREFIX_NOSTR', env('POLYDOC_PROJECT_PREFIX', 'polydocdev-nr')),
        ],
        'Fedimint' => [
            'lagoonProjectPrefix' => env('POLYDOC_PROJECT_PREFIX_FEDIMINT', env('POLYDOC_PROJECT_PREFIX', 'polydocdev-fm')),
        ],
        'D10Demo' => [
            'lagoonProjectPrefix' => env('POLYDOC_PROJECT_PREFIX_D10DEMO', env('POLYDOC_PROJECT_PREFIX', 'polydocdev-d10demo')),
        ],
    ]
];
