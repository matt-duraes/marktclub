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
const { phpCsFixer } = require('./src/Gulpfile/php.js');

const {
    buildCopiarComposerConfig,
    buildComposerInstall,
    buildEnv,
    buildGit,
    buildArquivosRaiz,
    buildArquivoConfigVsCode,
    buildArquivoConfigGithub,
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
    buildCopiarIndex,
} = require('./src/Gulpfile/build.js');
const { limparArquivosDoMac, limparSessao } = require('./src/Gulpfile/clean.js');
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
    parallel(
        corrigindoBugDoComposer,
        copiandoArquivosDaRaiz,
        copiandoArquivoConfigDoVsCode,
        copiandoArquivoConfigDoGithub,
        copiandoArquivoDeErro,
        copiandoArquivosDeteste
    )
);

exports.css = series(copiandoArquivosCSS);
exports.js = series(copiandoArquivosJS);
exports.html = series(copiandoArquivosHtml);
exports.tabela = series(copiandoArquivosCSS);

// Limpa o framework
exports.clearFramework = series(limpandoFramework);

// Deploy em produção
exports.deploy = series(
    parallel(
        series(copiandoArquivosJS, preparandoJSParaProducao),
        series(copiandoArquivosHtml, preparandoHtmlParaProducao),
        series(copiandoArquivosDeImagem)
    ),
    series(copiandoArquivosCSS, preparandoCSSParaProducao)
);

// Instalar o framework
exports.install = series(
    verificarSePrecisaConfigurar,
    copiandoArquivoParaGit,
    copiandoArquivosDaRaiz,
    copiandoArquivoConfigDoVsCode,
    copiandoArquivoConfigDoGithub,
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
    parallel(copiandoArquivosJS, copiandoArquivosHtml, copiandoArquivosDeImagem),
    copiandoArquivosCSS
);

// Executa ao dar commit
exports.commit = series(limpandoArquivosDoMac);

// Build projeto em desenvolvimento
exports.build = series(
    parallel(copiandoArquivoIndex, copiandoArquivosJS, copiandoArquivosHtml, copiandoArquivosDeImagem),
    copiandoArquivosCSS
);
exports.composerBugfix = series(corrigindoBugDoComposer);

/*
|--------------------------------------------------------------------------
| FUNÇÕES DO FULP
|--------------------------------------------------------------------------
*/
function validandoArquivoDeConfiguracao() {
    if (!fs.existsSync('./files/config/gulp.json')) {
        console.log('Execute "\x1b[32m\x1b[1mgulp install\x1b[0m" para poder configurar o projeto.');
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

    // PHP CS FIXER
    watch(['**/*.php', '!**/*Route.php']).on('change', async path => {
        const time = new Date().getTime();
        consoleHeader('php-fix');
        await phpCsFixer(path);
        consoleFooter(time);
    });

    // CSS
    watch('./views/pages/**/*.styl').on('change', async path => {
        const time = new Date().getTime();
        consoleHeader('styl');
        await cssUnico(path, browserSync);
        consoleFooter(time);
    });

    // JS
    watch('./views/pages/**/*.js').on('change', async path => {
        const time = new Date().getTime();
        consoleHeader('js');
        await jsUnico(path);
        browserSync.reload();
        consoleFooter(time);
    });

    // HTML
    watch(['./views/pages/**/*.view', './src/Painel/App/**/*.view']).on('change', async path => {
        const time = new Date().getTime();
        consoleHeader('view');
        await htmlUnico(path);
        browserSync.reload();
        consoleFooter(time);
    });

    // IMAGEM
    watch(['./views/images/**/*']).on('all', async () => {
        const time = new Date().getTime();
        consoleHeader('imagem');
        await imagemTodos();
        consoleFooter(time);
    });
}

function consoleHeader(acao) {
    acao = acao == undefined ? '' : acao;
    console.log('Processando ' + acao + ' ... ');
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
function copiandoArquivoConfigDoVsCode() {
    return buildArquivoConfigVsCode();
}
function copiandoArquivoConfigDoGithub() {
    return buildArquivoConfigGithub();
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

function copiandoArquivosJS() {
    return jsTodos();
}
function copiandoArquivoIndex() {
    return buildCopiarIndex();
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
function criandoPaginaExemplo() {
    return buildPaginaExemplo();
}
