const { src, dest } = require('gulp');
const fs = require('fs');
const concat = require('gulp-concat');
const replace = require('gulp-replace');
const eslint = require('gulp-eslint');
const uglify = require('gulp-uglify-es').default;
const plumber = require('gulp-plumber');
const { arquivoExiste, inArray } = require('./Helper.js');
const { mensagemErro, mensagemSucesso } = require('./mensagem');
const glob = require('glob');
const { fsDeletarDiretorio, fsCriarArquivo, fsCriarDiretorio, fsRemoverArquivoSeExistir } = require('./arquivo');

let arquivoConteudo = [];
let config;

/*
|--------------------------------------------------------------------------
| HTML
|--------------------------------------------------------------------------
*/
exports.jsUnico = function (path) {
    return new Promise(async resolve => {
        arquivoConteudo = [];

        if (config == undefined) {
            config = await JSON.parse(fs.readFileSync('./files/config/gulp.json'));
        }

        const pathReal = path.replace(/\/[a-zA-Z0-9\_\-]+\.js/, '') + '/all.js';
        const nome = pegarNomeArquivo(pathReal);

        if (!(await arquivoExiste(pathReal))) {
            mensagemErro('Arquivo não existe: ' + pathReal);
            resolve(false);
        }

        await fsRemoverArquivoSeExistir('files/build/js/' + nome);
        await fsRemoverArquivoSeExistir(config.public + '/js/' + nome);

        try {
            await processarJs(pathReal, config.public + '/js');
            mensagemSucesso('Arquivo copiado com sucesso: ' + pathReal);
        } catch (error) {
            mensagemErro('Erro ao copiar arquivo: ' + pathReal);
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

        arquivoConteudo = [];

        await fsDeletarDiretorio('./files/build/js');
        await fsCriarDiretorio('./files');
        await fsCriarDiretorio('./files/build');
        await fsCriarDiretorio('./files/build/js');

        const listaArquivo = glob
            .sync('views/@(pages|templates)/**/all.js')
            .concat(glob.sync('src/Painel/App/**/all.js'))
            .concat(glob.sync('src/Painel/template/**/all.js'));

        const quantidade = listaArquivo.length;
        const ultimo = quantidade - 1;
        let i, arquivo;
        for (i = 0; i < quantidade; ++i) {
            arquivo = listaArquivo[i];
            try {
                await processarJs(arquivo, config.public + '/js');
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
exports.jsDeploy = async function () {
    if (config == undefined) {
        config = await JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    return src(config.public + '/js/*.js')
        .pipe(plumber())
        .pipe(uglify())
        .pipe(dest(config.public + '/js'));
};

/*
|--------------------------------------------------------------------------
| PRODUÇÃO
|--------------------------------------------------------------------------
*/
exports.jsProducao = async () => {
    if (config == undefined) {
        config = await JSON.parse(fs.readFileSync('./files/config/gulp.json'));
    }
    await fsDeletarDiretorio(config.public + '/js');
    await new Promise(r => setTimeout(r, 2000));

    return src('./files/build/js/*.js')
        .pipe(plumber())
        .pipe(dest(config.public + '/js'));
};
exports.jsValidar = async path => {
    return src(path)
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
        .pipe(eslint.failAfterError());
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
            .replace(/\/js\/[a-zA-Z0-9\-\_\.]+\.js/, '')
            .replace(/\/Views/, '')
            .replace(/\//g, '_')
            .replace(/_{2,}/g, '_') + '.js'
    );
}
function processarJs(path, destino) {
    return new Promise(async (resolve, reject) => {
        const dirBase = path.replace(/\/all.js$/, '') + '/';
        const nome = pegarNomeArquivo(path);

        let conteudo = '';
        if (arquivoConteudo[path]) {
            conteudo = arquivoConteudo[path];
        } else {
            conteudo = fs.readFileSync(path, 'utf-8');
            arquivoConteudo[path] = conteudo;
        }
        let listaImport = pegarListaImports(conteudo, dirBase);
        if (listaImport) {
            listaImport = listaImport.filter((este, i) => listaImport.indexOf(este) === i);
            listaImport.unshift('src/Html/Scripts/js/Alerta.system.js', 'src/Html/Scripts/js/Funcao.system.js');
            listaImport.push(path);
        } else {
            listaImport = ['src/Html/Scripts/js/Alerta.system.js', 'src/Html/Scripts/js/Funcao.system.js', path];
        }

        listaImport = removerImportDuplicado(listaImport);

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

        await fsCriarArquivo('files/build/js/' + nome, conteudoFinal);

        return src('files/build/js/' + nome)
            .pipe(plumber())
            .pipe(
                replace(
                    /\/\/\ ?(\@template|\@painel|\@import|\@resource|\@system)(.*)/g,
                    function handleReplace(match) {
                        return '';
                    }
                )
            )
            .pipe(dest(destino))
            .on('end', resolve)
            .on('error', reject);
    });
}
function removerImportDuplicado(path) {
    const arquivo = [];
    path.forEach(function (item) {
        if (arquivo.indexOf(item) < 0) {
            arquivo.push(item);
        }
    });
    return arquivo;
}
// Pegar lista de imports
function pegarListaImports(conteudo, path) {
    if (!/\ ?\/\/\ ?(\@import|\@painel|\@resource|\@template|\@system)/.test(conteudo)) {
        return false;
    }
    const lista = conteudo
        .replace(/\"|\'|\(|\)/g, '')
        .match(/(\@import|\@painel|\@resource|\@template|\@system)\ [a-zA-Z0-9\_\-\.\/]+/g);

    let retorno = [];
    const quantidade = lista.length;
    let i, arquivo, item;
    for (i = 0; i < quantidade; ++i) {
        item = lista[i];
        if (/\@import/.test(item)) {
            arquivo = path + item.replace('@import ', '') + '.js';
        } else if (/\@template ?(\"|\')?painel/.test(item)) {
            arquivo = 'src/Painel/template/js/all.js';
        } else if (/\@template/.test(item)) {
            arquivo = 'views/templates/' + item.replace('@template ', '') + '/js/all.js';
        } else if (/\@resource/.test(item)) {
            arquivo = 'resources/js/' + item.replace('@resource ', '') + '.js';
        } else if (/\@painel/.test(item)) {
            arquivo = 'src/Painel/js/' + item.replace('@painel ', '') + '.js';
        } else if (/\@system/.test(item)) {
            arquivo =
                'src/Html/Scripts/js/' +
                item.replace('@system ', '').replace(/(\.system\.js|\.system|\.js)$/, '') +
                '.system.js';
            if (arquivo == 'src/Html/Scripts/js/Form.system.js') {
                if (!inArray('src/Html/Scripts/js/ArquivoUpload.system.js', retorno)) {
                    retorno.push('src/Html/Scripts/js/ArquivoUpload.system.js');
                }
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
            } else if (arquivo == 'src/Html/Scripts/js/Editor.system.js') {
                if (!inArray('src/Html/Scripts/js/ArquivoUpload.system.js', retorno)) {
                    retorno.push('src/Html/Scripts/js/ArquivoUpload.system.js');
                }
                retorno.push('src/Html/Scripts/js/Editor.init.js');
                retorno.push('src/Html/Scripts/js/Ckeditor.system.js');
                retorno.push('src/Html/Scripts/js/Editor.system.js');
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
                (/^views\/pages/.test(arquivo) && /all.js$/.test(arquivo)) ||
                (/^src\/Painel\/template/.test(arquivo) && /all.js$/.test(arquivo))) &&
            fs.existsSync(arquivo)
        ) {
            path = arquivo.split('/');
            path.pop();
            path = path.join('/') + '/';

            if (arquivoConteudo[arquivo]) {
                conteudo = arquivoConteudo[arquivo];
            } else {
                conteudo = fs.readFileSync(arquivo, 'utf-8');
                arquivoConteudo[arquivo] = conteudo;
            }

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
