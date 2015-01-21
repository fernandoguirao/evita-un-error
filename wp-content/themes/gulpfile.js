/* global require */
'use strict';

// VARIABLES

var gulp = require('gulp'),
    less = require('gulp-less'),
    // jade = require('gulp-jade'),
    // jade = require('gulp-jade-php'),
    browserSync = require('browser-sync'),
    reload = browserSync.reload,
    combineMediaQueries = require('gulp-combine-media-queries'),
    autoprefixer = require('gulp-autoprefixer'),
    cssmin = require('gulp-cssmin'),
    notify = require('gulp-notify'),
    imagemin = require('gulp-imagemin'),
    changed = require('gulp-changed'),
    pngcrush = require('imagemin-pngcrush');


// LESS

gulp.task('less', function() {
  gulp.src('./src/less/style.less')
  .pipe(less({
    compress: true
  }))
  .pipe(combineMediaQueries())
  .pipe(autoprefixer())
  .pipe(cssmin())
  .pipe(gulp.dest('./evita-un-error/'))
  .pipe(browserSync.reload({stream:true}))
  .pipe(notify({ message: 'less compiled' }));
});
 

// JADE

/*gulp.task('templates', function() {
  return gulp.src('./lib/*.jade')
    .pipe(jade({
      pretty: true,
      locals: {
        title: 'OMG THIS IS THE TITLE'
      }
    }))
    .pipe(gulp.dest('./evitaunerror/'))
    .pipe(notify({ message: 'jade compiled' }));
});*/

// IMAGES

// minify new images
gulp.task('imagemin', function() {
  var imgSrc = './src/images/**/*',
      imgDst = './evita-un-error/assets/images';
 
  gulp.src(imgSrc)
    .pipe(changed(imgDst))
    .pipe(imagemin({
          progressive: true,
          svgoPlugins: [{removeViewBox: false}],
          use: [pngcrush()]
      }))
    .pipe(gulp.dest(imgDst));
});

// SERVER

// Watch files for changes
gulp.task('watch', ['browser-sync'], function() {
  // Watch JADE files
  gulp.watch('./evita-un-error/views/*.twig', reload);
  // Watch Sass files
  gulp.watch('./src/less/**/*', ['less']);
  // Watch JS files
  // gulp.watch('src/js/**/*', ['js']);

  // Watch image files
  // gulp.watch('src/images/raster/*', ['images']);
  // // Watch SVG files
  // gulp.watch('src/images/vector/*', ['svgs']);
  // Watch HTML
  // gulp.watch('./evitaunerror/*.html', reload);
  // gulp.watch('./evitaunerror/*.php', reload);
});

gulp.task('browser-sync', function() {
    browserSync.init(['./evita-un-error/style.css', './evita-un-error/assets/js/**.*'], {
        // SI NO HAY SERVIDOR EXTERNO:
        // server: {
        //     baseDir: './evitaunerror/'
        // },
        proxy: {
          host: "localhost",
          port:8888
        }
    });
});

// DEFAULTS

gulp.task('default', ['less','imagemin','watch','browser-sync']);