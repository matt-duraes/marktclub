// @template "painel"

window.addEventListener('load', () => {
    const inputLimpar = $$(`
        #input_local, input_tipo, #input_titulo_interno, #input_titulo, #input_texto,
        #input_link, #input_target, #input_status, #input_api_status, #input_api_uri,
        #input_api_metodo, .bloco_api_body .input_geral, #input_id
    `);

    const inputId = $('#input_id');
    const inputLocal = $('#input_local');
    const inputTipo = $('#input_tipo');
    const inputTituloInterno = $('#input_titulo_interno');
    const inputTitulo = $('#input_titulo');
    const inputTexto = $('#input_texto');
    const inputLink = $('#input_link');
    const inputTarget = $('#input_target');
    const inputMargem = $('#input_margem');
    const inputStatus = $('#input_status');
    const inputApiStatus = $('#input_api_status');
    const inputApiMetodo = $('#input_api_metodo');
    const inputApiUri = $('#input_api_uri');
    const inputHtml = $('#input_html');

    const htmlLinha = {};
    let html;
    try {
        html = JSON.parse(inputHtml.valor());
    } catch (error) {
        html = [];
    }

    const blocoLista = $('#bloco_view_conteudo');
    const blocoLinhaPadrao = $('#bloco_linha_padrao');

    // Bloco add
    const blocoApiSim = $$('.bloco_api_sim');
    const blocoTitulo = $('.bloco_titulo');
    const blocoTexto = $('.bloco_texto');
    const blocoLink = $('.bloco_link');
    const blocoMargem = $('.bloco_margem');
    const blocoTarget = $('.bloco_target');
    const blocoBodyLista = $('.bloco_api_body .fw_form_indice_valor_lista');

    const botaoAdicionar = $('#botao_view_abrir');
    const botaoAddHtml = $('#botao_html_salvar');
    const PopupAdd = new Popup('Adicionar', 'bloco_view_add', true, false);

    botaoAdicionar.evento('click', () => {
        PopupAdd.abrir();
        limparObrigatorio();
    });

    const abrirEditar = article => {
        const id = article.attr('data-id');
        const item = htmlLinha[id];
        const apiStatus = item.api_status || '';

        limparObrigatorio();
        setarTipo(item.tipo);
        inputId.valor(id);
        inputTipo.valor(item.tipo);
        inputLocal.valor(item.local);
        inputTituloInterno.valor(item.titulo_interno);
        inputTitulo.valor(item.titulo || '');
        inputTexto.valor(item.texto || '');
        inputLink.valor(item.link || '');
        inputTarget.valor(item.target || '');
        inputMargem.valor(item.margem || '');
        inputStatus.valor(item.status || 'nao');
        inputApiStatus.valor(item.api_status || 'nao');
        blocoApiSim.classe('display_none', apiStatus != 'sim');
        inputApiMetodo.valor(item.api_metodo || '');
        inputApiUri.valor((item.api_uri || '').replace(/^\//, ''));

        const bodyAtual = item.api_body || [];
        const bodyQuantidade = Object.keys(bodyAtual).length;
        if (bodyQuantidade > 0) {
            for (const [chave, valor] of Object.entries(bodyAtual)) {
                blocoBodyLista.final(`
                    <div class="fw_form_indice_valor_linha fw_form_indice_valor_linha_padrao">
                        <div class="fw_form_indice_valor_ordem"><svg height="8" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 21" style="enable-background:new 0 0 40 21;" xml:space="preserve"><g><path class="st0" d="M37.1,21H2.9C1.3,21,0,19.7,0,18.1s1.3-2.9,2.9-2.9h34.3c1.6,0,2.9,1.3,2.9,2.9S38.7,21,37.1,21z M37.1,5.7 H2.9C1.3,5.7,0,4.4,0,2.9S1.3,0,2.9,0h34.3C38.7,0,40,1.3,40,2.9S38.7,5.7,37.1,5.7z"></path></g></svg></div>
                        <p><strong class="fw_form_indice_valor_indice">${chave}</strong></p>
                        <p class="fw_form_indice_valor_valor">${valor}</p>
                        <i class="fw_form_indice_valor_remover"><svg height="8" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50" xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"></path></svg></i>
                    </div>
                `);
            }
        }
        PopupAdd.abrir();
    };

    const colocarDragDrop = bloco => {
        new DragDrop().bloco(bloco).botao('.drag').item('article').iniciar();
    };

    const montarArticle = (bloco, item) => {
        htmlLinha[item.id] = item;
        const clone = blocoLinhaPadrao.clonar();
        clone.attr('data-id', item.id);
        if (item.status == 'sim') {
            $('.status', clone).classe('ativo', true);
        }
        if (typeof item['lista'] === 'object') {
            const blocoNovo = $('.lista', clone);
            blocoNovo.aparecer();
            for (const itemNovo of item['lista']) {
                montarArticle(blocoNovo, itemNovo);
            }
        }
        $('header h1', clone).texto(item.titulo_interno);
        bloco.final(clone);
        colocarDragDrop(bloco);
    };
    for (const item of html) {
        montarArticle(blocoLista, item);
    }

    botaoAddHtml.evento('click', async () => {
        const add = vazio(inputId.valor());
        const id = !add ? inputId.valor() : uuid();
        const item = {
            id: id,
            tipo: inputTipo.valor(),
            local: inputLocal.valor(),
            titulo: inputTitulo.valor(),
            texto: inputTexto.valor(),
            link: inputLink.valor(),
            target: inputTarget.valor(),
            margem: inputMargem.valor(),
            status: inputStatus.valor(),
            /* eslint-disable */
            titulo_interno: inputTituloInterno.valor(),
            api_status: inputApiStatus.valor(),
            api_metodo: inputApiMetodo.valor(),
            api_uri: inputApiUri.valor(),
            /* eslint-enable */
        };
        htmlLinha[id] = item;

        if (add) {
            //
        } else {
            //
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/view-pagina',
            {
                indice: 'html',
                html: pegarHtml(),
            },
            'Erro ao salvar html, por favor, tente novamente.'
        );

        Loading.hide();
        if (false === resposta) {
            return;
        }

        PopupAdd.fechar();
    });

    const pegarHtml = () => {
        const lista = $('article', blocoLista);
        const body = {};
        for (const item of lista) {
            const id = item.attr('data-id');
            body.push(htmlLinha[id]);
        }
    };

    inputTipo.evento('formChange', () => {
        limparObrigatorio();
        const valor = inputTipo.valor();
        setarTipo(valor);
    });

    inputApiStatus.evento('change', () => {
        blocoApiSim.classe('display_none', !inputApiStatus.checked);
    });

    blocoLista.evento('click', e => {
        if (e.target.classe('status', '?') || e.target.closest('.status')) {
            const bloco = e.target.classe('status', '?') ? e.target : e.target.closest('.status');
            bloco.classe('ativo');
        } else if (e.target.classe('editar', '?') || e.target.closest('.editar')) {
            const bloco = e.target.closest('article');
            abrirEditar(bloco);
        }
    });
    blocoLista.evento('dblclick', e => {
        if (e.target.classe('deletar', '?') || e.target.closest('.deletar')) {
            const bloco = e.target.closest('article');
            bloco.remove();
        }
    });

    const setarTipo = valor => {
        if (valor == 'titulo_texto') {
            blocoTitulo.aparecer();
            blocoTexto.aparecer();
        } else if (valor == 'titulo' || valor == 'subtitulo') {
            blocoTitulo.aparecer();
        } else if (valor == 'botao' || valor == 'botao-destaque' || valor == 'campanha' || valor == 'relacionado') {
            blocoTitulo.aparecer();
            blocoLink.aparecer();
            blocoTarget.aparecer();
        } else if (valor == 'margem') {
            blocoMargem.aparecer();
        }
    };
    const limparObrigatorio = () => {
        blocoTitulo.sumir();
        blocoTexto.sumir();
        blocoLink.sumir();
        blocoTarget.sumir();
        blocoMargem.sumir();
        blocoApiSim.sumir();
        inputLimpar.valor('');
        if (blocoBodyLista) {
            blocoBodyLista.html('');
        }
    };
});
