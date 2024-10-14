// @template "painel"

window.addEventListener('load', () => {
    const inputLimpar = $$(`
        #input_local, #input_titulo_interno, #input_titulo, #input_texto,
        #input_link, #input_target, #input_status, #input_api_status, #input_api_uri,
        #input_api_metodo, .bloco_api_body .input_geral, #input_id, #input_margem,
        #input_link_empresa, #input_editor
    `);

    const id = $('#input_visualizar_id').valor();

    const inputId = $('#input_id');
    const inputLocal = $('#input_local');
    const inputTipo = $('#input_tipo');
    const inputTituloInterno = $('#input_titulo_interno');
    const inputTitulo = $('#input_titulo');
    const inputTexto = $('#input_texto');
    const inputLink = $('#input_link');
    const inputLinkEmpresa = $('#input_link_empresa');
    const inputTarget = $('#input_target');
    const inputDivDirecao = $('#input_div_direcao');
    const inputDivPosicao = $('#input_div_posicao');
    const inputMargem = $('#input_margem');
    const inputStatus = $('#input_status');
    const inputApiStatus = $('#input_api_status');
    const inputApiMetodo = $('#input_api_metodo');
    const inputApiBody = $('#input_api_body');
    const inputApiUri = $('#input_api_uri');
    const inputHtml = $('#input_html');
    const inputTabela = $('#input_tabela');
    const inputEditor = $('#input_editor');

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
    const blocoLinkTag = $('.bloco_link_tag');
    const blocoMargem = $('.bloco_margem');
    const blocoTarget = $('.bloco_target');
    const blocoDivPosicao = $('.bloco_div_posicao');
    const blocoDivDirecao = $('.bloco_div_direcao');
    const blocoBodyLista = $('.bloco_api_body .fw_form_indice_valor_lista');
    const blocoEditor = $('.bloco_editor');

    const botaoPopupAbrir = $('#botao_view_abrir');
    const botaoPopupSalvar = $('#botao_add_html');
    const botaoSalvarHtml = $('#botao_salvar_html');
    const PopupAdd = new Popup('Adicionar', 'bloco_view_add', true, false);

    let blocoListaAtual;
    botaoPopupAbrir.evento('click', () => {
        addSubGrupo(blocoLista);
    });

    const addSubGrupo = bloco => {
        inputTipo.valor('');
        PopupAdd.abrir();
        blocoListaAtual = bloco;
        limparObrigatorio();
    };

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
        inputLinkEmpresa.valor(item.link_empresa || '');
        inputTarget.valor(item.target || '');
        inputDivDirecao.valor(item.div_direcao || '');
        inputDivPosicao.valor(item.div_posicao || '');
        inputMargem.valor(item.margem || '');
        inputStatus.valor(item.status || 'nao');
        inputApiStatus.valor(item.api_status || 'nao');
        blocoApiSim.classe('display_none', apiStatus != 'sim');
        inputApiMetodo.valor(item.api_metodo || '');
        inputApiBody.valor(item.api_body || '');
        inputApiUri.valor((item.api_uri || '').replace(/^\//, ''));
        inputTabela.valor(item.tabela || '');
        inputEditor.valor(item.editor || '');
        blocoListaAtual = $('.lista', article);
        PopupAdd.abrir();
    };

    const colocarDragDrop = bloco => {
        new DragDrop()
            .bloco(bloco)
            .botao('.drag')
            .item('article')
            .eventoMover(() => {
                mostrarBotaoSalvar();
            })
            .iniciar();
    };

    const montarArticle = (bloco, item) => {
        htmlLinha[item.id] = item;
        const clone = blocoLinhaPadrao.clonar();
        clone.attr({
            'data-id': item.id,
            id: 'bloco_item_' + item.id,
        });
        if (item.status == 'sim') {
            $('.status', clone).classe('ativo', true);
        }
        if (typeof item['lista'] === 'object' && Object.keys(item['lista']).length > 0) {
            const blocoNovo = $('.lista', clone);
            blocoNovo.aparecer();
            for (const [chave, itemNovo] of Object.entries(item['lista'])) {
                montarArticle(blocoNovo, itemNovo);
            }
        }
        $('header h1', clone).texto(item.titulo_interno);
        bloco.aparecer();
        bloco.final(clone);
        colocarDragDrop(bloco);
    };

    for (const [chave, item] of Object.entries(html)) {
        montarArticle(blocoLista, item);
    }

    botaoPopupSalvar.evento('click', async () => {
        const tipo = inputTipo.valor();
        if (!(await validarDadoPopup(tipo))) {
            return;
        }
        const add = vazio(inputId.valor());
        const id = !add ? inputId.valor() : uuid();
        const tituloInterno = inputTituloInterno.valor();
        const status = inputStatus.valor();
        ppe(inputEditor.valor());
        const item = {
            id: id,
            tipo,
            local: inputLocal.valor(),
            titulo: inputTitulo.valor(),
            texto: inputTexto.valor(),
            link: inputLink.valor(),
            target: inputTarget.valor(),
            margem: inputMargem.valor(),
            status: inputStatus.valor(),
            tabela: inputTabela.valor(),
            editor: inputEditor.valor(),
            /* eslint-disable */
            link_empresa: inputLinkEmpresa.valor(),
            div_direcao: inputDivDirecao.valor(),
            div_posicao: inputDivPosicao.valor(),
            titulo_interno: tituloInterno,
            api_status: inputApiStatus.valor(),
            api_metodo: inputApiMetodo.valor(),
            api_body: inputApiBody.valor(),
            api_uri: inputApiUri.valor(),
            /* eslint-enable */
        };
        htmlLinha[id] = item;

        if (add) {
            montarArticle(blocoListaAtual, item);
        } else {
            $('#bloco_item_' + id + ' header h1').texto(tituloInterno);
            $('#bloco_item_' + id + ' .status').classe('ativo', status == 'sim');
        }

        mostrarBotaoSalvar();
        PopupAdd.fechar();
    });

    const validarDadoPopup = tipo => {
        return new Promise(resolve => {
            const apiStatus = inputApiStatus.valor() == 'sim';

            let mensagem = '';
            if (vazio(tipo)) {
                mensagem = 'Escolha um tipo para continuar.';
            } else if (vazio(inputLocal.valor())) {
                mensagem = 'Escolha um local para continuar.';
            } else if (apiStatus && vazio(inputApiMetodo.valor())) {
                mensagem = 'Escolha o método da requisição para continuar.';
            } else if (apiStatus && vazio(inputApiUri.valor())) {
                mensagem = 'Digite a URI da requisição para continuar.';
            } else if (
                (tipo == 'titulo-texto' ||
                    tipo == 'titulo' ||
                    tipo == 'subtitulo' ||
                    tipo == 'botao' ||
                    tipo == 'botao-destaque' ||
                    tipo == 'campanha' ||
                    tipo == 'relacionado') &&
                vazio(inputTitulo.valor())
            ) {
                mensagem = 'Digite um título para continuar.';
            } else if ((tipo == 'titulo-texto' || tipo == 'texto') && vazio(inputTexto.valor())) {
                mensagem = 'Digite um texto para continuar.';
            } else if (tipo == 'magem') {
                mensagem = 'Digite uma margem para continuar.';
            } else if (tipo == 'div' && vazio(inputDivDirecao.valor())) {
                mensagem = 'Escolha a direção do conteudo da div para continuar.';
            } else if (tipo == 'div' && vazio(inputDivPosicao.valor())) {
                mensagem = 'Escolha a posição do conteudo da div para continuar.';
            }

            if (mensagem != '') {
                Alerta.notificacao(mensagem, false);
                resolve(false);
                return;
            }
            resolve(true);
        });
    };

    const mostrarBotaoSalvar = () => {
        botaoSalvarHtml.aparecer();
    };

    botaoSalvarHtml.evento('click', async () => {
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/view-pagina',
            {
                indice: 'html',
                id,
                html: pegarHtml(),
            },
            'Erro ao salvar html, por favor, tente novamente.'
        );

        Loading.hide();
        if (false === resposta) {
            return;
        }
        Alerta.notificacao('HTML salvo com sucesso.', true);
        botaoSalvarHtml.sumir();
    });

    const pegarHtml = () => {
        const lista = Array.from(blocoLista.children).filter(el => el.tagName.toLowerCase() === 'article');
        return JSON.stringify(montarBody(lista));
    };

    montarBody = lista => {
        const bodyTemp = {};
        let i = 1;
        for (const item of lista) {
            const id = item.attr('data-id');
            bodyTemp[i] = htmlLinha[id];
            const filho = Array.from(item.children).filter(el => el.classList.contains('lista') === true);
            if (filho.length == 1) {
                const articleFilho = Array.from(filho[0].children).filter(el => el.tagName.toLowerCase() === 'article');
                bodyTemp[i].lista = montarBody(articleFilho);
            }
            i++;
        }
        return bodyTemp;
    };

    inputTipo.evento('formChange', () => {
        limparObrigatorio();
        const valor = inputTipo.valor();
        setarTipo(valor);
    });

    inputApiStatus.evento('change', () => {
        blocoApiSim.classe('display_none', !inputApiStatus.checked);
    });

    const mudarStatus = bloco => {
        const ativo = bloco.classe('ativo', '?');
        const valor = ativo ? 'nao' : 'sim';
        const id = bloco.closest('article').attr('data-id');
        htmlLinha[id].status = valor;
        bloco.classe('ativo', !ativo);
        mostrarBotaoSalvar();
    };

    blocoLista.evento('click', e => {
        if (e.target.classe('status', '?') || e.target.closest('.status')) {
            const bloco = e.target.classe('status', '?') ? e.target : e.target.closest('.status');
            mudarStatus(bloco);
        } else if (e.target.classe('editar', '?') || e.target.closest('.editar')) {
            const bloco = e.target.closest('article');
            abrirEditar(bloco);
        } else if (e.target.classe('mais', '?') || e.target.closest('.mais')) {
            const bloco = e.target.closest('article');
            addSubGrupo($('.lista', bloco));
        }
    });
    blocoLista.evento('dblclick', e => {
        if (e.target.classe('deletar', '?') || e.target.closest('.deletar')) {
            const bloco = e.target.closest('article');
            bloco.remove();
            mostrarBotaoSalvar();
        }
    });

    const setarTipo = valor => {
        if (valor == 'titulo-texto') {
            blocoTitulo.aparecer();
            blocoTexto.aparecer();
        } else if (valor == 'titulo' || valor == 'subtitulo') {
            blocoTitulo.aparecer();
        } else if (valor == 'texto') {
            blocoTexto.aparecer();
        } else if (
            valor == 'botao' ||
            valor == 'botao-destaque' ||
            valor == 'botao-fixo' ||
            valor == 'campanha' ||
            valor == 'relacionado'
        ) {
            blocoTitulo.aparecer();
            blocoLink.aparecer();
            blocoTarget.aparecer();
        } else if (valor == 'botao-empresa') {
            blocoTitulo.aparecer();
            blocoLinkTag.aparecer();
            blocoTarget.aparecer();
        } else if (valor == 'margem') {
            blocoMargem.aparecer();
        } else if (valor == 'div') {
            blocoDivDirecao.aparecer();
            blocoDivPosicao.aparecer();
        } else if (valor == 'tabela') {
            inputTabela.aparecer();
        } else if (valor == 'editor') {
            blocoEditor.aparecer();
        }
    };
    const limparObrigatorio = () => {
        blocoEditor.sumir();
        inputTabela.sumir();
        blocoTitulo.sumir();
        blocoTexto.sumir();
        blocoLink.sumir();
        blocoLinkTag.sumir();
        blocoTarget.sumir();
        blocoDivPosicao.sumir();
        blocoDivDirecao.sumir();
        blocoMargem.sumir();
        blocoApiSim.sumir();
        inputLimpar.valor('');
        if (blocoBodyLista) {
            blocoBodyLista.html('');
        }
    };
});
