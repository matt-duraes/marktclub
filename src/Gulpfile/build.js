const { src, dest } = require('gulp');
const fs = require('fs');
const {
    fsRemoverArquivoSeExistir,
    fsVerificarSeArquivoExiste,
    fsCriarArquivo,
    fsCriarDiretorio,
    fsDeletarDiretorio,
    fsCopiar,
    fsListarDiretorio,
} = require('./arquivo.js');
const exec = require('gulp-exec');
const replace = require('gulp-replace');
const plumber = require('gulp-plumber');
const { mensagemErro, mensagemSucesso } = require('./mensagem.js');
let config;

exports.buildDefineTabela = async () => {
    const lista = await fsListarDiretorio('./database');
    let conteudo = '<?php\n\n';
    let quantidade = lista.length;
    let i, diretorio, tabela, arquivo;
    for (i = 0; i < quantidade; i++) {
        diretorio = lista[i];
        arquivo = await fsListarDiretorio('./database/' + diretorio);
        tabela = arquivo.find(e => /^tabela\-/.test(e));
        tabela = tabela == undefined ? diretorio : tabela.replace('tabela-', '');
        conteudo += `define("TABELA_${diretorio.toUpperCase()}", "${tabela}");\n`;
    }

    await fsRemoverArquivoSeExistir('./files/banco/tabela.php');
    await fsCriarArquivo('./files/banco/tabela.php', conteudo);
};

exports.buildGit = () => {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    if (!fs.existsSync('./.git')) {
        return src('./')
            .pipe(plumber())
            .pipe(exec('git init'))
            .pipe(exec('git remote remove origin'))
            .pipe(exec('git remote remove upstream'))
            .pipe(exec('git remote add origin ' + config.gitOrigin))
            .pipe(exec('git remote add upstream ' + config.gitUpstream));
    }
    return src('./')
        .pipe(plumber())
        .pipe(exec('git remote remove origin'))
        .pipe(exec('git remote remove upstream'))
        .pipe(exec('git remote add origin ' + config.gitOrigin))
        .pipe(exec('git remote add upstream ' + config.gitUpstream));
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

exports.buildComposerInstall = () => {
    return src(['./']).pipe(exec('composer install'));
};

exports.buildDocker = () => {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }

    const public = config.public;
    const nome = config.nome;
    const portaHttps = config.docker.https;
    const portaHttp = config.docker.http;
    const portaDb = config.docker.db;
    const portaPma = config.docker.pma;
    const dbNome = config.banco.nome;
    const dbSenha = config.banco.senha;

    src('./src/Files/docker_host/000-default.conf')
        .pipe(plumber())
        .pipe(replace('{{public}}', public))
        .pipe(dest('./files/docker_host'));

    src('./src/Files/docker_host/default-ssl.conf')
        .pipe(plumber())
        .pipe(replace('{{public}}', public))
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
    const url = config.url.replace(/http(s)?\:\/\//, '');
    const public = config.public;
    const dbNome = config.banco.nome;
    const dbSenha = config.banco.senha;

    const conteudoLocal =
        'APP_URL=' +
        url +
        '\nAPP_TIPO=localhost\n\nSESSION_DIRETORIO={{ROOT}}/files/sessions\n\nDB_HOST=mysql\nDB_BANCO=' +
        dbNome +
        '\nDB_USUARIO=root\nDB_SENHA=' +
        dbSenha +
        '\n';

    if (!(await fsVerificarSeArquivoExiste('.env.local'))) {
        await fsCriarArquivo('.env.local', conteudoLocal);
    } else {
        mensagemSucesso('Não foi criado o arquivo .env.local porque ele já existe.');
    }

    const conteudoAdp =
        'GIT=' +
        config.gitOrigin +
        '\n\nDB_HOST=0.0.0.0:' +
        config.docker.db +
        '\nDB_BANCO=' +
        dbNome +
        '\nDB_USUARIO=root\nDB_SENHA=' +
        dbSenha +
        '\n';

    await fsRemoverArquivoSeExistir('./files/config/.adp');
    await fsCriarArquivo('./files/config/.adp', conteudoAdp);

    return src('./src/Files/raiz/.env')
        .pipe(plumber())
        .pipe(replace('{{titulo}}', titulo))
        .pipe(replace('{{public}}', public.replace(/\//g, '')))
        .pipe(dest('./'));
};

exports.buildArquivosRaiz = () => {
    return src([
        './src/Files/raiz/.eslintignore',
        './src/Files/raiz/.prettierrc',
        './src/Files/raiz/adp.phar',
        './src/Files/raiz/.chave_publica',
        './src/Files/raiz/.chave_privada',
        './src/Files/raiz/.editorconfig',
        './src/Files/raiz/.php-cs-fixer.dist.php',
        './src/Files/raiz/captainhook.json',
    ])
        .pipe(plumber())
        .pipe(dest('./'));
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
    const public = config.public;

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
    await fsCriarDiretorio('./' + public);
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
    const public = config.public;

    return src(['./src/Files/public/.htaccess', './src/Files/public/robots.txt', './src/Files/public/index.php'])
        .pipe(plumber())
        .pipe(dest('./' + public));
};

exports.buildBaixandoUpdate = async () => {
    await fsDeletarDiretorio('./files/upgrade');
    return src(['./']).pipe(plumber()).pipe(exec('git clone git@github.com:marktclub/framework.git files/upgrade'));
};

exports.buildCopiandoUpdate = async () => {
    if (!(await fsVerificarSeArquivoExiste('./files/upgrade/src'))) {
        mensagemErro('Não foi encontrado o download para fazer upgrade.');
        mensagemErro('Execute "gulp update" para baixar a atualização.');
        return Promise.reject();
    }

    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    const public = config.public;

    await fsDeletarDiretorio('./src');
    await fsCopiar('./files/upgrade/src', './src');
    await fsCopiar('./files/upgrade/gulpfile.js', './gulpfile.js');
    await fsDeletarDiretorio('./files/upgrade');

    src('./src/Files/public/index.php')
        .pipe(plumber())
        .pipe(dest('./' + public));

    return Promise.resolve();
};

exports.buildLimparFramework = async () => {
    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }

    const public = config.public;
    await fsDeletarDiretorio('./app');
    await fsDeletarDiretorio('./database');
    await fsDeletarDiretorio('./resources');
    await fsDeletarDiretorio('./tests');
    await fsDeletarDiretorio('./routes');
    await fsDeletarDiretorio('./views');
    await fsDeletarDiretorio('./composer.json');
    await fsDeletarDiretorio('./' + public);
};

exports.buildPaginaExemplo = async () => {
    if (fs.existsSync('./routes/SiteRoute.php')) {
        return Promise.resolve(true);
    }

    if (config == undefined) {
        config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    const public = config.public;

    await fsCriarDiretorio('./app/Controllers/Site');
    await fsCriarDiretorio('./' + public + '/css');
    await fsCriarDiretorio('./views/pages/site');
    await fsCriarDiretorio('./views/pages/site/exemplo');

    src(['src/Files/exemplo/pages/css/site_exemplo.css'])
        .pipe(plumber())
        .pipe(dest('./' + public + '/css'));
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
