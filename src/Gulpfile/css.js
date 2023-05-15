const { src, dest } = require('gulp');
const fs = require('fs');
const replace = require('gulp-replace');
const stylus = require('gulp-stylus');
const concat = require('gulp-concat');
const autoprefixer = require('gulp-autoprefixer');
const cssMin = require('gulp-cssmin');
const plumber = require('gulp-plumber');
const { arquivoExiste } = require('./Helper.js');
const { mensagemErro, mensagemSucesso } = require('./mensagem');
const glob = require('glob');
const { fsDeletarDiretorio, fsCriarArquivo, fsCriarDiretorio, fsRemoverArquivoSeExistir } = require('./arquivo');

let arquivoConteudo = [];
let config;

/*
|--------------------------------------------------------------------------
| CSS ÚNICO
|--------------------------------------------------------------------------
*/
exports.cssUnico = function (path, browser) {
    return new Promise(async resolve => {
        if (config == undefined) {
            config = await JSON.parse(fs.readFileSync('./files/config/gulp.json'));
        }

        const pathReal = path.replace(/\/[a-zA-Z0-9\_\-]+\.styl/, '') + '/layout.styl';
        const nome = pegarNomeArquivo(pathReal);

        if (!(await arquivoExiste(pathReal))) {
            mensagemErro('Arquivo não existe: ' + pathReal);
            resolve(false);
        }

        await fsRemoverArquivoSeExistir('files/build/css/' + nome);
        await fsRemoverArquivoSeExistir(config.public + '/css/' + nome);

        try {
            await processarCss(pathReal, config.public + '/css', browser);
            mensagemSucesso('Arquivo copiado com sucesso: ' + pathReal);
        } catch (error) {
            console.log(error);
            mensagemErro('Erro ao copiar arquivo: ' + pathReal);
            resolve(false);
        }
        resolve(true);
    });
};

/*
|--------------------------------------------------------------------------
| TODOS
|--------------------------------------------------------------------------
*/
exports.cssTodos = function () {
    return new Promise(async resolve => {
        await fsDeletarDiretorio('./files/build/css');
        await fsCriarDiretorio('./files/build/css');

        const listaArquivo = glob
            .sync('views/@(pages|templates)/**/layout.styl')
            .concat(glob.sync('src/Painel/App/**/layout.styl'));

        const quantidade = listaArquivo.length;
        const ultimo = quantidade - 1;
        let i, arquivo;
        for (i = 0; i < quantidade; ++i) {
            arquivo = listaArquivo[i];
            try {
                await processarCss(arquivo, './files/build/css');
            } catch (error) {
                mensagemErro('Erro ao copiar arquivo: ' + arquivo);
            }
            if (i == ultimo) {
                resolve(true);
            }
        }
        resolve(true);
    });
};

/*
|--------------------------------------------------------------------------
| BUILD
|--------------------------------------------------------------------------
*/
exports.cssDeploy = async function () {
    return src('./files/build/css/*.css')
        .pipe(plumber())
        .pipe(autoprefixer())
        .pipe(cssMin())
        .pipe(dest('./files/build/css'));
};

/*
|--------------------------------------------------------------------------
| PRODUCAO
|--------------------------------------------------------------------------
*/
exports.cssProducao = async () => {
    if (config == undefined) {
        config = await JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }

    await fsDeletarDiretorio(config.public + '/css');
    await new Promise(r => setTimeout(r, 2000));

    return src('./files/build/css/*.css')
        .pipe(plumber())
        .pipe(dest(config.public + '/css'));
};

