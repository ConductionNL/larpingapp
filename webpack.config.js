const path = require("path");
const webpackConfig = require("@nextcloud/webpack-vue-config");

const buildMode = process.env.NODE_ENV;
const isDev = buildMode === "development";
webpackConfig.devtool = isDev ? "cheap-source-map" : "source-map";

webpackConfig.stats = {
  colors: true,
  modules: false,
};

const appId = "larpingapp";
webpackConfig.entry = {
  main: {
    import: path.join(__dirname, "src", "mainScript.js"),
    filename: appId + "-mainScript.js",
  }
};

module.exports = webpackConfig;
