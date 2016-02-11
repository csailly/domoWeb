'use strict';

var gulp = require('gulp');

var $ = require('gulp-load-plugins')({
  pattern: ['gulp-*', 'main-bower-files', 'run-sequence', 'del']
});

module.exports = function (options) {

  gulp.task('partials:clean', function() {
    $.del(options.src + '/partials');
  });

  gulp.task('partials', ['partials:clean'], function () {
    return gulp.src([
      options.src + '/app/**/*.html',
      options.tmp + '/serve/app/**/*.html'
    ])
      .pipe($.minifyHtml({
        empty: true,
        spare: true,
        quotes: true
      }))
      .pipe($.angularTemplatecache('templateCacheHtml.js', {
        module: 'ds',
        root: 'app'
      }))
      .pipe(gulp.dest(options.src + '/partials'));
  });

};
