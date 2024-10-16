// @template "painel"

window.addEventListener('load', () => {
    const inputLimpar = $$(`
        #input_local, #input_titulo_interno, #input_titulo, #input_texto,
        #input_link, #input_target, #input_status, #input_api_status, #input_api_uri,
        #input_api_metodo, .bloco_api_body .input_geral, #input_id, #input_margem,
        #input_link_empresa, #input_editor, #input_lista_valor, #input_lista_tipo
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
    const inputListaTipo = $('#input_lista_tipo');
    const inputListaValor = $('#input_lista_valor');
    const inputImagemArquivo = $('#input_imagem_arquivo');
    const inputImagemAltura = $('#input_imagem_altura');
    const inputIconeTipo = $('#input_icone_tipo');
    const inputIconeTamanho = $('#input_icone_tamanho');
    const inputIconeNome = $('#input_icone_nome');
    const inputIconeAltura = $('#input_icone_altura');

    const htmlLinha = {};
    let html;
    try {
        html = JSON.parse(inputHtml.valor());
    } catch (error) {
        html = [];
    }

    const blocoConteudo = $('#bloco_view_conteudo');
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
    const blocoLista = $$('.bloco_lista');
    const blocoApiStatus = $$('.bloco_api_status');
    const blocoImagem = $$('.bloco_imagem');
    const blocoIcone = $('.bloco_icone');
    const blocoIconeTamanho = $('.bloco_icone_tamanho');

    const botaoPopupAbrir = $('#botao_view_abrir');
    const botaoPopupSalvar = $('#botao_add_html');
    const botaoSalvarHtml = $('#botao_salvar_html');
    const PopupAdd = new Popup('Adicionar', 'bloco_view_add', true, false);

    let blocoListaAtual;
    botaoPopupAbrir.evento('click', () => {
        addSubGrupo(blocoConteudo);
    });

    const addSubGrupo = bloco => {
        inputTipo.valor('');
        PopupAdd.abrir();
        blocoListaAtual = bloco;
        limparObrigatorio();
    };

    inputIconeTipo.evento('formChange', () => {
        const valor = inputIconeTipo.valor();
        if (valor != 'quadrado' && valor != 'redondo') {
            blocoIconeTamanho.sumir();
            return;
        }
        blocoIconeTamanho.aparecer();
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
        inputListaTipo.valor(item.lista_tipo || '');
        inputListaValor.valor(item.lista_valor || '');
        inputImagemArquivo.valor(item.imagem_arquivo || '');
        inputImagemAltura.valor(item.imagem_altura || '');
        inputIconeTipo.valor(item.icone_tipo || '');
        inputIconeTamanho.valor(item.icone_tamanho || '');
        inputIconeNome.valor(item.icone_nome || '');
        inputIconeAltura.valor(item.icone_altura || '');
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
        montarArticle(blocoConteudo, item);
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
        const item = adicionarBody(id, tipo);

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

    const adicionarBody = (id, tipo) => {
        const completo = {
            titulo: inputTitulo.valor(),
            texto: inputTexto.valor(),
            link: inputLink.valor(),
            target: inputTarget.valor(),
            margem: inputMargem.valor(),
            tabela: inputTabela.valor(),
            editor: inputEditor.valor(),
            /* eslint-disable */
            imagem_arquivo: inputImagemArquivo.valor(),
            imagem_altura: inputImagemAltura.valor(),
            icone_tipo: inputIconeTipo.valor(),
            icone_tamanho: inputIconeTamanho.valor(),
            icone_nome: inputIconeNome.valor(),
            icone_altura: inputIconeAltura.valor(),
            lista_tipo: inputListaTipo.valor(),
            lista_valor: inputListaValor.valor(),
            link_empresa: inputLinkEmpresa.valor(),
            div_direcao: inputDivDirecao.valor(),
            div_posicao: inputDivPosicao.valor(),
            api_status: inputApiStatus.valor() == 'sim' ? 'sim' : 'nao',
            api_metodo: inputApiMetodo.valor(),
            api_body: inputApiBody.valor(),
            api_uri: inputApiUri.valor(),
            /* eslint-enable */
        };
        const body = {
            id: id,
            tipo: tipo,
            local: inputLocal.valor(),
            status: inputStatus.valor() == 'sim' ? 'sim' : 'nao',
            /* eslint-disable */
            titulo_interno: inputTituloInterno.valor(),
            /* eslint-enable */
        };
        for (const ind of bodyUsado) {
            body[ind] = completo[ind];
        }
        return body;
    };

    const validarDadoPopup = tipo => {
        return new Promise(resolve => {
            const apiStatus = inputApiStatus.valor() == 'sim';
            const iconeTipo = inputIconeTipo.valor();
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
            } else if (tipo == 'tabela' && vazio(inputTabela.valor())) {
                mensagem = 'Digite pelo menos uma linha para a tabela.';
            } else if (tipo == 'margem' && vazio(inputMargem.valor())) {
                mensagem = 'Escolha um tamanho para a margem.';
            } else if (tipo == 'editor' && vazio(inputEditor.valor())) {
                mensagem = 'Digite um texto para o editor.';
            } else if (tipo == 'lista' && vazio(inputListaTipo.valor())) {
                mensagem = 'Escolha um tipo para a lista.';
            } else if (tipo == 'lista' && vazio(inputListaValor.valor())) {
                mensagem = 'Coloque pelo menos um item na lista.';
            } else if (tipo == 'imagem' && vazio(inputImagemArquivo.valor())) {
                mensagem = 'Envie uma imagem para continuar.';
            } else if (tipo == 'imagem' && vazio(inputImagemAltura.valor())) {
                mensagem = 'Digite uma altura para a imagem.';
            } else if (tipo == 'icone' && vazio(iconeTipo)) {
                mensagem = 'Escolha um tipo de ícone para continuar.';
            } else if (
                tipo == 'icone' &&
                (iconeTipo == 'redondo' || iconeTipo == 'quadrado') &&
                vazio(inputIconeTamanho.valor())
            ) {
                mensagem = 'Digite o tamanho do ícone.';
            } else if (tipo == 'icone' && vazio(inputIconeNome.valor())) {
                mensagem = 'Digite o nome do ícone para continuar.';
            } else if (tipo == 'icone' && vazio(inputIconeAltura.valor())) {
                mensagem = 'Digite a altura do ícone para continuar.';
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
        const lista = Array.from(blocoConteudo.children).filter(el => el.tagName.toLowerCase() === 'article');
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

    blocoConteudo.evento('click', e => {
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
    blocoConteudo.evento('dblclick', e => {
        if (e.target.classe('deletar', '?') || e.target.closest('.deletar')) {
            const bloco = e.target.closest('article');
            bloco.remove();
            mostrarBotaoSalvar();
        }
    });

    let bodyUsado = [];
    const setarTipo = valor => {
        if (valor == 'titulo-texto') {
            blocoTitulo.aparecer();
            blocoTexto.aparecer();
            bodyUsado = ['titulo', 'texto'];
        } else if (valor == 'titulo' || valor == 'subtitulo') {
            blocoTitulo.aparecer();
            bodyUsado = ['titulo'];
        } else if (valor == 'texto') {
            blocoTexto.aparecer();
            bodyUsado = ['texto'];
        } else if (valor == 'botao' || valor == 'botao-destaque' || valor == 'botao-fixo') {
            blocoTitulo.aparecer();
            blocoLink.aparecer();
            blocoTarget.aparecer();
            bodyUsado = ['titulo', 'link', 'target'];
        } else if (valor == 'relacionado') {
            blocoTitulo.aparecer();
            blocoLink.aparecer();
            blocoTarget.aparecer();
            blocoApiStatus.aparecer();
            bodyUsado = ['titulo', 'link', 'target', 'api_status', 'api_metodo', 'api_uri', 'api_body'];
        } else if (valor == 'botao-empresa') {
            blocoTitulo.aparecer();
            blocoLinkTag.aparecer();
            blocoTarget.aparecer();
            bodyUsado = ['titulo', 'link_empresa', 'target'];
        } else if (valor == 'margem') {
            blocoMargem.aparecer();
            bodyUsado = ['margem'];
        } else if (valor == 'div') {
            blocoDivDirecao.aparecer();
            blocoDivPosicao.aparecer();
            bodyUsado = ['div_direcao', 'div_posicao'];
        } else if (valor == 'tabela') {
            inputTabela.aparecer();
            bodyUsado = ['tabela'];
        } else if (valor == 'lista') {
            blocoLista.aparecer();
            bodyUsado = ['lista_tipo', 'lista_valor'];
        } else if (valor == 'imagem') {
            blocoImagem.aparecer();
            bodyUsado = ['imagem_arquivo', 'imagem_altura'];
        } else if (valor == 'icone') {
            blocoIcone.aparecer();
            bodyUsado = ['icone_tipo', 'icone_tamanho', 'icone_nome', 'icone_altura'];
        } else if (valor == 'campanha') {
            blocoTitulo.aparecer();
            blocoLink.aparecer();
            blocoTarget.aparecer();
            blocoApiStatus.aparecer();
            bodyUsado = ['titulo', 'link', 'target', 'api_status', 'api_metodo', 'api_uri', 'api_body'];
        } else if (valor == 'banner') {
            blocoApiStatus.aparecer();
            bodyUsado = ['api_status', 'api_metodo', 'api_uri', 'api_body'];
        } else if (valor == 'linha') {
            bodyUsado = [];
        } else if (valor == 'editor') {
            blocoEditor.aparecer();
            bodyUsado = ['editor'];
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
        blocoLista.sumir();
        blocoApiStatus.sumir();
        blocoImagem.sumir();
        blocoIcone.sumir();
        inputLimpar.valor('');
        if (blocoBodyLista) {
            blocoBodyLista.html('');
        }
    };
});
