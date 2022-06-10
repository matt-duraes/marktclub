const clean = require('gulp-clean');
const { src, dest } = require('gulp');

exports.limparArquivosDoMac = () => {
    return src(['**/.DS_Store', '**/._*'], { read: false }).pipe(clean());
};

exports.limparSessao = () => {
    return src('files/sessions', { read: false }).pipe(clean()).pipe(dest('files'));
};
