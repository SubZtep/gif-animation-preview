require("dotenv").config()

nightwatch_config = {
  src_folders: ["tests/e2e/specs"],

  selenium : {
    "start_process": false,
    "host": "hub-cloud.browserstack.com",
    "port": 80
  },

  test_settings: {
    default: {
      desiredCapabilities: {
        "browserstack.user": process.env.BROWSERSTACK_USERNAME,
        "browserstack.key": process.env.BROWSERSTACK_ACCESS_KEY,
        "browser": "chrome"
      }
    }
  }
}

// Code to copy seleniumhost/port into test settings
for (let i in nightwatch_config.test_settings) {
  var config = nightwatch_config.test_settings[i]
  config["selenium_host"] = nightwatch_config.selenium.host
  config["selenium_port"] = nightwatch_config.selenium.port
}

module.exports = nightwatch_config
