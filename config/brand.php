<?php

/*
|--------------------------------------------------------------------------
| Lahore Fan brand / chrome configuration
|--------------------------------------------------------------------------
|
| Central place for the parent-site URLs, contact details, and navigation
| shown in the header/footer of this outlets microsite. Everything mirrors
| lahorefan.com so the page feels native to the main site. Update these
| values if the WordPress site's menu or contact info changes.
|
*/

return [
    'site_url' => 'https://lahorefan.com',

    'tagline' => "Pakistan's 1st Designer Fan",

    'phone' => [
        'display' => '+92 304 1116600',
        'tel' => '+923041116600',
    ],

    'email' => 'lahorefans@gmail.com',

    'address' => [
        'LAHORE FANS, Kotli pir-ahmad,',
        'NH 5, Kotli Pir Ahmed Shah,',
        'Gujranwala, Punjab',
    ],
    'address_map_url' => 'https://maps.app.goo.gl/8D1CdDeLq3QpgTVdA',

    'social' => [
        'facebook' => 'https://www.facebook.com/LahoreFanOfficial/',
        'instagram' => 'https://www.instagram.com/lahorefanofficial/',
        'youtube' => 'https://www.youtube.com/@lahorefan2336',
    ],

    'catalogue_url' => 'https://lahorefan.com/wp-content/uploads/2025/02/LAHORE-FAN-PDF-2025.pdf',
    'ravian_url' => 'https://ravianfan.com/',

    // Primary navigation — mirrors the lahorefan.com header menu exactly.
    // A "children" key renders a dropdown (the Shop mega-menu).
    'nav' => [
        ['label' => 'Home', 'url' => 'https://lahorefan.com/'],
        ['label' => 'Shop', 'url' => '#', 'children' => [
            ['label' => 'CEILING FANS', 'url' => 'https://lahorefan.com/ceiling-fans/'],
            ['label' => 'BRACKET FANS', 'url' => 'https://lahorefan.com/bracket-fans/'],
            ['label' => 'PEDESTAL FAN', 'url' => 'https://lahorefan.com/product-category/pedestal-fan/'],
            ['label' => 'EXHAUST FAN', 'url' => 'https://lahorefan.com/exhaust-fan/'],
        ]],
        ['label' => 'About', 'url' => 'https://lahorefan.com/about/'],
        ['label' => 'Contact', 'url' => 'https://lahorefan.com/contact/'],
        ['label' => 'Blog', 'url' => 'https://lahorefan.com/blog/'],
        ['label' => 'FAQs', 'url' => 'https://lahorefan.com/faqs/'],
        ['label' => 'Hybrid Econo Power', 'url' => 'https://lahorefan.com/product-category/ceiling-fan/hybrid-econo-power/'],
        ['label' => 'Conventional Motor', 'url' => 'https://lahorefan.com/product-category/ceiling-fan/conventional-motor/'],
    ],

    // Footer link columns (exact order from the live footer).
    'footer_links' => [
        'Useful Links' => [
            ['label' => 'Home', 'url' => 'https://lahorefan.com/'],
            ['label' => 'Shop', 'url' => 'https://lahorefan.com/shop/'],
            ['label' => 'About', 'url' => 'https://lahorefan.com/about/'],
            ['label' => 'Contact', 'url' => 'https://lahorefan.com/contact/'],
        ],
        'Categories' => [
            ['label' => 'Bracket Fan', 'url' => 'https://lahorefan.com/product-category/bracket-fan/'],
            ['label' => 'Ceiling Fan', 'url' => 'https://lahorefan.com/product-category/ceiling-fan/'],
            ['label' => 'Exhaust Fan', 'url' => 'https://lahorefan.com/product-category/exhaust-fan/'],
            ['label' => 'Pedestal Fan', 'url' => 'https://lahorefan.com/product-category/pedestal-fan/'],
        ],
    ],

    'developer' => ['label' => 'Rex Technologies', 'url' => 'https://www.rextech.pk/'],
];
