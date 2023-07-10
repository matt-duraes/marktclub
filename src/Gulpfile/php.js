const exec = require('gulp-exec');
const { src } = require('gulp');
const plumber = require('gulp-plumber');

exports.phpCsFixer = path => {
    return src(path)
        .pipe(plumber())
        .pipe(exec('php php-cs-fixer.phar fix --config=.php-cs-fixer.dist.php --using-cache=yes ' + path));
};
