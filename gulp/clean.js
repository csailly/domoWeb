/**
 * Task used to clean build directories.
 */

'use strict';

var gulp = require('gulp');

var $ = require('gulp-load-plugins')({
  pattern: ['gulp-*', 'del']
});

module.exports = function (options) {
  gulp.task('clean', function (done) {
    $.del([
      options.dist + '/',
      options.tmp + '/'
    ], done);
  });
};
