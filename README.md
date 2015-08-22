# Assets

Asset builder for [Symphony][1].

Rebuild and store assets on page request if something has changed.

*Uses [cat][2] for bundles, so probably doesn't work on Windows. Tools ([Autoprefixer][3], [Uglify][4], etc.) need to be installed separately.*

*This is for development only and shouldn't be used in production environments.*

This extension uses [Composer][7] for autoloading.

Make sure to include the autoload instructions from its `composer.json` in your project's main `composer.json` and run `composer update` before installing this extension.

## Configuration

Asset bundles and basic build tasks are defined in `/workspace/assets.php`.

It looks like this...

    <?php

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

Your source- and target files go into `/workspace/assets/`.

**/workspace/assets/styles/bundle.css**
- /workspace/assets/styles/src/reset.css
- /workspace/assets/styles/src/fonts.css
- /workspace/assets/styles/src/...

**/workspace/assets/scripts/bundle.js**
- /workspace/assets/scripts/src/lib.js
- /workspace/assets/scripts/src/lib_retina.js
- /workspace/assets/scripts/src/...

This is just an example and can easily be adapted for different structures or additional tools like [TypeScript][5] and [SASS][6].

[1]: http://getsymphony.com/
[2]: http://en.wikipedia.org/wiki/Cat_(Unix)
[3]: http://github.com/postcss/autoprefixer
[4]: http://lisperator.net/uglifyjs
[5]: http://typescriptlang.org/
[6]: http://sass-lang.com/
[7]: http://getcomposer.org/
