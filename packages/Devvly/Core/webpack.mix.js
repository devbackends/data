const { mix } = require("laravel-mix");
require("laravel-mix-merge-manifest");
var publicPath = "../../../public/themes/default/assets";
if (mix.inProduction()) {
    publicPath = 'publishable/assets';
}

mix.setPublicPath(publicPath).mergeManifest();
mix.disableNotifications();

mix.js([__dirname + "/src/Resources/assets/js/app.js"], "js/core.js")
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();
}
if (!mix.inProduction()) {
    mix.sourceMaps();
}