const { src, dest } = require('gulp');
const htmlMin = require('gulp-htmlmin');
const glob = require('glob');
const fs = require('fs');
const plumber = require('gulp-plumber');
const { mensagemErro, mensagemSucesso } = require('./mensagem');
const {
    fsVerificarSeArquivoExiste,
    fsCriarDiretorio,
    fsDeletarDiretorio,
    fsCriarArquivo,
    fsPegarConteudo,
    fsRemoverArquivoSeExistir,
} = require('./arquivo');

/*
|--------------------------------------------------------------------------
| BUILD
|--------------------------------------------------------------------------
*/
exports.htmlDeploy = function () {
    return src('files/build/html/**/*.php')
        .pipe(plumber())
        .pipe(htmlMin({ collapseWhitespace: true }))
        .pipe(dest('files/build/html/'));
};

/*
|--------------------------------------------------------------------------
| PRODUÇÃO
|--------------------------------------------------------------------------
*/
exports.htmlProducao = async () => {
    await fsDeletarDiretorio('files/build/views');
    await new Promise(r => setTimeout(r, 2000));

    return src('./files/build/html/*.php').pipe(plumber()).pipe(dest('./files/build/views'));
};

/*
|--------------------------------------------------------------------------
| RENDERIZA UMA VIEW
|--------------------------------------------------------------------------
*/
exports.htmlUnico = function (path) {
    return new Promise(async resolve => {
        const arquivo = path;
        const nome = arquivo
            .replace(/views\/pages\//, '')
            .replace(/\/index\.view$/, '.php')
            .replace(/.view$/, '.php')
            .replace(/\/Views/, '')
            .replace(/\//g, '_')
            .replace(/_{2,}/g, '_');

        await fsRemoverArquivoSeExistir('files/build/views/' + nome);
        await fsCriarDiretorio('files/build');
        await fsCriarDiretorio('files/build/views');

        try {
            const retorno = await processarHtml(arquivo, nome, 'files/build/views');
            mensagemSucesso(retorno);
        } catch (error) {
            mensagemErro(error);
        }
        resolve(true);
    });
};

/*
|--------------------------------------------------------------------------
| RENDERIZA TODAS AS VIEWS
|--------------------------------------------------------------------------
*/
exports.htmlTodos = function () {
    return new Promise(async resolve => {
        await fsCriarDiretorio('files/build');
        await fsDeletarDiretorio('files/build/html');
        await fsCriarDiretorio('files/build/html');

        const listaArquivo = glob
            .sync('views/@(pages|templates)/**/*.view')
            .concat(glob.sync('src/Painel/App/**/*.view'));

        const quantidade = listaArquivo.length;
        const ultimo = quantidade - 1;
        let i, arquivo;
        for (i = 0; i < quantidade; ++i) {
            arquivo = listaArquivo[i];
            if (!(/^views\/templates/.test(arquivo) && /\/index\.view$/.test(arquivo))) {
                let nome = arquivo
                    .replace(/^src\/Painel\/App\//, 'painel_')
                    .replace(/^views\/pages\//, '')
                    .replace(/^views\/templates\//, 'templates/')
                    .replace(/\/index\.view$/, '.php')
                    .replace(/\.view$/, '.php')
                    .replace(/\/Views/, '')
                    .replace(/\//g, '_')
                    .replace(/_{2,}/g, '_');

                try {
                    await processarHtml(arquivo, nome, 'files/build/html');
                } catch (error) {
                    mensagemErro(error);
                }
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
async function processarHtml(arquivo, nome, destino) {
    return new Promise(async (resolve, reject) => {
        try {
            await fsVerificarSeArquivoExiste(arquivo);
        } catch (error) {
            reject('Arquivo não existe: ' + arquivo);
            return;
        }

        let conteudo = await fsPegarConteudo(arquivo);
        conteudo = await adicionarTemplateSeHouver(conteudo);
        conteudo = await fazerReplaceNoConteudo(conteudo, arquivo.replace(/\/[a-zA-Z0-9\_\-]+\.view$/, ''));

        try {
            await fsCriarArquivo(destino + '/' + nome, conteudo);
            resolve('Arquivo salvo com sucesso: ' + arquivo);
        } catch (error) {
            reject('Ocorreu um erro ao salvar o arquivo: ' + arquivo);
        }
    });
}

async function adicionarTemplateSeHouver(conteudo) {
    return new Promise(async resolve => {
        const templateExiste = conteudo.match(/\@\ ?template\ \(?(\"|\')[a-zA-Z0-9\_\-\.\/]+/g);
        if (templateExiste == null) {
            resolve(conteudo);
            return;
        }

        const template = templateExiste[0]
            .replace(/\@\ ?template\ ?\(?(\"|\')/, '')
            .replace('.', '/')
            .trim();

        const arquivoTemplate = 'views/templates/' + template + '/index.view';
        const arquivoConfig = 'views/templates/' + template + '/config.php';

        if (await !fs.existsSync(arquivoTemplate, 'utf-8')) {
            resolve(conteudo);
            return;
        }

        let conteudoTemplate = await fs.readFileSync(arquivoTemplate, 'utf-8');
        const dirExiste = conteudoTemplate.match(/\@\ ?dir/g);
        if (dirExiste != null) {
            conteudoTemplate = await fazerReplaceNoTemplate(conteudoTemplate, template);
        }

        let html = '';
        if (await fs.existsSync(arquivoConfig)) {
            html = '<?php require_once ROOT . "/views/templates/' + template + '/config.php"; ?>\n';
        }
        html += conteudoTemplate.replace('[[VIEW]]', conteudo);
        resolve(html);
    });
}

function fazerReplaceNoTemplate(conteudo, template) {
    return new Promise(resolve => {
        let listaLinha = conteudo.split('\n');
        let html = '';
        listaLinha.forEach(linha => {
            linha = linha.replace(/\t/g, '    ');
            // @dir
            if (linha.match(/\@\ ?dir(.*)/g)) {
                let arquivo = linha
                    .replace(/\;{0,1}\ {0,}$/, '')
                    .replace(/\ \(|\)|\'|\"|\.php/g, '')
                    .replace(/\@\ ?dir\ ?\/?/, '')
                    .trim();
                if (/\.view$/.test(arquivo)) {
                    arquivo = arquivo.replace(/\.view$/, '').replace(/\//g, '_');
                    html +=
                        '<?php require ROOT . "/files/build/views/templates_' +
                        template +
                        '_' +
                        arquivo +
                        '.php"; ?>\n';
                } else {
                    html += '<?php require ROOT . "/views/templates/' + template + '/' + arquivo + '.php"; ?>\n';
                }
                return;
            }
            html += linha + '\n';
        });
        resolve(html);
    });
}

function fazerReplaceNoConteudo(conteudo, path) {
    return new Promise(resolve => {
        let listaLinha = conteudo.split('\n');
        let html = '';
        let lacoNumero = 1;
        [].forEach.call(listaLinha, linha => {
            if (linha.match(/\@\ ?template\ (.*)/)) {
                return;
            }
            linha = linha.replace(/\t/g, '    ');

            const echoHtml = linha.match(/\{\{(.*)\}\}/g);
            const echoPuro = linha.match(/\{\!\!(.*)\!\!\}/g);
            const echoIcone = linha.match(/\@\ ?icone/g);
            const echoLink = linha.match(/\@\ ?LINK/g);
            const echoRoute = linha.match(/\@\ ?route/g);

            if (echoHtml || echoPuro || echoIcone || echoLink || echoRoute) {
                let htmlTemp = linha;
                // {{ $teste }}
                if (echoHtml) {
                    htmlTemp = htmlTemp.replace(/\{\{/g, '<?= echoView(').replace(/\;?\ ?\}\}/g, '); ?>') + '\n';
                }
                // {!! $teste !!}
                if (echoPuro) {
                    htmlTemp = htmlTemp.replace(/\{\!\!/g, '<?= ').replace(/\!\!\}/g, ' ?>') + '\n';
                }
                // @iconeNome(numero);
                if (echoIcone) {
                    htmlTemp =
                        htmlTemp.replace(/\@\ ?icone([a-zA-Z0-9\_]+)\(?([0-9\.]*)\)?(.*)/, '<?= icone$1($2); ?>$3') +
                        '\n';
                }
                // @LINK;
                if (echoLink) {
                    htmlTemp = htmlTemp.replace(/\@\ ?(LINK[A-Z\_]{0,})/, '<?= $1 ?>') + '\n';
                }
                // @route;
                if (echoRoute) {
                    htmlTemp =
                        htmlTemp.replace(/\@\ ?route\(?[\'|\"]?([a-zA-Z0-9\.]{0,})[\'|\"]?\)?/, "<?= route('$1') ?>") +
                        '\n';
                }
                html += htmlTemp;
                return;
            }

            //UNICO
            // @ifforeach
            if (linha.match(/^\ {0,}\@\ ?ifforeach/i)) {
                html +=
                    linha
                        .replace(/\:{0,1}\ {0,}$/, '')
                        .replace(
                            /^\ {0,}\@\ ?ifforeach\ ?\(\ ?([a-zA-Z0-9\_\$\-\>\[\]\'\"]+)/i,
                            '<?php if (isset($1) && !vazio($1)):\nforeach($1'
                        ) + ': ?>\n';
                return;
            }
            // @endifforeach;
            if (linha.match(/^\ {0,}\@\ ?endifforeach/i)) {
                html += '    <?php endforeach; ?>\n<?php endif;?>\n';
                return;
            }
            // @elseforeach;
            if (linha.match(/^\ {0,}\@\ ?elseforeach/i)) {
                html += '    <?php endforeach; ?>\n<?php else:?>\n';
                return;
            }
            // @elseifforeach;
            if (linha.match(/^\ {0,}\@\ ?elseifforeach/i)) {
                html +=
                    '    <?php endforeach; ?>\n<?php elseif' +
                    linha.replace(/^\ {0,}\@\ ?elseifforeach/i, '').replace(/\:\ *$/, '') +
                    ': ?>\n';
                return;
            }
            // @ifIssetObject
            if (linha.match(/^\ {0,}\@\ ?ifIssetObject/i)) {
                html +=
                    linha
                        .replace(/\:{0,1}\ {0,}$/, '')
                        .replace(
                            /^\ {0,}\@\ ?ifissetObject\ ?\(([a-zA-Z0-9\_\$\-\>\[\]\'\"]+)/i,
                            '<?php if(isset($1) && is_object($1) && !vazio($1)'
                        ) + ': ?>\n';
                return;
            }
            // @ifIssetArray
            if (linha.match(/^\ {0,}\@\ ?ifIssetArray/i)) {
                html +=
                    linha
                        .replace(/\:{0,1}\ {0,}$/, '')
                        .replace(
                            /^\ {0,}\@\ ?ifIssetArray\ ?\(([a-zA-Z0-9\_\$\-\>\[\]\'\"]+)/i,
                            '<?php if(isset($1) && is_array($1) && !empty($1)'
                        ) + ': ?>\n';
                return;
            }
            // @ifIsset
            if (linha.match(/^\ {0,}\@\ ?ifIsset/i)) {
                html +=
                    linha
                        .replace(/\:{0,1}\ {0,}$/, '')
                        .replace(
                            /^\ {0,}\@\ ?ifIsset\ ?\(([a-zA-Z0-9\_\$\-\>\[\]\'\"]+)/i,
                            '<?php if(isset($1) && !vazio($1)'
                        ) + ': ?>\n';
                return;
            }

            // @laco
            if (linha.match(/^\ {0,}\@\ ?laco\([0-9\,]+\)\;?\ */g)) {
                let numero = linha
                    .match(/\(([0-9\,]+)\)/)[0]
                    .replace(/\(|\)/g, '')
                    .split(',');
                if (numero.length == 1) {
                    numero = numero[0];
                } else if (numero.length > 1) {
                    const min = Math.ceil(numero[0]);
                    const max = Math.floor(numero[1]);
                    numero = Math.floor(Math.random() * (max - min) + min);
                } else {
                    numero = 5;
                }
                html += linha.replace(
                    /^\ *\@\ ?laco\([0-9\,]+\)\;?\ */g,
                    '<?php for($i' +
                        lacoNumero +
                        ' = 0; $i' +
                        lacoNumero +
                        ' < ' +
                        numero +
                        '; $i' +
                        lacoNumero +
                        '++): ?>\n'
                );
                lacoNumero++;
                return;
            }
            // @endlaco
            if (linha.match(/^\ {0,}\@\ ?endlaco(.*)/g)) {
                html += '<?php endfor; ?>\n';
                return;
            }
            // @iflaco
            if (linha.match(/\@\ ?iflaco/g)) {
                const lacoNumeroTemp = lacoNumero - 1;
                html += linha
                    .replace(/\@\ ?iflaco/g, '<?php $lacoLista' + lacoNumeroTemp + ' = ')
                    .replace('[', '["')
                    .replace(/\,\ */g, '","')
                    .replace(']', '"]; echo $lacoLista' + lacoNumeroTemp + '[$i' + lacoNumeroTemp + '] ?? "" ?>');
                return;
            }
            // @ifs
            if (
                linha.match(
                    /^\ {0,}\@\ ?(if\ ?\(|else\ ?if\ ?\(|else|foreach\ ?\(|while\ ?\(|for\ ?\(|switch\ ?\()(.*)/g
                )
            ) {
                html += linha.replace(/\:{0,1}\ {0,}$/, '').replace(/\@\ ?/, '<?php ') + ': ?>\n';
                return;
            }
            // @endifs
            if (linha.match(/^\ {0,}\@\ ?(endif|endforeach|endwhile|endfor|endswitch)(.*)/g)) {
                html +=
                    linha.replace(/^\ {0,}\@\ ?(endif|endforeach|endwhile|endfor|endswitch)(.*)/g, '<?php $1; ?>') +
                    '\n';
                return;
            }
            // @continue
            if (linha.match(/\@\ ?continue\((.*)/g)) {
                html += '<?php ' + linha.replace(/\@\ ?continue/, 'if') + ' { continue; } ?>\n';
                return;
            }
            if (linha.match(/\@\ ?continue(.*)/g)) {
                html += '<?php continue; ?>\n';
                return;
            }
            // @break
            if (linha.match(/\@\ ?break\((.*)/g)) {
                html += '<?php ' + linha.replace(/\@\ ?break/, 'if') + ' { break; } ?>\n';
                return;
            }
            if (linha.match(/\@\ ?break(.*)/g)) {
                html += '<?php break; ?>\n';
                return;
            }
            // @resource
            if (linha.match(/\@\ ?resource(.*)/g)) {
                html +=
                    linha
                        .replace(/\;{0,1}\ {0,}$/, '')
                        .replace(/\(|\)|\'|\"|\.php/g, '')
                        .replace(/\@\ ?resource\ ?/, '<?php require ROOT . "/resources/php/') + '.php"; ?>\n';
                return;
            }
            // @view
            if (linha.match(/\@\ ?view(.*)/g)) {
                html +=
                    linha
                        .replace(/\;{0,1}\ {0,}$/, '')
                        .replace(/\(|\)|\'|\"/g, '')
                        .replace(/\/index\.view$/, '')
                        .replace(/\.view$/, '')
                        .replace(/\//g, '_')
                        .replace(/\@\ ?view\ ?/, '<?php require ROOT . "/files/build/views/') + '.php"; ?>\n';
                return;
            }
            // @dir
            if (linha.match(/\@\ ?dir(.*)/g)) {
                let arquivo = linha
                    .replace(/\;{0,1}\ {0,}$/, '')
                    .replace(/\ \(|\)|\'|\"|\.php/g, '')
                    .replace(/\@\ ?dir\ ?\/?/, '')
                    .trim();
                if (/\.view$/.test(arquivo)) {
                    arquivo = arquivo.replace(/\.view$/, '').replace(/\//g, '_');
                    html +=
                        '<?php require ROOT . "/files/build/views/' +
                        path.replace(/^\/?views\/pages\//, '').replace(/\//g, '_') +
                        '_' +
                        arquivo +
                        '.php"; ?>';
                } else {
                    html += '<?php require ROOT . "/' + path + '/' + arquivo + '.php"; ?>';
                }
                return;
            }
            // @require_once
            if (linha.match(/\@\ ?require_once(.*)/g)) {
                html +=
                    linha.replace(/\;{0,1}\ {0,}$/, '').replace(/@\ ?require_once/, '<?php require_once') + '; ?>\n';
                return;
            }
            // @require
            if (linha.match(/\@\ ?require(.*)/g)) {
                html += linha.replace(/\;{0,1}\ {0,}$/, '').replace(/@\ ?require/, '<?php require') + '; ?>\n';
                return;
            }
            // @include_once
            if (linha.match(/\@\ ?include_once(.*)/g)) {
                html +=
                    linha.replace(/\;{0,1}\ {0,}$/, '').replace(/@\ ?include_once/, '<?php include_once') + '; ?>\n';
                return;
            }
            // @include
            if (linha.match(/\@\ ?include(.*)/g)) {
                html += linha.replace(/\;{0,1}\ {0,}$/, '').replace(/@\ ?include/, '<?php include') + '; ?>\n';
                return;
            }
            // @? "teste" @end
            if (linha.match(/\@\?\ ?/) && linha.match(/\@\ ?end/)) {
                html += linha.replace(/^\@\?\ ?/, '<?php echo ').replace(/^\@\ ?php/, ' ?>') + '\n';
                return;
            }
            // @?
            if (linha.match(/^\ {0,}\@\?\ ?$/)) {
                html += linha.replace(/\@\?\ ?/, '<?php echo ');
                return;
            }
            // @? Algo aqui
            if (linha.match(/^\ {0,}\@\?\ ?/)) {
                html += linha.replace(/^\ {0,}\@\?\ ?/, '<?php echo ') + ' ?>\n';
                return;
            }
            // @php algo
            if (linha.match(/^\ {0,}\@\ ?php\ \$?[a-zA-Z0-9\_\-]+/)) {
                html += linha.replace(/\;\ {0,}$/, '').replace(/^\ {0,}\@\ ?php/, '<?php ') + '; ?>\n';
                return;
            }
            // @php
            if (linha.match(/^\ {0,}\@\ ?php/)) {
                html += linha.replace(/^\ {0,}\@\ ?php/, '<?php ') + '\n';
                return;
            }
            // @end
            if (linha.match(/^\ {0,}\@\ ?end/)) {
                html += linha.replace(/@\ ?end/, ' ?>') + '\n';
                return;
            }
            // @hash
            if (linha.match(/^\ {0,}\@\ ?hash/)) {
                html +=
                    linha.replace(/\@\ ?hash\((\'|\")?/, "<?= formHash('").replace(/(\'|\")?\)\;?/, "'); ?>") + '\n';
                return;
            }
            // @CSS
            if (linha.match(/\@\ ?CSS/g)) {
                html += linha.replace(/\@\ ?CSS/g, '<?= $listaCss; ?>') + '\n';
                return;
            }
            // @JS
            if (linha.match(/\@\ ?JS/g)) {
                html += linha.replace(/\@\ ?JS/g, '<?= $listaJs; ?>') + '\n';
                return;
            }
            html += linha + '\n';
        });
        resolve(html);
    });
}
