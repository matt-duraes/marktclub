const { src, dest } = require('gulp');
const clean = require('gulp-dest-clean');
const plumber = require('gulp-plumber');
const imagemin = require('gulp-imagemin');
const fs = require('fs');

let config;

exports.imagemTodos = async function () {
    if (config == undefined) {
        config = await JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    return src('views/images/**/*')
        .pipe(plumber())
        .pipe(clean(config.public + '/images'))
        .pipe(dest(config.public + '/images'));
};

exports.imagemDeploy = async function () {
    if (config == undefined) {
        config = await JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    return src([
        config.public + '/images/**/*.jpg',
        config.public + '/images/**/*.jpeg',
        config.public + '/images/**/*.png',
        config.public + '/images/**/*.gif',
    ])
        .pipe(plumber())
        .pipe(
            imagemin({
                silent: true,
            })
        )
        .pipe(dest(config.public + '/images'));
};
