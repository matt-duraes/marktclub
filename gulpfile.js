const browserSync = require('browser-sync').create();
const open = require('open');
const fs = require('fs');
const prop = require('yargs').argv;
const { watch, parallel, series } = require('gulp');
const { cssUnico, cssTodos, cssDeploy } = require('./src/Gulpfile/css.js');
const { jsUnico, jsTodos, jsDeploy } = require('./src/Gulpfile/js.js');
const { htmlUnico, htmlTodos, htmlDeploy } = require('./src/Gulpfile/html.js');
const { imagemTodos } = require('./src/Gulpfile/imagem.js');
const { configVerificar } = require('./src/Gulpfile/config.js');
const {
    buildComposer,
    buildEnv,
    buildGit,
    buildArquivosRaiz,
    buildArquivosPublico,
    buildDiretorios,
    buildDocker,
    buildPhpMussel,
} = require('./src/Gulpfile/build.js');
const { limparArquivosDoMac, limparSessao } = require('./src/Gulpfile/clean.js');
const { dockerComposerUp, dockerComposerDown } = require('./src/Gulpfile/docker.js');

/*
|--------------------------------------------------------------------------
| TAREFAS DE DESENVOLVIMENTO
|--------------------------------------------------------------------------
|
| Monitora as ações do desenvolvedor para otimizar seu trabalho
|
*/
exports.default = series(validandoArquivoDeConfiguracao, limpandoSessoes, subindoContainer, monitorarSistema);
exports.down = parallel(matandoContainer, limpandoSessoes);

function validandoArquivoDeConfiguracao() {
    if (!fs.existsSync('./src/Gulpfile/gulp.json')) {
        console.log('Execute "\x1b[32m\x1b[1mgulp install --config\033[0m" para poder configurar o projeto.');
        console.log('');
        return;
    }
    return Promise.resolve();
}

function subindoContainer() {
    return dockerComposerUp();
}

function matandoContainer() {
    return dockerComposerDown();
}

function limpandoSessoes() {
    if (prop.clean == undefined) {
        return limparSessao();
    }
    return Promise.resolve('Limpeza de sessão ignorada.');
}

async function monitorarSistema() {
    const config = JSON.parse(fs.readFileSync('./src/Gulpfile/gulp.json'));

    // RELOAD
    const proxyPorta = config.browserSync.porta;
    const proxyUrl = config.browserSync.proxy + ':' + config.docker.https;

    await browserSync.init({
        proxy: proxyUrl,
        open: false,
        port: proxyPorta,
    });

    if (prop.open == undefined) {
        await open(config.browserSync.open + ':' + proxyPorta);
    }

    // CSS
    watch('./views/pages/**/*.styl').on('change', async path => {
        const time = new Date().getTime();
        consoleHeader();
        await cssUnico(path, browserSync);
        consoleFooter(time);
    });
    watch(['./views/templates/**/*.styl', './resources/css/**/*.styl', './src/Html/Scripts/css/*.styl']).on(
        'change',
        async () => {
            const time = new Date().getTime();
            consoleHeader();
            await cssTodos();
            browserSync.reload();
            consoleFooter(time);
        }
    );

    // JS
    watch('./views/pages/**/*.js').on('change', async path => {
        const time = new Date().getTime();
        consoleHeader();
        await jsUnico(path);
        browserSync.reload();
        consoleFooter(time);
    });
    watch(['./views/templates/**/*.js', './resources/js/**/*.js', './src/Html/Scripts/js/*.js']).on(
        'change',
        async () => {
            const time = new Date().getTime();
            consoleHeader();
            await jsTodos();
            browserSync.reload();
            consoleFooter(time);
        }
    );

    // HTML
    watch('./views/pages/**/*.view').on('change', async path => {
        const time = new Date().getTime();
        consoleHeader();
        await htmlUnico(path);
        browserSync.reload();
        consoleFooter(time);
    });
    watch(['./views/templates/**/*.view', './resources/php/**/*.php']).on('change', async () => {
        const time = new Date().getTime();
        consoleHeader();
        await htmlTodos();
        browserSync.reload();
        consoleFooter(time);
    });
    watch(['./src/**/*.php', '!./src/Database/tabela.php']).on('change', () => {
        browserSync.reload();
    });

    // IMAGEM
    watch(['./views/images/**/*']).on('all', async () => {
        const time = new Date().getTime();
        consoleHeader();
        await imagemTodos();
        consoleFooter(time);
    });
}

function consoleHeader() {
    console.log('Processando ... ');
}

function consoleFooter(time) {
    const gasto = new Date().getTime() - time;
    console.log('Processo concluido em ' + gasto + 'ms');
    console.log('');
}

/*
|--------------------------------------------------------------------------
| BUILD INICIAL
|--------------------------------------------------------------------------
|
| Deve ser executado ao fazer o pull/clone do projeto
|
*/
exports.install = series(
    verificarSePrecisaConfigurar,
    copiandoArquivoParaGit,
    copiandoArquivosDaRaiz,
    copiandoArquivosPublicos,
    parallel(
        executandoComposerInstall,
        copiandoArquivoParaDocker,
        criandoDiretorios,
        copiandoArquivoParaEnv,
        copiandoArquivoParaPhpMussel,
        copiandoArquivosCSS,
        copiandoArquivosJS,
        copiandoArquivosHtml,
        copiandoArquivosDeImagem
    )
);

function verificarSePrecisaConfigurar() {
    return configVerificar();
}

function executandoComposerInstall() {
    return buildComposer();
}

function copiandoArquivoParaDocker() {
    return buildDocker();
}

function criandoDiretorios() {
    return buildDiretorios();
}
function copiandoArquivosDaRaiz() {
    return buildArquivosRaiz();
}
function copiandoArquivosPublicos() {
    return buildArquivosPublico();
}

function copiandoArquivoParaEnv() {
    return buildEnv();
}

function copiandoArquivoParaGit() {
    return buildGit();
}

function copiandoArquivoParaPhpMussel() {
    return buildPhpMussel();
}

/*
|--------------------------------------------------------------------------
| DEPLOY AO COMMITAR
|--------------------------------------------------------------------------
|
| Executa uma limpeza e converter os arquivos para a versão
| de produção assim que um commit é iniciado
|
*/
exports.deploy = series(
    limpandoArquivosDoMac,
    parallel(
        series(copiandoArquivosCSS, preparandoCSSParaProducao),
        series(copiandoArquivosJS, preparandoJSParaProducao),
        series(copiandoArquivosHtml, preparandoHtmlParaProducao),
        copiandoArquivosDeImagem
    )
);
exports.build = parallel(
    series(copiandoArquivosCSS, preparandoCSSParaProducao),
    series(copiandoArquivosJS, preparandoJSParaProducao),
    series(copiandoArquivosHtml, preparandoHtmlParaProducao),
    copiandoArquivosDeImagem
);

function limpandoArquivosDoMac() {
    return limparArquivosDoMac();
}

function copiandoArquivosCSS() {
    return cssTodos();
}

function preparandoCSSParaProducao() {
    return cssDeploy();
}

function copiandoArquivosJS() {
    return jsTodos();
}

function preparandoJSParaProducao() {
    return jsDeploy();
}

function copiandoArquivosHtml() {
    return htmlTodos();
}

function preparandoHtmlParaProducao() {
    return htmlDeploy();
}

function copiandoArquivosDeImagem() {
    return imagemTodos();
}
