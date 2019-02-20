module.exports = {
  "Gapplayer Basic Testing": browser => {
    browser
      .url("http://localhost/wordpress/wp-content/plugins/gif-animation-preview/gapplayer/test.html")
      .waitForElementVisible("body", 1000)
      .assert.elementPresent(".gapplayer", "GAP Player works")
      .end()
  }
}
