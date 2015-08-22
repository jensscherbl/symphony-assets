# Assets

Asset builder for [Symphony][1].

Builds assets on page request.

This extension uses [Composer][5] for autoloading.

Make sure to include the autoload instructions from its `composer.json` in your project's main `composer.json` and run `composer update` before installing this extension.

Source- and target files go into `/workspace/assets/`.

Asset bundles and basic build tasks are defined in `/workspace/assets.php`.

Example `assets.php` is included.

Uses [cat][2] for bundles, so probably doesn't work on Windows. Tools ([Autoprefixer][3], [Uglify][4], etc.) need to be installed separately.

This is for development only and shouldn't be used in production environments.

[1]: http://getsymphony.com/
[2]: http://en.wikipedia.org/wiki/Cat_(Unix)
[3]: http://github.com/postcss/autoprefixer
[4]: http://lisperator.net/uglifyjs
[5]: http://getcomposer.org/
