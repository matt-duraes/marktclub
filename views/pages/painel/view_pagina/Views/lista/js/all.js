// @template "painel"

window.addEventListener('load', () => {
    const app = 'view-pagina';
    const inputLimpar = $$(`
        #input_local, #input_titulo_interno, #input_titulo, #input_texto,
        #input_link, #input_target, #input_status, #input_api_status, #input_api_uri,
        #input_api_metodo, .bloco_api_body .input_geral, #input_id, #input_div_posicao,
        #input_link_empresa, #input_editor, #input_lista_valor, #input_lista_tipo,
        #input_botao_tipo, #input_imagem_altura, #input_div_direcao, #input_margem_topo,
        #input_margem_esquerda, #input_margem_direita, #input_margem_baixo
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
    const inputStatus = $('#input_status');
    const inputApiStatus = $('#input_api_status');
    const inputApiMetodo = $('#input_api_metodo');
    const inputApiBody = $('#input_api_body');
    const inputApiUri = $('#input_api_uri');
    const inputTabela = $('#input_tabela');
    const inputEditor = $('#input_editor');
    const inputListaTipo = $('#input_lista_tipo');
    const inputListaValor = $('#input_lista_valor');
    const inputImagemArquivo = $('#input_imagem_arquivo');
    const inputImagemAltura = $('#input_imagem_altura');
    const inputImagemLink = $('.bloco_imagem .fw_imagem_figure');
    const inputIconeTipo = $('#input_icone_tipo');
    const inputIconeTamanho = $('#input_icone_tamanho');
    const inputIconeNome = $('#input_icone_nome');
    const inputIconeAltura = $('#input_icone_altura');
    const inputBotaoTipo = $('#input_botao_tipo');

    const blocoConteudo = $('#bloco_view_conteudo');
    const blocoLinhaPadrao = $('#bloco_linha_padrao');

    // Bloco add
    const blocoApiSim = $$('.bloco_api_sim');
    const blocoTitulo = $('.bloco_titulo');
    const blocoTexto = $('.bloco_texto');
    const blocoLink = $('.bloco_link');
    const blocoLinkTag = $('.bloco_link_tag');
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
    const blocoBotaoTipo = $('.bloco_botao_tipo');

    const botaoAtualizarGeral = $('#botao_salvar_alteracao');
    const botaoPopupAbrir = $('#botao_view_abrir');
    const botaoPopupSalvar = $('#botao_add_html');
    const PopupAdd = new Popup('Adicionar', 'bloco_view_add', true, false);

    const botaoMargem = $('#botao_abrir_margem');
    botaoMargem.evento('click', () => {
        blocoConteudo.classe('margem_ativa');
    });

    const listaAtual = {};

    const buscarHtml = async () => {
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/' + app,
            {
                pagina: id,
                indice: 'listar-html',
            },
            'Erro ao buscar HTML, recarregue a página e tente novamente.'
        );

        Loading.hide();

        blocoConteudo.html('');
        if (false === resposta) {
            return;
        }

        for (const item of resposta.dado) {
            montarArticle(blocoConteudo, item);
        }
    };
    buscarHtml();

    const montarArticle = (bloco, item) => {
        const clone = blocoLinhaPadrao.clonar();
        clone.attr({
            'data-id': item.id,
            id: 'bloco_item_' + item.id,
        });

        const blocoNovo = $('.lista', clone);
        if (typeof item['lista'] === 'object' && Object.keys(item['lista']).length > 0) {
            for (const [chave, itemNovo] of Object.entries(item['lista'])) {
                montarArticle(blocoNovo, itemNovo);
            }
        }

        const margemLista = $$('.margem_input', clone);
        const margemTopo = $('.margem_topo', clone);
        const margemDireita = $('.margem_Direita', clone);
        const margemBaixo = $('.margem_baixo', clone);
        const margemEsquerda = $('.margem_esquerda', clone);

        margemLista.evento('change', (e, item) => {
            item.classe('margem_ativa', !vazio(item.valor()));
            botaoAtualizarGeral.aparecer();
        });
        if (!vazio(item.margem_topo)) {
            margemTopo.valor(item.margem_topo);
            margemTopo.classe('margem_ativa', true);
        }
        if (!vazio(item.margem_direita)) {
            margemDireita.valor(item.margem_direita);
            margemDireita.classe('margem_ativa', true);
        }
        if (!vazio(item.margem_baixo)) {
            margemBaixo.valor(item.margem_baixo);
            margemBaixo.classe('margem_ativa', true);
        }
        if (!vazio(item.margem_esquerda)) {
            margemEsquerda.valor(item.margem_esquerda);
            margemEsquerda.classe('margem_ativa', true);
        }

        $('header h1', clone).texto(item.titulo_interno);
        bloco.aparecer();
        bloco.final(clone);
        listaAtual[item.id] = item;
        adicionarDragDrop(blocoNovo);
    };

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

    const setarBotaoTipo = () => {
        if (inputBotaoTipo.valor() == 'voltar') {
            inputTitulo.valor('');
            blocoTitulo.sumir();
            return;
        }
        blocoTitulo.aparecer();
    };
    inputBotaoTipo.evento('formChange', () => {
        setarBotaoTipo();
    });

    const abrirEditar = article => {
        const id = article.attr('data-id');
        const item = listaAtual[id];
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
        inputStatus.valor(item.status == 'sim');
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
        inputBotaoTipo.valor(item.botao_tipo || '');
        PopupAdd.abrir();
    };

    const adicionarDragDrop = bloco => {
        new DragDrop()
            .grupo('.conteudo_drag')
            .bloco(bloco)
            .botao('.drag')
            .item('article')
            .eventoMover(() => {
                botaoAtualizarGeral.aparecer();
            })
            .iniciar();
    };
    adicionarDragDrop(blocoConteudo);

    botaoPopupSalvar.evento('click', async () => {
        const tipo = inputTipo.valor();
        if (!(await validarDadoPopup(tipo))) {
            return;
        }
        const add = vazio(inputId.valor());
        const idNovo = !add ? inputId.valor() : '';
        const tituloInterno = inputTituloInterno.valor();
        const blocoPai = add ? blocoListaAtual.closest('.item_pai') : null;
        const pai = blocoPai ? blocoPai.attr('data-id') : '';
        const bodyReal = adicionarBody(tipo, pai);
        const body = bodyReal;
        body['indice'] = add ? 'salvar-html' : 'atualizar-html';
        if (!add) {
            body['id'] = idNovo;
        } else {
            body['pagina'] = id;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/' + app,
            body,
            'Erro ao buscar HTML, recarregue a página e tente novamente.'
        );
        Loading.hide();

        if (false === resposta) {
            return;
        }

        if (add) {
            bodyReal.id = resposta.dado.id;
            montarArticle(blocoListaAtual, bodyReal);
        } else {
            listaAtual[idNovo] = bodyReal;
            $('#bloco_item_' + idNovo + ' header h1').texto(tituloInterno);
        }
        PopupAdd.fechar();
    });

    const adicionarBody = (tipo, pai) => {
        return {
            pai,
            tipo: tipo,
            local: inputLocal.valor(),
            titulo: JSON.stringify(inputTitulo.valor()),
            status: inputStatus.valor() == 'sim' ? 'sim' : 'nao',
            texto: JSON.stringify(inputTexto.valor()),
            link: inputLink.valor(),
            target: inputTarget.valor(),
            tabela: JSON.stringify(inputTabela.valor()),
            editor: inputEditor.valor(),
            /* eslint-disable */
            titulo_interno: inputTituloInterno.valor(),
            imagem_arquivo: inputImagemArquivo.valor(),
            imagem_altura: inputImagemAltura.valor(),
            icone_tipo: inputIconeTipo.valor(),
            icone_tamanho: inputIconeTamanho.valor(),
            icone_nome: inputIconeNome.valor(),
            icone_altura: inputIconeAltura.valor(),
            lista_tipo: inputListaTipo.valor(),
            lista_valor: JSON.stringify(inputListaValor.valor()),
            link_empresa: JSON.stringify(inputLinkEmpresa.valor()),
            div_direcao: inputDivDirecao.valor(),
            div_posicao: inputDivPosicao.valor(),
            api_status: inputApiStatus.valor() == 'sim' ? 'sim' : 'nao',
            api_metodo: inputApiMetodo.valor(),
            api_body: JSON.stringify(inputApiBody.valor()),
            api_uri: inputApiUri.valor(),
            botao_tipo: inputBotaoTipo.valor(),
            /* eslint-enable */
        };
    };

    const validarDadoPopup = tipo => {
        return new Promise(resolve => {
            const apiStatus = inputApiStatus.valor() == 'sim';
            const iconeTipo = inputIconeTipo.valor();
            const eBotao =
                tipo == 'botao' || tipo == 'botao-empresa' || tipo == 'botao-destaque' || tipo == 'botao-fixo';
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
                inputBotaoTipo.valor() != 'voltar' &&
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
            } else if (eBotao && vazio(inputBotaoTipo.valor())) {
                mensagem = 'Escolha um tipo para o botão.';
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

    inputTipo.evento('formChange', () => {
        limparObrigatorio();
        const valor = inputTipo.valor();
        setarTipo(valor);
    });

    inputApiStatus.evento('change', () => {
        blocoApiSim.classe('display_none', !inputApiStatus.checked);
    });

    blocoConteudo.evento('click', e => {
        if (e.target.classe('editar', '?') || e.target.closest('.editar')) {
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
            deletarHtml(bloco);
        }
    });
    const deletarHtml = async bloco => {
        const id = bloco.attr('data-id');
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/' + app,
            {
                id: id,
                indice: 'deletar-html',
            },
            'Erro ao deletar HTML.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        bloco.remove();
    };

    const setarTipo = valor => {
        if (valor == 'titulo-texto') {
            blocoTitulo.aparecer();
            blocoTexto.aparecer();
        } else if (valor == 'titulo' || valor == 'subtitulo') {
            blocoTitulo.aparecer();
        } else if (valor == 'texto') {
            blocoTexto.aparecer();
        } else if (valor == 'botao' || valor == 'botao-destaque') {
            blocoTitulo.aparecer();
            blocoLink.aparecer();
            blocoTarget.aparecer();
            blocoBotaoTipo.aparecer();
        } else if (valor == 'botao-fixo') {
            blocoTitulo.aparecer();
            blocoLink.aparecer();
            blocoTarget.aparecer();
        } else if (valor == 'relacionado') {
            blocoTitulo.aparecer();
            blocoLink.aparecer();
            blocoTarget.aparecer();
            blocoApiStatus.aparecer();
        } else if (valor == 'botao-empresa') {
            blocoTitulo.aparecer();
            blocoLinkTag.aparecer();
            blocoTarget.aparecer();
            blocoBotaoTipo.aparecer();
        } else if (valor == 'div') {
            blocoDivDirecao.aparecer();
            blocoDivPosicao.aparecer();
        } else if (valor == 'tabela') {
            inputTabela.aparecer();
        } else if (valor == 'lista') {
            blocoLista.aparecer();
        } else if (valor == 'imagem') {
            blocoImagem.aparecer();
        } else if (valor == 'icone') {
            blocoIcone.aparecer();
        } else if (valor == 'campanha') {
            blocoTitulo.aparecer();
            blocoLink.aparecer();
            blocoTarget.aparecer();
            blocoApiStatus.aparecer();
        } else if (valor == 'banner') {
            blocoApiStatus.aparecer();
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
        blocoApiSim.sumir();
        blocoLista.sumir();
        blocoApiStatus.sumir();
        blocoImagem.sumir();
        blocoIcone.sumir();
        blocoBotaoTipo.sumir();
        inputLimpar.valor('');
        if (blocoBodyLista) {
            blocoBodyLista.html('');
        }
    };
});
