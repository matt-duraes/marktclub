const { src, dest } = require('gulp');
const fs = require('fs');
const { fsVerificarSeArquivoExiste, fsCriarDiretorio, fsDeletarDiretorio, fsCopiar } = require('./arquivo.js');
const exec = require('gulp-exec');
const replace = require('gulp-replace');
const plumber = require('gulp-plumber');
const { mensagemSucesso } = require('./mensagem.js');
let config;

exports.buildCopiarIndex = () => {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    return src('./src/Files/public/index.php')
        .pipe(plumber())
        .pipe(dest('./' + config.public));
};

exports.buildGit = async () => {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }

    if (!fs.existsSync('./.git')) {
        return src('./')
            .pipe(plumber())
            .pipe(exec('git init'))
            .pipe(exec('git remote add origin ' + config.gitProjetoOrigin))
            .pipe(exec('git remote add upstream ' + config.gitProjetoUpstream));
    }
    const gitRemote = await fs.readFileSync('./.git/config', 'utf-8');
    const gitRemoveValido = typeof gitRemote === 'string' && gitRemote != '';
    const remoteOrigin = gitRemoveValido && /\[remote \"origin\"\]/gm.test(gitRemote);
    const remoteUpstream = gitRemoveValido && /\[remote \"upstream\"\]/gm.test(gitRemote);
    if (remoteOrigin && remoteUpstream) {
        return src('./')
            .pipe(plumber())
            .pipe(exec('git remote remove origin'))
            .pipe(exec('git remote remove upstream'))
            .pipe(exec('git remote add origin ' + config.gitProjetoOrigin))
            .pipe(exec('git remote add upstream ' + config.gitProjetoUpstream));
    } else if (remoteOrigin) {
        return src('./')
            .pipe(plumber())
            .pipe(exec('git remote remove origin'))
            .pipe(exec('git remote add origin ' + config.gitProjetoOrigin))
            .pipe(exec('git remote add upstream ' + config.gitProjetoUpstream));
    } else if (remoteUpstream) {
        return src('./')
            .pipe(plumber())
            .pipe(exec('git remote remove upstream'))
            .pipe(exec('git remote add origin ' + config.gitProjetoOrigin))
            .pipe(exec('git remote add upstream ' + config.gitProjetoUpstream));
    }
    return src('./')
        .pipe(plumber())
        .pipe(exec('git remote add origin ' + config.gitProjetoOrigin))
        .pipe(exec('git remote add upstream ' + config.gitProjetoUpstream));
};

exports.buildPhpMussel = () => {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    const virus = config.phpMussel.virusTotalKey;
    const google = config.phpMussel.googleKey;
    return src('./src/Files/raiz/phpmussel.yml')
        .pipe(plumber())
        .pipe(replace('{{virus}}', virus))
        .pipe(replace('{{google}}', google))
        .pipe(dest('./'));
};

exports.buildCopiarComposerConfig = async () => {
    if (await fsVerificarSeArquivoExiste('./composer.json')) {
        mensagemSucesso('Arquivo do composer não foi copiado porque ele já existe.');
        return Promise.resolve();
    }
    return src(['./src/Files/raiz/composer.json']).pipe(plumber()).pipe(dest('./'));
};
exports.buildArquivoConfigVsCode = async () => {
    await fsCriarDiretorio('./.vscode');
    return src(['./src/Files/vscode/settings.json']).pipe(plumber()).pipe(dest('./.vscode'));
};

exports.buildComposerInstall = () => {
    return src(['./']).pipe(exec('composer install'));
};

exports.buildDocker = async () => {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }

    const diretorioPublico = config.public;
    const nome = config.nome;
    const portaHttps = config.docker.https;
    const portaHttp = config.docker.http;
    const portaDb = config.docker.db;
    const portaPma = config.docker.pma;
    const dbNome = config.banco.nome;
    const dbSenha = config.banco.senha;

    await fsDeletarDiretorio('./files/docker_host');
    await fsCriarDiretorio('./files/docker_host');

    src('./src/Files/docker_host/default')
        .pipe(plumber())
        .pipe(replace('{{public}}', diretorioPublico))
        .pipe(dest('./files/docker_host'));

    return src('./src/Files/raiz/docker-compose.yml')
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

exports.buildEnv = async () => {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }

    const titulo = config.titulo;
    const diretorioPublico = config.public;
    const dbHost = config.nome != '' ? 'db-' + config.nome : '';
    const dbBanco = config.banco.nome;
    const dbSenha = config.banco.senha;

    await fsCriarDiretorio('./env');
    if (await fsVerificarSeArquivoExiste('./env/.env')) {
        mensagemSucesso('Arquivo ./env/.env não foi copiado porque ele já existe.');
        return Promise.resolve();
    }
    return src('./src/Files/env/.env')
        .pipe(plumber())
        .pipe(replace('{{titulo}}', titulo))
        .pipe(replace('{{public}}', diretorioPublico.replace(/\//g, '')))
        .pipe(replace('{{db_host}}', dbHost))
        .pipe(replace('{{db_banco}}', dbBanco))
        .pipe(replace('{{db_usuario}}', dbSenha))
        .pipe(replace('{{db_senha}}', dbSenha))
        .pipe(dest('./env'));
};

exports.buildArquivosRaiz = () => {
    return src([
        './src/Files/raiz/.eslintignore',
        './src/Files/raiz/.prettierrc',
        './src/Files/raiz/adp.phar',
        './src/Files/raiz/.editorconfig',
        './src/Files/raiz/.php-cs-fixer.dist.php',
        './src/Files/raiz/php-cs-fixer.phar',
        './src/Files/raiz/captainhook.json',
    ])
        .pipe(plumber())
        .pipe(dest('./'));
};
exports.buildArquivoChave = () => {
    return src(['./src/Files/chave/.chave_publica', './src/Files/chave/.chave_privada'])
        .pipe(plumber())
        .pipe(dest('./chave'));
};
exports.buildArquivoErro = () => {
    return src('./src/Files/erro/lista.txt').pipe(plumber()).pipe(dest('./files/erro'));
};
exports.buildArquivosTeste = () => {
    return src(['./src/Tests/selenium.jar']).pipe(plumber()).pipe(dest('./tests/server'));
};

exports.buildDiretorios = async () => {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    const diretorioPublico = config.public;

    await fsCriarDiretorio('./app');
    await fsCriarDiretorio('./app/Classes');
    await fsCriarDiretorio('./app/Controllers');
    await fsCriarDiretorio('./app/Models');
    await fsCriarDiretorio('./app/Helpers');
    await fsCriarDiretorio('./app/Middlewares');
    await fsCriarDiretorio('./database');
    await fsCriarDiretorio('./postman');
    await fsCriarDiretorio('./files/arquivo_privado');
    await fsCriarDiretorio('./files/arquivo_publico');
    await fsCriarDiretorio('./files/banco');
    await fsCriarDiretorio('./files/build');
    await fsCriarDiretorio('./files/log');
    await fsCriarDiretorio('./files/erro');
    await fsCriarDiretorio('./files/banco');
    await fsCriarDiretorio('./files/banco/mysql');
    await fsCriarDiretorio('./files/phpmussel');
    await fsCriarDiretorio('./files/phpmussel/assinatura');
    await fsCriarDiretorio('./files/phpmussel/cache');
    await fsCriarDiretorio('./files/phpmussel/quarentena');
    await fsCriarDiretorio('./files/sessions');
    await fsCriarDiretorio('./files/sessions');
    await fsCriarDiretorio('./' + diretorioPublico);
    await fsCriarDiretorio('./chave');
    await fsCriarDiretorio('./resources');
    await fsCriarDiretorio('./resources/css');
    await fsCriarDiretorio('./resources/js');
    await fsCriarDiretorio('./resources/php');
    await fsCriarDiretorio('./routes');
    await fsCriarDiretorio('./tests');
    await fsCriarDiretorio('./tests/server');
    await fsCriarDiretorio('./views');
    await fsCriarDiretorio('./views/pages');
    await fsCriarDiretorio('./views/templates');
    await fsCriarDiretorio('./views/images');

    return src('./')
        .pipe(plumber())
        .pipe(exec('chmod 775 ./files/sessions'))
        .pipe(exec('chmod 775 ./files/arquivo_privado'))
        .pipe(exec('chmod 775 ./files/arquivo_publico'))
        .pipe(exec('chmod 775 ./files/log'))
        .pipe(exec('chmod 775 ./files/phpmussel/cache'))
        .pipe(exec('chmod 775 ./files/phpmussel/quarentena'));
};

exports.buildArquivosPublico = () => {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    const diretorioPublico = config.public;

    return src(['./src/Files/public/.htaccess', './src/Files/public/robots.txt', './src/Files/public/index.php'])
        .pipe(plumber())
        .pipe(dest('./' + diretorioPublico));
};

exports.buildPaginaExemplo = async () => {
    if (fs.existsSync('./routes/SiteRoute.php')) {
        return Promise.resolve(true);
    }

    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    const diretorioPublico = config.public;

    await fsCriarDiretorio('./app/Controllers/Site');
    await fsCriarDiretorio('./' + diretorioPublico + '/css');
    await fsCriarDiretorio('./views/pages/site');
    await fsCriarDiretorio('./views/pages/site/exemplo');

    src(['src/Files/exemplo/pages/css/site_exemplo.css'])
        .pipe(plumber())
        .pipe(dest('./' + diretorioPublico + '/css'));
    src(['src/Files/exemplo/pages/css/layout.styl']).pipe(plumber()).pipe(dest('./views/pages/site/exemplo/css'));
    src(['src/Files/exemplo/pages/index.view']).pipe(plumber()).pipe(dest('./views/pages/site/exemplo'));
    src(['src/Files/exemplo/ExemploController.php']).pipe(plumber()).pipe(dest('./app/Controllers/Site'));
    src(['src/Files/exemplo/site_exemplo.php']).pipe(plumber()).pipe(dest('./files/build/views'));
    src(['src/Files/exemplo/SiteRoute.php']).pipe(plumber()).pipe(dest('./routes'));

    return Promise.resolve(true);
};

exports.buildCorrigindoComposer = () => {
    src('src/Files/vendor/Scanner.php').pipe(plumber()).pipe(dest('./vendor/phpmussel/core/src'));
    src('src/Files/vendor/GlobalFunctionsHelper.php')
        .pipe(plumber())
        .pipe(dest('./vendor/box/spout/src/Spout/Common/Helper'));
    return Promise.resolve(true);
};
