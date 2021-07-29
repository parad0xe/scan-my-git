// eslint-disable-next-line @typescript-eslint/no-var-requires
const Encore = require("@symfony/webpack-encore");
const SpeedMeasurePlugin = require("speed-measure-webpack-plugin");

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
  Encore.configureRuntimeEnvironment(process.env.NODE_ENV || "dev");
}

// uncomment to get integrity="..." attributes on your script & link tags
// requires WebpackEncoreBundle 1.4 or higher
//.enableIntegrityHashes(Encore.isProduction())

Encore
  // directory where compiled assets will be stored
  .setOutputPath("public/build/")
  // public path used by the web server to access the output path
  .setPublicPath("/build")
  // only needed for CDN's or sub-directory deploy
  //.setManifestKeyPrefix('build/')
  .cleanupOutputBeforeBuild()

  .copyFiles({
    from: "./assets/img",

    // if versioning is enabled, add the file hash too
    to: "images/[path][name].[hash:8].[ext]",

    // only copy files matching this pattern
    pattern: /\.(png|jpg|jpeg)$/,
  })

  /*
   * ENTRY CONFIG
   *
   * Each entry will result in one JavaScript file (e.g. app.js)
   * and one CSS file (e.g. app.css) if your JavaScript imports CSS.
   */
  .addEntry("app", "./assets/app.tsx")

  // enables the Symfony UX Stimulus bridge (used in assets/bootstrap.js)
  .enableStimulusBridge("./assets/controllers.json")

  // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
  .splitEntryChunks()

  // will require an extra script tag for runtime.js
  // but, you probably want this, unless you're building a single-page app
  .enableSingleRuntimeChunk()

  /*
   * FEATURE CONFIG
   *
   * Enable & configure other features below. For a full
   * list of features, see:
   * https://symfony.com/doc/current/frontend.html#adding-more-features
   */
  .enableBuildNotifications()

  .configureBabel((config) => {
    config.plugins.push("@babel/plugin-proposal-class-properties");
  })

  // enables @babel/preset-env polyfills
  .configureBabelPresetEnv((config) => {
    config.useBuiltIns = "usage";
    config.corejs = 3;
  })

  // enables Sass/SCSS support
  // .enableSassLoader(function(options) {}, { resolveUrlLoader: false })
  .enablePostCssLoader()

  // uncomment if you use TypeScript
  .enableTypeScriptLoader()
  .enableForkedTypeScriptTypesChecking()

  // uncomment if you use React
  .enableReactPreset()

  .enableSourceMaps(!Encore.isProduction())
  // enables hashed filenames (e.g. app.abc123.css)
  .enableVersioning(Encore.isProduction());

// uncomment if you're having problems with a jQuery plugin
//.autoProvidejQuery()

Encore.configureWatchOptions(function (watchOptions) {
  watchOptions.poll = 250;
  watchOptions.ignored = /node_modules/;
});

let webpack_config = Encore.getWebpackConfig();

webpack_config.module.rules[0].exclude =
  /node_modules\/(?!(autotrack|dom-utils))/;

// webpack_config = (new SpeedMeasurePlugin()).wrap(webpack_config)

module.exports = webpack_config;
