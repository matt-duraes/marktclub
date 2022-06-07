const { src, dest } = require('gulp');
const fs = require('fs');
const {
    fsRemoverArquivoSeExistir,
    fsCriarArquivo,
    fsCriarDiretorio,
} = require('./arquivo.js');
const exec = require('gulp-exec');
const replace = require('gulp-replace');
const plumber = require('gulp-plumber');
let config;

exports.buildGit = function () {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./src/Gulpfile/gulp.json'));
    }
    if (!fs.existsSync('./.git')) {
        return src('./')
            .pipe(plumber())
            .pipe(exec('git init'))
            .pipe(exec('git remote add origin ' + config.git))
            .pipe(exec('cp ./src/Files/pre-commit ./.git/hooks/'))
            .pipe(exec('chmod 775 ./.git/hooks/pre-commit'));
    }
    return src('./')
        .pipe(plumber())
        .pipe(exec('cp ./src/Files/pre-commit ./.git/hooks/'))
        .pipe(exec('chmod 775 ./.git/hooks/pre-commit'));
};

exports.buildPhpMussel = function () {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./src/Gulpfile/gulp.json'));
    }
    const virus = config.phpMussel.virusTotalKey;
    const google = config.phpMussel.googleKey;
    return src('./src/Files/phpmussel.yml')
        .pipe(plumber())
        .pipe(replace('{{virus}}', virus))
        .pipe(replace('{{google}}', google))
        .pipe(dest('./'));
};

exports.buildComposer = function () {
    return src('/').pipe(plumber()).pipe(exec('composer install'));
};

exports.buildDocker = function () {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./src/Gulpfile/gulp.json'));
    }

    const nome = config.nome;
    const portaHttps = config.docker.https;
    const portaHttp = config.docker.http;
    const portaDb = config.docker.db;
    const portaPma = config.docker.pma;
    const dbNome = config.banco.nome;
    const dbSenha = config.banco.senha;

    return src('./src/Files/docker-compose.yml')
        .pipe(plumber())
        .pipe(replace('{{nome}}', nome))
        .pipe(replace('{{portaHttps}}', portaHttps))
        .pipe(replace('{{portaHttp}}', portaHttp))
        .pipe(replace('{{portaDb}}', portaDb))
        .pipe(replace('{{portaPma}}', portaPma))
        .pipe(replace('{{dbNome}}', dbNome))
        .pipe(replace('{{dbSenha}}', dbSenha))
        .pipe(dest('./'));
};

exports.buildEnv = async function () {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./src/Gulpfile/gulp.json'));
    }

    const titulo = config.titulo;
    const url = config.url.replace(/http(s)?\:\/\//, '');
    const public = config.public;
    const dbNome = config.banco.nome;
    const dbSenha = config.banco.senha;

    const conteudoLocal =
        'APP_URL=' +
        url +
        '\nAPP_TIPO=localhost\n\nSESSION_DIRETORIO={{ROOT}}/files/sessions\n\nDB_HOST=mariadb\nDB_BANCO=' +
        dbNome +
        '\nDB_USUARIO=root\nDB_SENHA=' +
        dbSenha +
        '\n';

    await fsRemoverArquivoSeExistir('.env.local');
    await fsCriarArquivo('.env.local', conteudoLocal);

    const conteudoAdp =
        'GIT=' +
        config.git +
        '\n\nDB_HOST=0.0.0.0:' +
        config.docker.db +
        '\nDB_BANCO=' +
        dbNome +
        '\nDB_USUARIO=root\nDB_SENHA=' +
        dbSenha +
        '\n';

    await fsRemoverArquivoSeExistir('src/Files/.adp');
    await fsCriarArquivo('src/Files/.adp', conteudoAdp);

    return src('./src/Files/.env')
        .pipe(plumber())
        .pipe(replace('{{titulo}}', titulo))
        .pipe(replace('{{public}}', public.replace(/\//g, '')))
        .pipe(dest('./'));
};

exports.buildArquivosRaiz = function () {
    return src([
        './src/Files/.eslintignore',
        './src/Files/.prettierrc',
        './src/Files/phpunit.xml',
        './src/Files/adp.phar',
    ])
        .pipe(plumber())
        .pipe(dest('./'));
};
exports.buildDiretorios = async function () {
    await fsCriarDiretorio('files/arquivos');
    await fsCriarDiretorio('files/log');
    await fsCriarDiretorio('files/banco');
    await fsCriarDiretorio('files/banco/mariadb');
    await fsCriarDiretorio('files/phpmussel');
    await fsCriarDiretorio('files/phpmussel/assinatura');
    await fsCriarDiretorio('files/phpmussel/cache');
    await fsCriarDiretorio('files/phpmussel/quarentena');
    await fsCriarDiretorio('files/sessions');

    return src('./')
        .pipe(plumber())
        .pipe(exec('chmod 775 ./files/sessions'))
        .pipe(exec('chmod 775 ./files/arquivos'))
        .pipe(exec('chmod 775 ./files/log'))
        .pipe(exec('chmod 775 ./files/phpmussel/assinatura'))
        .pipe(exec('chmod 775 ./files/phpmussel/cache'))
        .pipe(exec('chmod 775 ./files/phpmussel/quarentena'));
};
