'use strict';

var gulp = require('gulp');

var $ = require('gulp-load-plugins')({
pattern: ['gulp-*', 'run-sequence', 'del']
});

module.exports = function (options) {

gulp.task('html', ['inject:dist'], function () {
    var htmlFilter = $.filter('*.html');
    var appJsFilter = $.filter('**/app*.js');
    var jsFilter = $.filter('**/*.js');
    var cssFilter = $.filter('**/*.css');
    var assets;

    return gulp.src(options.tmp + '/serve/*.html')
        .pipe(assets = $.useref.assets())
        .pipe(jsFilter)
        .pipe($.ngAnnotate())
        .pipe(appJsFilter)
        .pipe($.using())
        .pipe($.uglify({
            mangle: true,
            compression: false
        })).on('error', options.errorHandler('Uglify'))
        .pipe(appJsFilter.restore())
        .pipe(jsFilter.restore())
        .pipe(cssFilter)
        .pipe($.csso())
        .pipe(cssFilter.restore())
        .pipe(assets.restore())
        .pipe($.useref())
        .pipe(htmlFilter)
        .pipe($.htmlmin({collapseWhitespace: true}))
        .pipe(htmlFilter.restore())
        .pipe(gulp.dest(options.dist + '/'))
        .pipe($.size({title: options.dist + '/', showFiles: true}));
});

gulp.task('build', function () {
    $.runSequence(['cssfonts', 'styles', 'partials'], 'html');
});

};
