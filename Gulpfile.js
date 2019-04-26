'use strict';

var gulp          = require('gulp');
var sass          = require('gulp-sass');
var wpPot         = require('gulp-wp-pot');

sass.compiler = require('node-sass');

gulp.task('sass', function () {
  return gulp.src('./template/assets/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('./template/assets'));
});

gulp.task('watch', function(){
  gulp.watch('./template/assets/*.scss', gulp.series('sass'));
});

gulp.task('default', function () {
  return gulp.src('**/*.php')
    .pipe(wpPot( {
        domain: 'elegant-crm',
        package: 'Elegant CRM'
    } ))
    .pipe(gulp.dest('languages/elegant-crm.pot'));
});