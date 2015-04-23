
/*
*   SOURCE FILES DIRECTORY :
*   don't use this theme in WP, it only contains the source files to be compiled
*   those files are the ones you work on
*/
var srcDir = '';

/*
*   BUILD FILE DIRECTORY :
*   the development theme, activate this theme in WP while developing
*/
var buildDir = '../rome-1-build/';

// Load plugins
var gulp = require('gulp'),
  plugins = require('gulp-load-plugins')({ camelize: true }),
  lr = require('tiny-lr'),
  server = lr();

// Static
// moves HTML and PHP without changing them

gulp.task('static', function () {
  gulp.src(srcDir + '**/*.php', { base: srcDir})
  .pipe(gulp.dest(buildDir))
  .pipe(plugins.livereload(server));
});

// Styles
gulp.task('styles', function() {
  return gulp.src(srcDir + 'styles/style.scss', { base: srcDir})
  .pipe(plugins.rubySass({ style: 'nested'}))
  .pipe(plugins.autoprefixer('last 2 versions', 'ie 9', 'ios 6', 'android 4'))
  .pipe(gulp.dest(buildDir))
  // .pipe(plugins.minifyCss({ keepSpecialComments: '*' }))
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(buildDir));
});

// // Vendor Plugin Scripts
// gulp.task('plugins', function() {
//   return gulp.src([srcDir + 'js/source/plugins.js', srcDir + 'js/vendor/*.js'], { base: srcDir})
//   .pipe(plugins.concat('plugins.js'))
//   .pipe(gulp.dest(srcDir + 'js/build'))
//   .pipe(plugins.rename({ suffix: '.min' }))
//   .pipe(plugins.uglify())
//   .pipe(plugins.livereload(server))
//   .pipe(gulp.dest(buildDir));
// });

// Site Scripts
gulp.task('scripts', function() {
  return gulp.src([srcDir + 'js/*.js'], { base: srcDir})
  .pipe(plugins.concat('main.js'))
  .pipe(gulp.dest(buildDir + 'js/'))
  .pipe(plugins.rename({ suffix: '.min' }))
  //.pipe(plugins.uglify())
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(buildDir + '/js'));
});

// Images
gulp.task('images', function() {
  return gulp.src([srcDir + 'img/**/*', '!' + srcDir + 'img/**/*.ai'], { base: srcDir, ignore: '**/*.ai' })
  .pipe(plugins.cache(plugins.imagemin({ optimizationLevel: 7, progressive: true, interlaced: true })))
  .pipe(plugins.livereload(server))
  .pipe(gulp.dest(buildDir + '/img'));
});


// Fonts
gulp.task('fonts', function() {
  return gulp.src(srcDir + 'font/**/*', { base: srcDir})
  .pipe(gulp.dest(buildDir + '/font'));
});


// Watch
gulp.task('watch', function() {

  // Listen on port 35729
  server.listen(35729, function (err) {
  if (err) {
    return console.log(err);
  }

  // Watch .php files
  gulp.watch(srcDir + '*.php', ['static']);

 // Watch .scss files
  gulp.watch(srcDir + 'styles/**/*.scss', ['styles']);

  // Watch .js files
  gulp.watch(srcDir + 'js/**/*.js', [ 'scripts']);

  // Watch image files
  gulp.watch(srcDir + 'img/**/*', ['images']);

  // Watch font files
  gulp.watch(srcDir + 'font/**/*', ['fonts']);

  });

});

// Default task
gulp.task('default', ['styles', 'scripts', 'images', 'fonts', 'static', 'watch']);