const clean = require('gulp-clean');
const { src, dest } = require('gulp');
const { fsDeletarDiretorio } = require('./arquivo');

exports.limparArquivosDoMac = function () {
    return src(['**/.DS_Store', '**/._*'], { read: false }).pipe(clean());
};

exports.limparSessao = function () {
    return src('files/sessions', { read: false }).pipe(clean()).pipe(dest('files'));
};

exports.limparDeploy = async () => {
    setTimeout(() => {
        fsDeletarDiretorio('./files/build/js');
        fsDeletarDiretorio('./files/build/css');
        fsDeletarDiretorio('./files/build/html');
    }, 2000);
};
