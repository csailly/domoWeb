'use strict';

module.exports = function(config) {

  var configuration = {
    autoWatch : false,

    frameworks: ['jasmine'],

    browsers : ['PhantomJS'],

    plugins : [
      'karma-phantomjs-launcher',
      'karma-jasmine',
      'karma-ng-html2js-preprocessor',
      'karma-coverage'
    ],

    reporters: ['progress', 'coverage'],

    ngHtml2JsPreprocessor: {
      stripPrefix: 'src/',
      moduleName: 'ds.templates'
    },

    preprocessors: {
      'src/app/**/*.html': ['ng-html2js'],

      // source files, that you wanna generate coverage for
      // do not include tests or libraries
      // (these files will be instrumented by Istanbul)
      'src/app/**/!(*spec|*mock).js': ['coverage']
    },

    coverageReporter: {
      type: 'lcov',
      subdir: function(browser) {
        // normalization process to keep a consistent browser name accross different OS
        return browser.toLowerCase().split(/[ /-]/)[0];
      },
      dir: 'coverage'
    }
  };

  // This block is needed to execute Chrome on Travis
  // If you ever plan to use Chrome and Travis, you can keep it
  // If not, you can safely remove it
  // https://github.com/karma-runner/karma/issues/1144#issuecomment-53633076
  if(configuration.browsers[0] === 'Chrome' && process.env.TRAVIS) {
    configuration.customLaunchers = {
      'chrome-travis-ci': {
        base: 'Chrome',
        flags: ['--no-sandbox']
      }
    };
    configuration.browsers = ['chrome-travis-ci'];
  }

  config.set(configuration);
};
