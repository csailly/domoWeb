'use strict';

var gulp = require('gulp');

var $ = require('gulp-load-plugins')();

var wiredep = require('wiredep').stream;

module.exports = function (options) {

  var scripts = [
    options.src + '/app/app.js',
    options.src + '/app/**/*.js',
    options.src + '/partials/*.js',
    '!' + options.src + '/app/**/*.spec.js',
    '!' + options.src + '/app/**/*.mock.js'
  ];

  function runInject() {
    var injectScripts = gulp.src(scripts).pipe($.angularFilesort());
    var injectStyles = gulp.src([
      options.tmp + '/serve/app/**/*-cssfont.css',
      options.tmp + '/serve/app/**/*.css',
      options.tmp + '/serve/app/vendor.css'
    ], {read: false});

    var injectOptions = {
      ignorePath: [options.src, options.tmp + '/serve'],
      addRootSlash: false
    };

    return gulp.src(options.src + '/*.html')
      .pipe($.inject(injectStyles, injectOptions))
      .pipe($.inject(injectScripts, injectOptions))
      .pipe(wiredep(options.wiredep))
      .pipe(gulp.dest(options.tmp + '/serve'));
  }

  gulp.task('inject:dist', function () {
    // FIXME: uncomment below code in order to avoid to embed the main js demo files
    // ignore main js files, which are used in dev mode
    //scripts.push('!' + options.src + '/app/main/*.js');

    return runInject();
  });

  gulp.task('inject', ['scripts', 'styles'], function () {
    return runInject();
  });
};
