var gulp = require('gulp'); 

var browserify = require('browserify');
var babelify = require('babelify');
var source = require('vinyl-source-stream');
var concat = require('gulp-concat');
var rename = require('gulp-rename');
var uglify = require('gulp-uglify');

var minifyCSS = require('gulp-minify-css');
var autoprefixer = require('gulp-autoprefixer');

gulp.task('js', function(){
	return browserify({
		entries: 'js/app.js',
		debug: true
	})
	.transform(babelify)
    .bundle()
    .pipe(source('candy.min.js'))
    .pipe(gulp.dest('dist'));
});

gulp.task('watch', function(){
	gulp.watch(['js/app.js'], ['js']);
});