/*
|--------------------------------------------------------------------------
| FUNÇÕES GERAIS
|--------------------------------------------------------------------------
*/
function pegarNomeArquivo(path) {
    return (
        path
            .replace(/^src\/Painel\/App\//, 'painel_')
            .replace(/^views\/(pages\/)?/, '')
            .replace(/\/css\/[a-zA-Z0-9\-\_\.]+\.styl/, '')
            .replace(/\/Views/, '')
            .replace(/\//g, '_')
            .replace(/_{2,}/g, '_') + '.styl'
    );
}
async function processarCss(path, destino, browser) {
    const dirBase = path.replace(/\/layout.styl$/, '') + '/';
    const nome = pegarNomeArquivo(path);

    const conteudo = fs.readFileSync(path, 'utf-8');
    let listaImport = pegarListaImports(conteudo, dirBase);
    if (listaImport) {
        listaImport = listaImport.filter((este, i) => listaImport.indexOf(este) === i);
        listaImport.unshift('src/Html/Scripts/css/Variavel.system.styl');
    } else {
        listaImport = ['src/Html/Scripts/css/Variavel.system.styl'];
    }

    if (!(await arquivoExiste(listaImport))) {
        return;
    }

    let conteudoTemp = '';
    let conteudoFinal = '';
    [].forEach.call(listaImport, arquivo => {
        if (arquivoConteudo[arquivo]) {
            conteudoTemp = arquivoConteudo[arquivo];
        } else {
            conteudoTemp = fs.readFileSync(arquivo, 'utf-8');
            arquivoConteudo[arquivo] = conteudoTemp;
        }
        conteudoFinal += conteudoTemp + '\n';
    });
    conteudoFinal += conteudo;

    await fsCriarArquivo('files/build/css/' + nome, conteudoFinal);

    if (browser != undefined) {
        return src('files/build/css/' + nome)
            .pipe(plumber())
            .pipe(
                replace(/(\@template(.*)|\@import(.*)|\@resource(.*)|\@system(.*))/g, function handleReplace(match) {
                    return '';
                })
            )
            .pipe(
                stylus({
                    'include css': true,
                })
            )
            .pipe(dest(destino))
            .pipe(browser.stream());
    } else {
        return src('files/build/css/' + nome)
            .pipe(plumber())
            .pipe(
                replace(/(\@template(.*)|\@import(.*)|\@resource(.*)|\@system(.*))/g, function handleReplace(match) {
                    return '';
                })
            )
            .pipe(
                stylus({
                    'include css': true,
                })
            )
            .pipe(dest(destino));
    }
}
// Pegar lista de imports
function pegarListaImports(conteudo, path) {
    if (!/\@import|\@resource|\@template|\@system/.test(conteudo)) {
        return false;
    }
    const lista = conteudo
        .replace(/\"|\'|\(|\)/g, '')
        .match(/(\@import|\@resource|\@template|\@system)\ [a-zA-Z0-9\_\-\.\/]+/g);

    let retorno = [];
    const quantidade = lista.length;
    let i, arquivo;
    for (i = 0; i < quantidade; ++i) {
        arquivo = lista[i];
        if (/\@import/.test(arquivo)) {
            retorno.push(path + arquivo.replace('@import ', '') + '.styl');
        } else if (/\@template/.test(arquivo)) {
            retorno.push('views/templates/' + arquivo.replace('@template ', '') + '/css/layout.styl');
        } else if (/\@resource/.test(arquivo)) {
            retorno.push('resources/css/' + arquivo.replace('@resource ', '') + '.styl');
        } else if (/\@system/.test(arquivo)) {
            retorno.push(
                'src/Html/Scripts/css/' +
                    arquivo.replace('@system ', '').replace(/(\.system\.js|\.system|\.js)$/, '') +
                    '.system.styl'
            );
        }
    }
    return pegarSubImports(retorno);
}

function pegarSubImports(lista) {
    const retorno = [];
    let path, tmp, conteudo;
    [].forEach.call(lista, arquivo => {
        if (
            ((/^views\/templates/.test(arquivo) && /layout.styl$/.test(arquivo)) ||
                (/^views\/pages/.test(arquivo) && /layout.styl$/.test(arquivo))) &&
            fs.existsSync(arquivo)
        ) {
            path = arquivo.split('/');
            path.pop();
            path = path.join('/') + '/';
            conteudo = fs.readFileSync(arquivo, 'utf-8');
            tmp = pegarListaImports(conteudo, path);
            if (false !== tmp) {
                [].forEach.call(tmp, subArquivo => {
                    retorno.push(subArquivo);
                });
            }
        }
        retorno.push(arquivo);
    });
    return retorno;
}
