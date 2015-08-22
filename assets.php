<?php

// /workspace/assets.php

$this->add('styles/bundle.css', function ($asset) {

    $asset->files = [

        'styles/src/reset.css',
        'styles/src/fonts.css',
        'styles/src/base.css',
        'styles/src/header.css',
        'styles/src/main.css',
        'styles/src/footer.css'
    ];

    $asset->tasks = [

        '/usr/local/bin/node node_modules/.bin/autoprefixer',
        '/usr/local/bin/node node_modules/.bin/cssmin'
    ];

    return $asset;
});

$this->add('scripts/bundle.js', function ($asset) {

    $asset->files = [

        'scripts/src/lib.js',
        'scripts/src/lib_retina.js',
        'scripts/src/app.js',
        'scripts/src/app_slider.js',
        'scripts/src/app_init.js'
    ];

    $asset->tasks = [

        '/usr/local/bin/node node_modules/.bin/uglifyjs -mc'
    ];

    return $asset;
});
