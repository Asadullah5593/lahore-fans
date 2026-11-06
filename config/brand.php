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

    // Shown as plain text in the footer, exactly as on lahorefan.com (the live
    // footer address is not a link).
    'address' => 'LAHORE FANS, NH 5, Kotli Pir Ahmed Shah, Gujranwala, Punjab',
    // Google Maps pin used by the mobile drawer's "Location" link.
    'address_map_url' => 'https://maps.app.goo.gl/8D1CdDeLq3QpgTVdA',

    'social' => [
        'facebook' => 'https://www.facebook.com/LahoreFanOfficial/?_rdc=2&_rdr',
        'instagram' => 'https://www.instagram.com/lahorefanofficial/',
        'youtube' => 'https://www.youtube.com/@lahorefan2336',
    ],

    'ravian_url' => 'https://ravianfan.com/',

    // Primary navigation — URLs mirror the lahorefan.com header menu.
    // A "children" key renders a dropdown (the Shop menu); the Shop item itself
    // also links through to the shop page, as on the live site.
    'nav' => [
        ['label' => 'Home', 'url' => 'https://lahorefan.com/'],
        ['label' => 'Shop', 'url' => 'https://lahorefan.com/shop/', 'children' => [
            ['label' => 'Ceiling Fans', 'url' => 'https://lahorefan.com/ceiling-fans/'],
            ['label' => 'Bracket Fan', 'url' => 'https://lahorefan.com/bracket-fan/'],
            ['label' => 'Exhaust Fan', 'url' => 'https://lahorefan.com/exhaust-fan/'],
            ['label' => 'Pedestal Fan', 'url' => 'https://lahorefan.com/product-category/pedestal-fan/'],
        ]],
        ['label' => 'About', 'url' => 'https://lahorefan.com/about/'],
        ['label' => 'Contact', 'url' => 'https://lahorefan.com/contact/'],
        ['label' => 'Blog', 'url' => 'https://lahorefan.com/blog/'],
        ['label' => 'FAQs', 'url' => 'https://lahorefan.com/faqs/'],
        ['label' => 'Hybrid Econo Power', 'url' => 'https://lahorefan.com/product-category/ceiling-fans/hybrid-econo-power/'],
        ['label' => 'Conventional Motor', 'url' => 'https://lahorefan.com/product-category/ceiling-fans/conventional-motor/'],
    ],

    // Footer link columns (exact labels, URLs and order from the live footer).
    'footer_links' => [
        'Useful Links' => [
            ['label' => 'Home', 'url' => 'https://lahorefan.com/'],
            ['label' => 'About', 'url' => 'https://lahorefan.com/about/'],
            ['label' => 'Catalogue', 'url' => 'https://lahorefan.com/wp-content/uploads/2026/09/LAHORE-FAN-PDF-2025-1.pdf'],
            ['label' => 'Shop', 'url' => 'https://lahorefan.com/shop/'],
            ['label' => 'Contact', 'url' => 'https://lahorefan.com/contact/'],
        ],
        'Categories' => [
            ['label' => 'Ceiling Fan', 'url' => 'https://lahorefan.com/ceiling-fans/'],
            ['label' => 'Bracket Fan', 'url' => 'https://lahorefan.com/bracket-fan/'],
            ['label' => 'Exhaust Fan', 'url' => 'https://lahorefan.com/exhaust-fan/'],
            ['label' => 'Pedestal Fans', 'url' => 'https://lahorefan.com/product-category/pedestal-fan/'],
        ],
    ],

    'developer' => ['label' => 'Rex Technologies', 'url' => 'https://www.rextech.pk/'],
];
