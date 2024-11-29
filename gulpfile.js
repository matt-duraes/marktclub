const fs = require('fs');
const prop = require('yargs').argv;
const { watch, parallel, series } = require('gulp');
const { cssUnico, cssTodos, cssDeploy } = require('./src/Gulpfile/css.js');
const { jsUnico, jsTodos, jsDeploy } = require('./src/Gulpfile/js.js');
const { htmlUnico, htmlTodos, htmlDeploy } = require('./src/Gulpfile/html.js');
const { configVerificar } = require('./src/Gulpfile/config.js');
const { phpCsFixer } = require('./src/Gulpfile/php.js');

const {
    buildCopiarComposerConfig,
    buildComposerInstall,
    buildEnv,
    buildGit,
    buildArquivosRaiz,
    buildArquivoConfigVsCode,
    buildArquivosTeste,
    buildArquivosPublico,
    buildDiretorios,
    buildDocker,
    buildPhpMussel,
    buildPaginaExemplo,
    buildArquivoErro,
    buildCorrigindoComposer,
    buildCopiarIndex,
} = require('./src/Gulpfile/build.js');

const { limparArquivosDoMac, limparSessao } = require('./src/Gulpfile/clean.js');
const { dockerComposerUp, dockerComposerDown } = require('./src/Gulpfile/docker.js');
const { mensagemSucesso } = require('./src/Gulpfile/mensagem.js');

// Subir e parar desenvolvimento
exports.default = series(
    validandoArquivoDeConfiguracao,
    limpandoSessoes,
    subindoContainer,
    monitorarSistema,
    sistemaInicializado
);
exports.down = parallel(matandoContainer, limpandoSessoes);
exports.css = series(copiandoArquivosCSS);
exports.js = series(copiandoArquivosJS);
exports.html = series(copiandoArquivosHtml);
exports.tabela = series(copiandoArquivosCSS);

// Deploy em produção
exports.deploy = series(
    parallel(
        series(copiandoArquivosJS, preparandoJSParaProducao),
        series(copiandoArquivosHtml, preparandoHtmlParaProducao)
    ),
    series(copiandoArquivosCSS, preparandoCSSParaProducao)
);

// Instalar o framework
exports.install = series(
    verificarSePrecisaConfigurar,
    copiandoArquivoParaGit,
    copiandoArquivosDaRaiz,
    copiandoArquivoConfigDoVsCode,
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
    parallel(copiandoArquivosJS, copiandoArquivosHtml),
    copiandoArquivosCSS
);

// Executa ao dar commit
exports.commit = series(limpandoArquivosDoMac);

// Build projeto em desenvolvimento
exports.build = parallel(copiandoArquivoIndex, copiandoArquivosJS, copiandoArquivosHtml, copiandoArquivosCSS);
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
    // PHP CS FIXER
    watch(['**/*.php', '!./files/**/*.php', '!**/*Route.php']).on('change', async path => {
        const time = new Date().getTime();
        consoleHeader('php-fix');
        await phpCsFixer(path);
        consoleFooter(time);
    });

    // CSS
    watch(['./views/**/*.styl', './src/Painel/templates/**/*.styl', './src/Painel/App/**/*.styl']).on(
        'change',
        async path => {
            const time = new Date().getTime();
            consoleHeader('styl');
            await cssUnico(path);
            consoleFooter(time);
        }
    );

    // JS
    watch(['./views/**/*.js', './src/Painel/templates/**/*.js', './src/Painel/App/**/*.js']).on(
        'change',
        async path => {
            const time = new Date().getTime();
            consoleHeader('js');
            console.log(path);
            await jsUnico(path);
            consoleFooter(time);
        }
    );

    // HTML
    watch(['./views/**/*.view', './src/Painel/templates/**/*.view', './src/Painel/App/**/*.view']).on(
        'change',
        async path => {
            const time = new Date().getTime();
            consoleHeader('view');
            await htmlUnico(path);
            consoleFooter(time);
        }
    );
}

function sistemaInicializado() {
    mensagemSucesso('Sistema inicializado com sucesso...');
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

function criandoPaginaExemplo() {
    return buildPaginaExemplo();
}
