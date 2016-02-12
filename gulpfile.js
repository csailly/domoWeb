'use strict';

var gulp = require('gulp');
var plumber = require("gulp-plumber");//Prevent pipe breaking caused by errors from gulp plugins

//****CSS***
var sourcemaps = require('gulp-sourcemaps');//Source map support
var less = require("gulp-less");
var rename = require("gulp-rename");//provides simple file renaming methods.
var autoprefixer = require("gulp-autoprefixer");//Prefix CSS with Autoprefixer
var cssnano = require('gulp-cssnano');//Minify CSS with cssnano.

//****JavaScript****
var ngConstant = require('gulp-ng-constant');//dynamic generation of angular constant modules.
var angularFilesort = require('gulp-angular-filesort');//Automatically sort AngularJS app files depending on module definitions and usage
var inject = require('gulp-inject');//A javascript, stylesheet and webcomponent injection plugin for Gulp, i.e. inject file references into your index.html

//****HTML****
var templateCache = require('gulp-angular-templatecache');//Concatenates and registers AngularJS templates in the $templateCache.


var dist = {
    path: 'dist',
    css: 'dist/css',
    sourcemaps: 'dist/maps',
    scripts: 'dist/scripts'
};

//----------------------------------------------------------------------------------
//1° TODO Transform to css single file
//2° TODO Remove unused css with uncss
//3° Create source maps
//3° Create vendor css
//4° Minimify
//5° Watch


gulp.task("less", function () {
    return gulp.src('./src/app/**/*.less')
        .pipe(plumber({
            errorHandler: function (error) {
                console.log(error);
                this.emit("end");
            }
        }))

        .pipe(sourcemaps.init())
        .pipe(less())
        .pipe(autoprefixer())
        .pipe(gulp.dest(dist.css))
        .pipe(cssnano())
        .pipe(rename({suffix: ".min"}))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(dist.css));
});

//----------------------------------------------------------------------------------

gulp.task('config', function () {
        return gulp.src('./config/dev.json')
            .pipe(ngConstant({
                name: 'domoweb.configs',
                dest: "constants.config.js",
                deps: false
            }))
            .pipe(gulp.dest('src/app/configs/'));
    }
);

gulp.task('scripts', ['config'], function () {
        return gulp.src(['./src/app/**/*.js'])//TODO do not include test and mock files
            .pipe(gulp.dest(dist.scripts));
    }
);
//----------------------------------------------------------------------------------

gulp.task('inject', ['less', 'scripts'], function () {
    var target = gulp.src('./src/index.html');
    var jsSources = gulp.src([dist.scripts + '/**/*.js'])//TODO do not include test and mock files
        .pipe(angularFilesort());
    // It's not necessary to read the css files (will speed up things), we're only after their paths:
    var cssSources = gulp.src([dist.css + '/**/*.css'], {read: false});

    return target.pipe(inject(jsSources))
        .pipe(inject(cssSources))
        .pipe(gulp.dest(dist.path));
});

//----------------------------------------------------------------------------------
gulp.task('htmlTemplate', function () {
        return gulp.src('src/app/**/*.html')
            .pipe(templateCache('templateCacheHtml.js', {
                module: 'domoweb',
                root: 'app'
            }))
            .pipe(gulp.dest(dist.path));
    }
);


//----------------------------------------------------------------------------------

gulp.task('watch', function () {
    gulp.watch(['src/app/**/*.less'],
        function (event) {
            if (event.type === 'changed') {
                gulp.start('less');
            } else {
                gulp.start('inject');
            }
        });

    gulp.watch(['src/app/**/*.js'],//TODO do not include test and mock files
        function (event) {
            if (event.type === 'changed') {
                gulp.start('scripts');
            } else {
                gulp.start('inject');
            }
        });

});