const browserSync = require('browser-sync').create();
const open = require('open');
const fs = require('fs');
const prop = require('yargs').argv;
const { watch, parallel, series } = require('gulp');
const { cssUnico, cssTodos, cssDeploy, cssProducao } = require('./src/Gulpfile/css.js');
const { jsUnico, jsValidar, jsTodos, jsDeploy, jsProducao } = require('./src/Gulpfile/js.js');
const { htmlUnico, htmlTodos, htmlDeploy, htmlProducao } = require('./src/Gulpfile/html.js');
const { imagemTodos } = require('./src/Gulpfile/imagem.js');
const { configVerificar } = require('./src/Gulpfile/config.js');
const {
    buildCopiarComposerConfig,
    buildComposerInstall,
    buildEnv,
    buildGit,
    buildArquivosRaiz,
    buildArquivosTeste,
    buildArquivosPublico,
    buildDiretorios,
    buildDocker,
    buildPhpMussel,
    buildBaixandoUpdate,
    buildCopiandoUpdate,
    buildLimparFramework,
    buildPaginaExemplo,
    buildArquivoErro,
    buildCorrigindoComposer,
    buildDefineTabela,
} = require('./src/Gulpfile/build.js');
const { limparArquivosDoMac, limparSessao, limparDeploy } = require('./src/Gulpfile/clean.js');
const { dockerComposerUp, dockerComposerDown } = require('./src/Gulpfile/docker.js');

// Subir e parar desenvolvimento
exports.default = series(validandoArquivoDeConfiguracao, limpandoSessoes, subindoContainer, monitorarSistema);
exports.down = parallel(matandoContainer, limpandoSessoes);

// Atualiza o framework
exports.update = series(fazerDownloadDoProjeto);
exports.upgrade = series(
    instalandoDownloadDoProjeto,
    copiandoArquivoDoComposer,
    executandoComposerInstall,
    parallel(corrigindoBugDoComposer, copiandoArquivosDaRaiz, copiandoArquivoDeErro, copiandoArquivosDeteste)
);

// Limpa o framework
exports.clearFramework = series(limpandoFramework);

exports.build = parallel(
    series(copiandoArquivosCSS, colocandoCssEmProducao),
    series(copiandoArquivosJS, colocandoJsEmProducao),
    series(copiandoArquivosHtml, colocandoHtmlEmProducao),
    copiandoArquivosDeImagem,
    criandoDefineTabela
);
exports.js = series(copiandoArquivosJS, colocandoJsEmProducao);
exports.css = series(copiandoArquivosCSS, colocandoCssEmProducao);
exports.html = series(copiandoArquivosHtml, colocandoHtmlEmProducao);
exports.imagem = series(copiandoArquivosDeImagem);
exports.tabela = series(criandoDefineTabela);
exports.teste = series(preparandoJSParaProducao);

// Instalar o framework
exports.install = series(
    verificarSePrecisaConfigurar,
    copiandoArquivoParaGit,
    copiandoArquivosDaRaiz,
    copiandoArquivosDeteste,
    copiandoArquivosPublicos,
    parallel(
        series(copiandoArquivoDoComposer, executandoComposerInstall, corrigindoBugDoComposer),
        copiandoArquivoParaDocker,
        criandoDiretorios,
        copiandoArquivoParaEnv,
        copiandoArquivoParaPhpMussel
    ),
    copiandoArquivoDeErro,
    criandoPaginaExemplo,
    parallel(
        series(copiandoArquivosCSS, colocandoCssEmProducao),
        series(copiandoArquivosJS, colocandoJsEmProducao),
        series(copiandoArquivosHtml, colocandoHtmlEmProducao),
        copiandoArquivosDeImagem,
        criandoDefineTabela
    )
);

// Executa ao dar commit
exports.commit = series(limpandoArquivosDoMac);

// Build projeto em desenvolvimento
exports.composerBugfix = series(corrigindoBugDoComposer);

/*
|--------------------------------------------------------------------------
| FUNÇÕES DO FULP
|--------------------------------------------------------------------------
*/
function limpandoBuildDoDeploy() {
    return limparDeploy();
}
function validandoArquivoDeConfiguracao() {
    if (!fs.existsSync('./files/config/gulp.json')) {
        console.log('Execute "\x1b[32m\x1b[1mgulp install\033[0m" para poder configurar o projeto.');
        console.log('');
        return;
    }
    return Promise.resolve();
}

function corrigindoBugDoComposer() {
    return buildCorrigindoComposer();
}

async function fazerDownloadDoProjeto() {
    return buildBaixandoUpdate();
}

function instalandoDownloadDoProjeto() {
    return buildCopiandoUpdate();
}

function subindoContainer() {
    return dockerComposerUp();
}

function matandoContainer() {
    return dockerComposerDown();
}

function limpandoFramework() {
    return buildLimparFramework();
}

function limpandoSessoes() {
    if (prop.clean == undefined) {
        return limparSessao();
    }
    return Promise.resolve('Limpeza de sessão ignorada.');
}

async function monitorarSistema() {
    const config = JSON.parse(fs.readFileSync('./files/config/gulp.json'));

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

    // JS
    watch('./views/pages/**/*.js').on('change', async path => {
        const time = new Date().getTime();
        consoleHeader();
        await jsValidar(path);
        await jsUnico(path);
        browserSync.reload();
        consoleFooter(time);
    });

    // HTML
    watch('./views/pages/**/*.view').on('change', async path => {
        const time = new Date().getTime();
        consoleHeader();
        await htmlUnico(path);
        browserSync.reload();
        consoleFooter(time);
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

function verificarSePrecisaConfigurar() {
    return configVerificar();
}

function copiandoArquivoDoComposer() {
    return buildCopiarComposerConfig();
}

function executandoComposerInstall() {
    return buildComposerInstall();
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

function copiandoArquivosDeteste() {
    return buildArquivosTeste();
}

function copiandoArquivosPublicos() {
    return buildArquivosPublico();
}

function copiandoArquivoDeErro() {
    return buildArquivoErro();
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

function limpandoArquivosDoMac() {
    return limparArquivosDoMac();
}

function copiandoArquivosCSS() {
    return cssTodos();
}

function preparandoCSSParaProducao() {
    return cssDeploy();
}
function colocandoCssEmProducao() {
    return cssProducao();
}
function copiandoArquivosJS() {
    return jsTodos();
}

function preparandoJSParaProducao() {
    return jsDeploy();
}
function colocandoJsEmProducao() {
    return jsProducao();
}

function copiandoArquivosHtml() {
    return htmlTodos();
}
function preparandoHtmlParaProducao() {
    return htmlDeploy();
}
function colocandoHtmlEmProducao() {
    return htmlProducao();
}

function copiandoArquivosDeImagem() {
    return imagemTodos();
}

function criandoDefineTabela() {
    return buildDefineTabela();
}

function criandoPaginaExemplo() {
    return buildPaginaExemplo();
}
