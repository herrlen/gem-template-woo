const gulp = require('gulp');
const sass = require('gulp-sass')(require('sass'));
const cleanCSS = require('gulp-clean-css');
const autoprefixer = require('gulp-autoprefixer');
const rename = require('gulp-rename');
const uglify = require('gulp-uglify');
const plumber = require('gulp-plumber');
const sourcemaps = require('gulp-sourcemaps');
const browserSync = require('browser-sync').create();
const zip = require('gulp-zip');

// ---------------------------------------------------------
// EINSTELLUNGEN (Hier anpassen!)
// ---------------------------------------------------------
const config = {
    // WICHTIG: Hier die URL deiner lokalen WordPress-Seite eintragen
    // Beispiel: 'localhost:8888/wordpress' oder 'mein-shop.local'
    localUrl: 'http://lensuh.local', 
    paths: {
        styles: {
            src: './src/scss/**/*.scss',
            dest: './assets/css'
        },
        scripts: {
            src: './src/js/**/*.js',
            dest: './assets/js'
        },
        php: './**/*.php' // Überwacht alle PHP Dateien
    }
};

// ---------------------------------------------------------
// TASKS
// ---------------------------------------------------------

// 1. CSS Verarbeitung
function styles() {
    return gulp.src(config.paths.styles.src)
        .pipe(sourcemaps.init())
        .pipe(plumber()) // Verhindert Absturz bei Fehlern
        .pipe(sass().on('error', sass.logError)) // SCSS zu CSS
        .pipe(autoprefixer()) // Fügt Vendor-Prefixes hinzu (-webkit, -moz)
        .pipe(gulp.dest(config.paths.styles.dest)) // Speichert normale CSS
        .pipe(cleanCSS()) // Minifiziert
        .pipe(rename({ suffix: '.min' })) // Benennt in .min.css um
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(config.paths.styles.dest))
        .pipe(browserSync.stream()); // Lädt CSS neu ohne Refresh
}

// 2. JS Verarbeitung
function scripts() {
    return gulp.src(config.paths.scripts.src)
        .pipe(plumber())
        .pipe(uglify()) // JS Komprimierung
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(config.paths.scripts.dest))
        .pipe(browserSync.stream());
}

// 3. BrowserSync & Watcher
function serve() {
    browserSync.init({
        proxy: config.localUrl, // Verbindet sich mit deinem lokalen WP
        notify: false,
        open: true
    });

    // Beobachter
    gulp.watch(config.paths.styles.src, styles);
    gulp.watch(config.paths.scripts.src, scripts);
    gulp.watch(config.paths.php).on('change', browserSync.reload);
}

// 4. ZIP erstellen (Für ThemeForest Upload)
function bundle() {
    return gulp.src([
        '**/*',
        '!node_modules/**',
        '!node_modules',
        '!src/**',
        '!src',
        '!gulpfile.js',
        '!package.json',
        '!package-lock.json',
        '!.gitignore',
        '!*.zip'
    ])
    .pipe(zip('theme-installable.zip'))
    .pipe(gulp.dest('./dist'));
}

// Export der Tasks für die Konsole
exports.styles = styles;
exports.scripts = scripts;
exports.zip = bundle;
exports.default = gulp.series(styles, scripts, serve);