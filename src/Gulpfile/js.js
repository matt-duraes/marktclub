const { src, dest } = require('gulp');
const fs = require('fs');
const concat = require('gulp-concat');
const replace = require('gulp-replace');
const eslint = require('gulp-eslint');
const uglify = require('gulp-uglify-es').default;
const plumber = require('gulp-plumber');
const { arquivoExiste } = require('./validacao.js');
const { mensagemErro, mensagemSucesso } = require('./mensagem');
const glob = require('glob');
const { fsDeletarDiretorio } = require('./arquivo');

let config;
/*
|--------------------------------------------------------------------------
| BUILD
|--------------------------------------------------------------------------
*/
exports.jsDeploy = async function () {
    if (config == undefined) {
        config = await JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    return src(config.public + '/js/**/*.js')
        .pipe(plumber())
        .pipe(uglify())
        .pipe(dest(config.public + '/js'));
};

/*
|--------------------------------------------------------------------------
| HTML
|--------------------------------------------------------------------------
*/
exports.jsUnico = function (path) {
    return new Promise(async resolve => {
        if (config == undefined) {
            config = await JSON.parse(fs.readFileSync('./files/config/gulp.json'));
        }

        let pathAll = path.replace(/\/[a-zA-Z0-9\_\-]+\.js/, '') + '/all.js';
        const pathReal = path.replace(/\/[a-zA-Z0-9\_\-]+\.js/, '') + '/path.js';

        if (await arquivoExiste(pathReal, false)) {
            const conteudo = fs.readFileSync(pathReal, 'utf-8');
            pathAll = conteudo.replace(/^\/\/\ ?/, '').trim();
        }

        if (!(await arquivoExiste(pathAll))) {
            resolve(false);
        }

        try {
            await processarJs(pathAll);
            mensagemSucesso('Arquivo copiado com sucesso: ' + pathAll);
        } catch (error) {
            mensagemErro('Erro ao copiar arquivo: ' + pathAll);
        }
        resolve(true);
    });
};

/*
|--------------------------------------------------------------------------
| TODOS
|--------------------------------------------------------------------------
*/
exports.jsTodos = function () {
    return new Promise(async resolve => {
        if (config == undefined) {
            config = await JSON.parse(fs.readFileSync('./files/config/gulp.json'));
        }

        await fsDeletarDiretorio(config.public + '/js');

        const listaArquivo = glob
            .sync('views/@(pages|templates)/**/all.js')
            .concat(glob.sync('src/Painel/App/**/all.js'));
        const quantidade = listaArquivo.length;
        const ultimo = quantidade - 1;
        let i, arquivo;
        for (i = 0; i < quantidade; ++i) {
            arquivo = listaArquivo[i];
            try {
                await processarJs(arquivo);
                mensagemSucesso('Arquivo copiado com sucesso: ' + arquivo);
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
| FUNÇÕES GERAIS
|--------------------------------------------------------------------------
*/
function processarJs(path) {
    return new Promise(async (resolve, reject) => {
        const dirBase = path.replace(/\/all.js$/, '') + '/';
        const nome = path
            .replace(/^src\/Painel\/App\//, 'painel_')
            .replace(/^views\/(pages\/)?/, '')
            .replace(/\/js\/[a-zA-Z0-9\-\_\.]+\.js/, '')
            .replace(/\/Views/, '')
            .replace(/\//g, '_')
            .replace(/_{2,}/g, '_');
        const conteudo = fs.readFileSync(path, 'utf-8');

        let listaImport = pegarListaImports(conteudo, dirBase);
        if (listaImport) {
            listaImport = listaImport.filter((este, i) => listaImport.indexOf(este) === i);
            listaImport.push(path);
        } else {
            listaImport = [path];
        }

        if (!(await arquivoExiste(listaImport))) {
            return;
        }

        return src(listaImport)
            .pipe(plumber())
            .pipe(
                eslint({
                    rules: {
                        camelcase: 1,
                        semi: 1,
                    },
                    parserOptions: {
                        ecmaVersion: 2017,
                    },
                    env: {
                        es6: true,
                    },
                })
            )
            .pipe(eslint.format())
            .pipe(eslint.failAfterError())
            .pipe(concat(nome + '.js'))
            .pipe(
                replace(/\/\/\ ?(\@template|\@import|\@resource|\@system)(.*)/g, function handleReplace(match) {
                    return '';
                })
            )
            .pipe(dest(config.public + '/js'))
            .on('end', resolve)
            .on('error', reject);
    });
}
// Pegar lista de imports
function pegarListaImports(conteudo, path) {
    if (!/\ ?\/\/\ ?(\@import|\@resource|\@template|\@system)/.test(conteudo)) {
        return false;
    }
    const lista = conteudo
        .replace(/\"|\'|\(|\)/g, '')
        .match(/(\@import|\@resource|\@template|\@system)\ [a-zA-Z0-9\_\-\.\/]+/g);

    let retorno = [];
    const quantidade = lista.length;
    let i, arquivo, item;
    for (i = 0; i < quantidade; ++i) {
        item = lista[i];
        if (/\@import/.test(item)) {
            arquivo = path + item.replace('@import ', '') + '.js';
        } else if (/\@template/.test(item)) {
            arquivo = 'views/templates/' + item.replace('@template ', '') + '/js/all.js';
        } else if (/\@resource/.test(item)) {
            arquivo = 'resources/js/' + item.replace('@resource ', '') + '.js';
        } else if (/\@system/.test(item)) {
            arquivo =
                'src/Html/Scripts/js/' +
                item.replace('@system ', '').replace(/(\.system\.js|\.system|\.js)$/, '') +
                '.system.js';
            if (arquivo == 'src/Html/Scripts/js/Form.system.js') {
                retorno.push('src/Html/Scripts/js/ArquivoUpload.system.js');
                retorno.push('src/Html/Scripts/js/Galeria.system.js');
                retorno.push('src/Html/Scripts/js/Form.init.js');
                retorno.push('src/Html/Scripts/js/Form.tag.js');
                retorno.push('src/Html/Scripts/js/Form.select.js');
                retorno.push('src/Html/Scripts/js/Form.cor.js');
            } else if (arquivo == 'src/Html/Scripts/js/DragDrop.system.js') {
                retorno.push('src/Html/Scripts/js/DragDrop.interno.js');
            } else if (arquivo == 'src/Html/Scripts/js/Alerta.system.js') {
                retorno.push('src/Html/Scripts/js/Player.system.js');
            } else if (arquivo == 'src/Html/Scripts/js/Grafico.system.js') {
                retorno.push('src/Html/Scripts/js/Grafico.interno.js');
                retorno.push('src/Html/Scripts/js/Grafico.realtime.js');
            }
        }
        retorno.push(arquivo);
    }
    return pegarSubImports(retorno);
}

function pegarSubImports(lista) {
    const retorno = [];
    let path, tmp, conteudo;
    [].forEach.call(lista, arquivo => {
        if (
            ((/^views\/templates/.test(arquivo) && /all.js$/.test(arquivo)) ||
                (/^views\/pages/.test(arquivo) && /all.js$/.test(arquivo))) &&
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
