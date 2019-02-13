const AssetPlugin = require("assets-webpack-plugin")

module.exports = {
  chainWebpack: config => {
    config
      .plugin("html")
      .tap(args => {
        args[0].templateContent = `<div id="app"></div>`
        //args[0].inject = "body"
        return args
      })

    config
      .plugin("assets")
      .use(AssetPlugin, [{
        filename: "assets.json",
        fullPath: false,
        manifestFirst: true,
        useCompilerPath: true,
        update: true,
        prettyPrint: true,
        keepInMemory: true
      }])
  }
}
