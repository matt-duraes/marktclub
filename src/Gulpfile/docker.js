const { src } = require('gulp');
const exec = require('gulp-exec');
const plumber = require('gulp-plumber');

exports.dockerComposerUp = () => {
    return src('./').pipe(plumber()).pipe(exec('docker-compose up -d'));
};

exports.dockerComposerDown = () => {
    return src('./').pipe(plumber()).pipe(exec('docker-compose down'));
};
