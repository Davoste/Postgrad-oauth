<?php

return [
    // These are the install's factory defaults, used the first time the
    // portal boots (before an admin has saved anything in Admin > Branding)
    // and as a fallback if a setting is ever missing. Changing brand.php
    // does NOT change what's already saved in the `settings` table — use
    // the Branding admin page (or `php artisan brand:sync`) for that.
    'name'           => env('BRAND_NAME', 'JOOUST Postgraduate'),
    'legal_name'     => env('BRAND_LEGAL_NAME', 'Jaramogi Oginga Odinga University of Science and Technology'),
    'short_name'     => env('BRAND_SHORT_NAME', 'JOOUST'),
    'tagline'        => env('BRAND_TAGLINE', 'Thesis Supervision Portal'),
    'logo_path'      => env('BRAND_LOGO_PATH', 'assets/img/JOOUSTLOGO1.png'),
    'hero_image_path'=> env('BRAND_HERO_IMAGE_PATH', null), // null = use the CSS gradient
    'favicon_emoji'  => env('BRAND_FAVICON_EMOJI', '🎓'),
    'primary_color'  => env('BRAND_PRIMARY_COLOR', '#1a3c6e'),
    'accent_color'   => env('BRAND_ACCENT_COLOR', '#0000FF'),
    'gold_color'     => env('BRAND_GOLD_COLOR', '#CCCC33'),
    'support_email'  => env('BRAND_SUPPORT_EMAIL', 'bps@jooust.ac.ke'),
    'support_phone'  => env('BRAND_SUPPORT_PHONE', '057-2501804'),
    'website'        => env('BRAND_WEBSITE', 'www.jooust.ac.ke'),
    'address'        => env('BRAND_ADDRESS', 'P.O. Box 210-40601, Bondo, Siaya County, Kenya'),
    'footer_text'    => env('BRAND_FOOTER_TEXT', 'JOOUST Postgraduate Portal. All rights reserved.'),
];
