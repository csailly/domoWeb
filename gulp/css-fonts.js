'use strict';

var gulp = require('gulp');

var $ = require('gulp-load-plugins')({
  pattern: ['gulp-*', 'main-bower-files', 'del']
});

module.exports = function (options) {

  // Only applies for fonts from bower dependencies and assets/fonts folder
  gulp.task('cssfonts', function () {
    var fontsFiles = $.mainBowerFiles();
    fontsFiles.push(options.src + '/assets/fonts/**/*');

    return gulp.src(fontsFiles)
      .pipe($.filter('**/*.{eot,svg,ttf,woff,woff2}'))
      .pipe($.cssfont64())
      .pipe($.rename(function (path) {
        path.basename += "-cssfont";
      }))
      .pipe($.flatten())
      .pipe(gulp.dest(options.tmp + '/serve/app'));
  });

};
