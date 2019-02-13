module.exports = {

  //publicPath: "aaa/bbb", // wp install

  chainWebpack: config => {
    config
      .plugin("html")
      .tap(args => {
        args[0].templateContent = `<div id="app"></div>`
        //args[0].inject = "body"
        return args
      })
  }
}
