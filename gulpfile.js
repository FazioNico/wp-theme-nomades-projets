/**
 * @Author: Nicolas Fazio <webmaster-fazio>
 * @Date:   28-09-2016
 * @Email:  contact@nicolasfazio.ch
 * @Last modified by:   webmaster-fazio
 * @Last modified time: 01-06-2017
 */

var gulp              = require('gulp'),
    browserSync       = require('browser-sync').create(),
    reload            = browserSync.reload,
    sass              = require('gulp-sass'),
    bower             = require('gulp-bower'),
    concat            = require('gulp-concat'),
    concatCss         = require('gulp-concat-css'),
    notify            = require("gulp-notify") ,
    jshint            = require('gulp-jshint'),
    uglify            = require('gulp-uglify'),
    rename            = require("gulp-rename"),
    cssmin            = require('gulp-cssmin'),
    stripCssComments  = require('gulp-strip-css-comments'),
    autoprefixer      = require('gulp-autoprefixer'),
    useref            = require('gulp-useref'),
    sourcemaps        = require('gulp-sourcemaps'),

    babel = require('gulp-babel'),
    babelify = require('babelify'),
    browserify = require("browserify"),
    transform = require('vinyl-transform'),
    source = require('vinyl-source-stream');

// Config
var path = {
  dev:         './',
  js:           './js',
   sassPath:     './sass',
   bowerDir:     './bower_components' ,
  tmpDir:       './tmp',
  desDir:       '../../nomades-projets/'
}
var bowerDependencies = [
  path.bowerDir + '/jquery/dist/jquery.min.js'
  //,path.bowerDir + '/bootstrap-sass/assets/javascripts/bootstrap.min.js'
]

//// run bower install
gulp.task('bower', function() { 
    return bower()
      .pipe(gulp.dest(path.bowerDir)) 
});

/// task to bowerDependencies
gulp.task('bowerDependencies', function() {
  return gulp.src(bowerDependencies)
    .pipe(concat('bundle.js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(gulp.dest(path.desDir + 'js/'))
});

// Sass task
// will auto-update browsers
gulp.task('sass', function () {
    return gulp.src(path.sassPath+'/*.scss')
      .pipe(sass())
      .pipe(autoprefixer('last 2 version', 'safari 5', 'ie 8', 'ie 9'))
      .pipe(cssmin())
      .pipe(gulp.dest(path.dev))
      .pipe(reload({stream:true}));
});

// Task to build final min css file
gulp.task('css', function () {
  return gulp.src([path.tmpDir+'/*.css', path.dev+'/style.css'])
    .pipe(useref())
    .pipe(concatCss("style.css"))
    .pipe(cssmin({keepSpecialComments : 1}))
    .pipe(gulp.dest(path.desDir))
    .pipe(reload({stream:true}));
});

// JS task
gulp.task('js', function() {
  return gulp.src([path.js +'/*.js'])
    .pipe(jshint())
    .pipe(jshint.reporter('default'))
    .pipe(concat('app.js'))
    .pipe(rename({suffix: '.min'}))
    .pipe(uglify())
    .pipe(gulp.dest(path.desDir + 'js/'))
    .pipe(reload({stream:true}));
});
// Task to build JS files
gulp.task("ES6-js", function(){
    return browserify(path.js +'/main.js',{
        debug: true
    })
    .transform(babelify.configure({
        presets : ["es2015"]
    }))
    .bundle()
    .pipe(source("app.min.js"))
    .pipe(gulp.dest(path.desDir + 'js/'))
    .pipe(reload({stream:true}));
});
// Task to copy *.php files in desDir
gulp.task('php', function () {
  return gulp.src(['./*.php', './**/*.php', './template-parts/*.php'])
    .pipe(gulp.dest(path.desDir))
    .pipe(reload({stream:true}));
});

// Task to copy img files in desDir
gulp.task('img', function () {
  return gulp.src(['./src/img/*.png', './src/img/*.jpg', './src/img/*.jpeg'])
    .pipe(gulp.dest(path.desDir+'src/img/'))
    .pipe(reload({stream:true}));
});

// Task to copy reste of WP files in desDir
gulp.task('wpfile', function () {
  return gulp.src(['./**/*.pot', './screenshot.png'])
    .pipe(gulp.dest(path.desDir))
    .pipe(reload({stream:true}));
});

// browser-sync task for starting the server.
gulp.task('browser-sync', function() {
    //watch files
    var files = [
    './js/app.min.js',
    './style.css',
    './*.php'
    ];
    //initialize browsersync
    browserSync.init(files, {
    //browsersync with a php server
    port: 3001,
    proxy: "localhost:3000/wp/",
    notify: true
    });
});

// Task watch
// Rerun the task when a file changes
 gulp.task('watch', function() {
  gulp.watch('./*.php', ['php']);
  gulp.watch('./**/*.php', ['php']);
  gulp.watch('./template-parts/*.php', ['php']);
  //gulp.watch('./js/*.js', ['js']);
  gulp.watch('./js/*.js', ['ES6-js']);
  gulp.watch('./js/**/*.js', ['ES6-js']);
  gulp.watch('./style.css', ['css']); 
  gulp.watch('./sass/**/*.scss', ['sass']); 
});

// Task to watch change and reload
gulp.task('default', ['bowerDependencies', 'sass','browser-sync', 'ES6-js', 'css', 'php', 'img', 'wpfile', 'watch']);
