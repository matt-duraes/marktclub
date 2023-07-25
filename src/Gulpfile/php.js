const exec = require('gulp-exec');
const { src } = require('gulp');
const plumber = require('gulp-plumber');
const { mensagemSucesso } = require('./mensagem');

exports.phpCsFixer = path => {
    mensagemSucesso('Processando arquivo: ' + path);
    return src(path)
        .pipe(plumber())
        .pipe(exec('php php-cs-fixer.phar fix --config=.php-cs-fixer.dist.php --using-cache=yes ' + path));
};
