const gulp = require('gulp')
const sass = require('gulp-sass')
const postcss = require('gulp-postcss')
const autoprefixer = require('autoprefixer')
const cssnano = require('cssnano')
const uglify = require('gulp-uglify')

gulp.task('css', () => {
    return gulp.src('./src/**/*.scss')
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss([autoprefixer(), cssnano()]))
        .pipe(gulp.dest('./dist'))
})

gulp.task('js', () => {
    return gulp.src('./src/**/*.js')
        .pipe(uglify())
        .pipe(gulp.dest('./dist'))
})

gulp.task('watch', () => {
    gulp.watch('./src/**/*.scss', gulp.parallel('css'))
    gulp.watch('./src/**/*.js', gulp.parallel('js'))
})

gulp.task('default', gulp.parallel('css', 'js', done => {
    done()
}))
