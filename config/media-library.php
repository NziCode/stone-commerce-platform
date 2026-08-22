<?php

return [

    /*
     * This host has proc_open/proc_get_status disabled (common on shared
     * cPanel hosting), which the default image optimizer chain needs to
     * shell out to jpegoptim/pngquant/etc. Disabling it here prevents a
     * fatal error on every media upload across the whole app. Per-model
     * ->nonOptimized() calls (see app/Models/Product.php) do the same for
     * conversions defined explicitly; this covers everything else.
     */
    'image_optimizers' => [],

];
