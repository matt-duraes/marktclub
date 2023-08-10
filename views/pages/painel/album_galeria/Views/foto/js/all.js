// @system "Alerta"
// @system "Galeria"
// @system "SwipeEvent"
// @system "DragDrop"
// @template "painel"

// @import "editar"

window.addEventListener('load', () => {
    const blocoGeral = document.getElementById('bloco_galeria_index');
    const hashUpload = blocoGeral.querySelector('#id_hash_upload_hash').value;
    const hashListar = blocoGeral.querySelector('#id_hash_listar_hash').value;
    const hashEditar = blocoGeral.querySelector('#id_hash_editar_hash').value;
    const hashDeletar = blocoGeral.querySelector('#id_hash_delelar_hash').value;
    const hashOrdem = blocoGeral.querySelector('#id_hash_ordem_hash').value;
    const idAlbum = blocoGeral.querySelector('#input_id').value;
    const botaoPaginacao = blocoGeral.querySelector('#botao_paginacao');

    const permissaoAdd = blocoGeral.querySelector('#permissao_add').value == 1;
    const permissaoEditar = blocoGeral.querySelector('#permissao_editar').value == 1;
    const permissaoDeletar = blocoGeral.querySelector('#permissao_deletar').value == 1;
    const permissaoOrdem = blocoGeral.querySelector('#permissao_ordem').value == 1;

    const blocoArrastar = document.getElementById('bloco_arrastar');
    const blocoArrastarArquivo = document.getElementById('bloco_arrastar_arquivo');
    const blocoArquivoLista = document.getElementById('bloco_arquivo_lista');
    const botaoUpload = document.getElementById('botao_galeria_upload');

    const GaleriaModel = new Galeria(
        '#bloco_arquivo_lista',
        'article',
        '.visualizar',
        LINK + '/album/galeria-download'
    );

    let arquivoNumero = 1;
    let paginaAtual = 0;
    let podeCarregarMaisPagina = true;
    let naoExisteImagem = false;

    /*
    |--------------------------------------------------------------------------
    | ORDENAR FOTOS
    |--------------------------------------------------------------------------
    */
    const ordenarFoto = async () => {
        GaleriaModel.reordenar();
        const lista = blocoArquivoLista.querySelectorAll('article');
        if (lista.length == 0) {
            return;
        }
        const body = new FormData();
        lista.forEach(foto => {
            body.append('id[]', foto.getAttribute('data-id'));
        });
        body.append('album', idAlbum);
        body.append('form_system_hash', hashOrdem);
        body.append('form_system_validacao', '');
        const response = await fetch(LINK + '/album/galeria-ordem', {
            method: 'POST',
            body,
        });
        if (response.status == 204) {
            return;
        }
        fetchNotificacaoErro(response, 'Ocorreu um erro ao tentar salvar a nova ordem.');
    };

    if (permissaoOrdem) {
        new DragDrop()
            .bloco(blocoArquivoLista)
            .item('article')
            .botao('article figure')
            .eventoFim(ordenarFoto)
            .iniciar();
    }

    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    */
    const botaoDeletar = document.querySelector('#botao_deletar_foto');
    if (botaoDeletar) {
        botaoDeletar.addEventListener('click', async () => {
            const lista = blocoArquivoLista.querySelectorAll('article.arquivo_checked');
            const quantidade = lista.length;

            if (quantidade <= 0) {
                return;
            }

            if (
                await Alerta.confirmar(
                    'Deletar imagens!',
                    'Tem certeza que deseja deletar as imagens selecionadas?',
                    '!'
                )
            ) {
                enviarImagensSelecionadasParaDeletar(lista);
            }
        });
    }
    const enviarImagensSelecionadasParaDeletar = async lista => {
        Loading.show();
        const body = new FormData();
        body.append('form_system_hash', hashDeletar);
        body.append('form_system_validacao', '');
        lista.forEach(album => {
            body.append('id[]', album.getAttribute('data-id'));
        });
        body.append('album', idAlbum);

        const response = await fetch(LINK + '/album/galeria-deletar', {
            method: 'POST',
            body,
        });
        Loading.hide();
        if (response.status == 204) {
            Alerta.notificacao('Imagens deletadas com sucesso!', true);
            return removerImagensSelecionadas(lista);
        }
        let json;
        try {
            json = await response.json();
        } catch (error) {
            json = {};
        }
        Alerta.notificacao(
            json.mensagem != undefined ? json.mensagem : 'Erro ao deletar as imagens, por favor, tente novamente.',
            false
        );
    };
    const removerImagensSelecionadas = lista => {
        lista.forEach(imagem => {
            imagem.parentNode.removeChild(imagem);
        });
        verificarSeAindaExisteImagem();
    };
    const verificarSeAindaExisteImagem = () => {
        const quantidade = blocoArquivoLista.querySelectorAll('article').length;
        if (quantidade > 0) {
            return;
        }
        blocoArquivoLista.innerHTML = `
            <div class="bloco_zero" id="bloco_zero" style="display: flex">
                <i><svg height="40" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 30" style="enable-background:new 0 0 40 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path class="st0" d="M2.7,952.4c-1.5,0-2.7,1.2-2.7,2.6v24.7c0,1.5,1.2,2.6,2.7,2.6h34.7c1.5,0,2.7-1.2,2.7-2.6V955 c0-1.5-1.2-2.6-2.7-2.6H2.7z M2.7,954.1h34.7c0.5,0,0.9,0.4,0.9,0.9v18.5l-7.4-5.9c-0.3-0.2-0.7-0.3-1.1,0l-6.6,4.5l-8.8-7.1 c-0.2-0.1-0.4-0.2-0.7-0.2c-0.1,0-0.3,0.1-0.4,0.2l-11.5,7.9V955C1.8,954.5,2.2,954.1,2.7,954.1L2.7,954.1z M23.1,958.5 c-2,0-3.6,1.6-3.6,3.5s1.6,3.5,3.6,3.5s3.6-1.6,3.6-3.5S25.1,958.5,23.1,958.5z M23.1,960.3c1,0,1.8,0.8,1.8,1.8 c0,1-0.8,1.8-1.8,1.8c-1,0-1.8-0.8-1.8-1.8C21.3,961.1,22.1,960.3,23.1,960.3z M13.7,966.7l8.8,7.1c0.3,0.2,0.7,0.3,1.1,0l6.6-4.5 l8.1,6.4v4c0,0.5-0.4,0.9-0.9,0.9H2.7c-0.5,0-0.9-0.4-0.9-0.9v-4.8L13.7,966.7L13.7,966.7z"/></g></svg></i>
                <p>Sem imagens no álbum</p>
            </div>
        `;
    };
    /*
    |--------------------------------------------------------------------------
    | CARREGAR IMAGENS
    |--------------------------------------------------------------------------
    */
    const carregarImagem = async tipoAppend => {
        if (podeCarregarMaisPagina === false) {
            return;
        }
        paginaAtual++;
        const pagina = paginaAtual;
        const body = new FormData();
        body.append('id', idAlbum);
        body.append('pagina', pagina);
        body.append('form_system_hash', hashListar);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/album/galeria-imagem', {
            body,
            method: 'POST',
        });

        let json;
        try {
            json = await response.json();
        } catch (error) {
            json = {};
        }

        if (pagina == 1) {
            acaoAposCarregarPrimeiraPagina(json.lista);
        }

        if (json.lista == undefined) {
            return;
        }

        let bloco;
        json.lista.forEach(async figure => {
            bloco = await adicionarHtmlFigure(tipoAppend);
            adicionarDadosAoFigure(bloco, figure.id, figure.imagem, figure.titulo);
        });
        if (json.paginacao) {
            botaoPaginacao.style.display = 'flex';
        } else {
            podeCarregarMaisPagina = false;
        }
    };
    const acaoAposCarregarPrimeiraPagina = lista => {
        const listaEsqueleto = blocoArquivoLista.querySelectorAll('.article_fake');
        if (listaEsqueleto.length > 0) {
            listaEsqueleto.forEach(bloco => {
                bloco.parentNode.removeChild(bloco);
            });
        }
        const blocoZero = blocoArquivoLista.querySelector('#bloco_zero');
        if (lista == undefined || !lista || lista.length == 0) {
            blocoZero.style.display = 'flex';
            naoExisteImagem = true;
            return;
        }
        blocoZero.parentNode.removeChild(blocoZero);
    };

    carregarImagem('append');
    botaoPaginacao.addEventListener('click', () => {
        botaoPaginacao.style.display = 'none';
        carregarImagem('append');
    });

    /*
    |--------------------------------------------------------------------------
    | UPLOAD
    |--------------------------------------------------------------------------
    */
    if (permissaoAdd) {
        botaoUpload.addEventListener('change', () => {
            const arquivo = botaoUpload.files;
            const quantidade = arquivo.length;

            if (quantidade == 0) {
                botaoUpload.value = '';
                return;
            }

            const blocoZero = blocoArquivoLista.querySelector('#bloco_zero');
            if (blocoZero) {
                blocoZero.parentNode.removeChild(blocoZero);
            }

            let i;
            for (i = 0; i < quantidade; ++i) {
                enviarNovaImagem(arquivo[i]);
            }
            botaoUpload.value = '';
        });

        blocoArrastarArquivo.addEventListener('dragover', e => {
            e.preventDefault();
            blocoArrastar.classList.add('arquivo_drag');
        });
        blocoArrastarArquivo.addEventListener('dragleave', e => {
            e.preventDefault();
            blocoArrastar.classList.remove('arquivo_drag');
        });
        blocoArrastarArquivo.addEventListener('drop', e => {
            e.preventDefault();

            blocoArrastar.classList.remove('arquivo_drag');
            if (!e.dataTransfer.items && !e.dataTransfer.files) {
                Alerta.notificacao('O navegador não tem suporte a arrastar arquivos.', false);
                return;
            }

            let quantidade;
            if (e.dataTransfer.items) {
                quantidade = e.dataTransfer.items.length;
                for (i = 0; i < quantidade; ++i) {
                    enviarNovaImagem(e.dataTransfer.items[i].getAsFile());
                }
            } else {
                quantidade = e.dataTransfer.files.length;
                for (i = 0; i < quantidade; ++i) {
                    enviarNovaImagem(e.dataTransfer.files[i]);
                }
            }
        });

        const enviarNovaImagem = async arquivo => {
            const tipo = arquivo.type;
            if (tipo != 'image/jpg' && tipo != 'image/jpeg' && tipo != 'image/png' && tipo != 'image/gif') {
                Alerta.notificacao('O arquivo enviado deve ser uma extensão válida.', false);
                return;
            }

            const bloco = await adicionarHtmlFigure('prepend');
            enviarImagemParaBackend(bloco, arquivo);
        };

        const enviarImagemParaBackend = async (bloco, arquivo) => {
            const body = new FormData();
            body.append('arquivo', arquivo);
            body.append('id', idAlbum);
            body.append('form_system_hash', hashUpload);
            body.append('form_system_validacao', '');
            body.append('capa', naoExisteImagem ? 1 : 0);

            const response = await fetch(LINK + '/album/galeria-upload', {
                body,
                method: 'POST',
            });

            let json = {};
            try {
                json = await response.json();
            } catch (error) {
                return ocorreuErroAoEnviarArquivo(bloco, json);
            }
            if (response.status != 201 || json.id == undefined) {
                return ocorreuErroAoEnviarArquivo(bloco, json);
            }
            naoExisteImagem = false;
            return adicionarDadosAoFigure(bloco, json.id, json.imagem, json.titulo);
        };

        const ocorreuErroAoEnviarArquivo = (bloco, json) => {
            bloco.parentNode.removeChild(bloco);
            const mensagem = json.mensagem != undefined ? json.mensagem : 'Ocorreu um erro ao salvar a imagem';
            Alerta.notificacao(mensagem, false);
        };
    }

    /*
    |--------------------------------------------------------------------------
    | GERAL
    |--------------------------------------------------------------------------
    */
    const adicionarHtmlFigure = tipo => {
        return new Promise(resolve => {
            const id = 'id_arquivo_novo_' + arquivoNumero;
            arquivoNumero++;
            const local = tipo == 'prepend' ? 'afterbegin' : 'beforeend';
            let checkHtml = '';
            if (permissaoDeletar) {
                checkHtml =
                    '<div class="check"><div class="icone"><svg height="15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z"/></svg></div></div>';
            }
            let editarHtml = '';
            if (permissaoEditar) {
                editarHtml =
                    '<div class="editar botao"><svg height="15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 27 27" style="enable-background:new 0 0 27 27;" xml:space="preserve"><desc>Created with Sketch.</desc><g><g transform="translate(-2.000000, -2.000000)"><g><path d="M21.3,2.3c0.4-0.4,1-0.4,1.4,0l0,0l6,6c0.4,0.4,0.4,1,0,1.4l0,0l-17,17c-0.1,0.1-0.3,0.2-0.5,0.3l0,0     l-8,2c-0.7,0.2-1.4-0.5-1.2-1.2l0,0l2-8c0-0.2,0.1-0.3,0.3-0.5l0,0L21.3,2.3z M18,8.4L5.9,20.5l-1.5,6.1l6.1-1.5L22.6,13L18,8.4z M22,4.4L19.4,7l4.6,4.6L26.6,9L22,4.4z"/></g></g></g></svg></div>';
            }
            blocoArquivoLista.insertAdjacentHTML(
                local,
                `
                    <article class="arquivo_novo loading_esqueleto" data-galeria-imagem="" id="${id}">
                        <div class="figure_esqueleto loading_esqueleto"></div>
                        ${checkHtml}
                        ${editarHtml}
                        <a href="" target="_blank" class="download botao"><svg height="15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34 40" style="enable-background:new 0 0 34 40;" xml:space="preserve"><path d="M15.7,1.3c0,3.1,0,6.2,0,9.3c0,4.9,0,9.9,0,14.8c0,1.1,0,2.2,0,3.4c0,0.7,0.6,1.4,1.3,1.3s1.3-0.6,1.3-1.3 c0-3.1,0-6.2,0-9.3c0-4.9,0-9.9,0-14.8c0-1.1,0-2.2,0-3.4C18.3,0.6,17.7,0,17,0C16.3,0,15.7,0.6,15.7,1.3L15.7,1.3z"/><path d="M6.1,18.4c1.1,1.4,2.3,2.7,3.4,4.1c1.8,2.2,3.6,4.3,5.4,6.5c0.4,0.5,0.8,1,1.2,1.5c0.4,0.5,1.4,0.5,1.9,0 c1.1-1.4,2.3-2.7,3.4-4.1c1.8-2.2,3.6-4.3,5.4-6.5c0.4-0.5,0.8-1,1.2-1.5c0.5-0.6,0.5-1.4,0-1.9c-0.5-0.5-1.4-0.6-1.9,0 c-1.1,1.4-2.3,2.7-3.4,4.1c-1.8,2.2-3.6,4.3-5.4,6.5c-0.4,0.5-0.8,1-1.2,1.5c0.6,0,1.2,0,1.9,0c-1.1-1.4-2.3-2.7-3.4-4.1 c-1.8-2.2-3.6-4.3-5.4-6.5c-0.4-0.5-0.8-1-1.2-1.5c-0.5-0.6-1.4-0.5-1.9,0C5.6,17.1,5.6,17.8,6.1,18.4L6.1,18.4z"/><path d="M1.3,40c1,0,2.1,0,3.1,0c2.5,0,5,0,7.5,0c3,0,6,0,9.1,0c2.6,0,5.2,0,7.8,0c1.3,0,2.5,0,3.8,0c0,0,0,0,0.1,0 c0.7,0,1.3-0.6,1.3-1.3s-0.6-1.3-1.3-1.3c-1,0-2.1,0-3.1,0c-2.5,0-5,0-7.5,0c-3,0-6,0-9.1,0c-2.6,0-5.2,0-7.8,0 c-1.3,0-2.5,0-3.8,0c0,0,0,0-0.1,0c-0.7,0-1.3,0.6-1.3,1.3C0,39.4,0.6,40,1.3,40L1.3,40z"/></svg></a>
                        <div class="visualizar botao"><svg height="15" xmlns:cc="hqttp://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 29.3" style="enable-background:new 0 0 40 29.3;" xml:space="preserve"><g transform="translate(0,-288.53333)"><path d="M20,288.5c-13.3,0-19.3,12.3-19.6,12.9c-0.6,1.1-0.6,2.5,0,3.6c0.3,0.6,6.3,12.9,19.6,12.9s19.3-12.3,19.6-12.9 c0.6-1.1,0.6-2.5,0-3.6C39.3,300.8,33.3,288.5,20,288.5z M20,291.2c11.6,0,16.8,10.6,17.2,11.4c0.2,0.4,0.2,0.8,0,1.2 c-0.4,0.8-5.6,11.4-17.2,11.4S3.2,304.6,2.8,303.8c-0.2-0.4-0.2-0.8,0-1.2C3.2,301.7,8.4,291.2,20,291.2z"/><path d="M20,293.9c-5.1,0-9.3,4.2-9.3,9.3s4.2,9.3,9.3,9.3s9.3-4.2,9.3-9.3S25.1,293.9,20,293.9z M20,296.5c3.7,0,6.7,3,6.7,6.7 s-3,6.7-6.7,6.7s-6.7-3-6.7-6.7S16.3,296.5,20,296.5z"/></g></svg></div>
                        <div class="bg_grade"></div>
                        <figure></figure>
                        <div class="bg"></div>
                        <p class="loading_esqueleto"></p>
                    </article>
                `
            );
            resolve(document.querySelector('#' + id));
        });
    };

    const adicionarDadosAoFigure = (bloco, id, imagem, titulo) => {
        const p = bloco.querySelector('p');
        const esqueleto = bloco.querySelector('.figure_esqueleto');
        esqueleto.parentNode.removeChild(esqueleto);

        bloco.classList.remove('arquivo_novo');
        bloco.classList.remove('loading_esqueleto');
        bloco.setAttribute('data-id', id);
        bloco.setAttribute('data-galeria-imagem', imagem);
        bloco.querySelector('a').setAttribute('href', LINK + '/album/galeria-download/' + id);
        bloco.querySelector('figure').style.backgroundImage = 'url(' + imagem + ')';
        p.innerText = titulo;
        p.classList.remove('loading_esqueleto');

        GaleriaModel.add(bloco);
        setarEventosDoFigure(bloco, id, titulo);
    };

    const setarEventosDoFigure = (bloco, id, titulo) => {
        if (permissaoDeletar) {
            const check = bloco.querySelector('.check');
            check.addEventListener('click', e => {
                bloco.classList.toggle('arquivo_checked');
                if (
                    blocoArquivoLista.querySelectorAll('.arquivo_checked').length > 0 &&
                    botaoDeletar instanceof Object &&
                    botaoDeletar.style
                ) {
                    botaoDeletar.style.display = 'flex';
                } else if (botaoDeletar instanceof Object && botaoDeletar.style) {
                    botaoDeletar.style.display = 'none';
                }
            });
        }

        if (permissaoEditar) {
            const bodyEditar = new FormData();
            bodyEditar.append('id', id);
            bodyEditar.append('album', idAlbum);
            bodyEditar.append('form_system_hash', hashEditar);
            bodyEditar.append('form_system_validacao', '');
            const PaginaEditar = new Pagina(
                titulo,
                LINK + '/album/galeria-editar',
                {
                    body: bodyEditar,
                    method: 'POST',
                },
                true,
                false,
                carregarAlbumEditar
            );

            const editar = bloco.querySelector('.editar');
            editar.addEventListener('click', () => {
                PaginaEditar.abrir();
            });
        }
    };
});
