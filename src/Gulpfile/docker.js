const { src } = require('gulp');
const exec = require('gulp-exec');
const plumber = require('gulp-plumber');

exports.dockerComposerUp = function () {
    return src('./').pipe(plumber()).pipe(exec('docker compose up -d'));
};

exports.dockerComposerDown = function () {
    return src('./').pipe(plumber()).pipe(exec('docker compose down'));
};
