'use strict';

var gulp = require('gulp');
var ngConstant = require('gulp-ng-constant');
var rename = require("gulp-rename");
var $ = require('gulp-load-plugins')();

module.exports = function (options) {
    gulp.task('constants', function () {
            return gulp.src('./config/dev.json')
                .pipe($.ngConstant({
                    name: 'domoweb.configs',
                    dest: "constants.config.js",
                    deps: false
                }))
                .pipe(gulp.dest('src/app/configs/'));
        }
    );
}
;
