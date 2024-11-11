// @template "painel"

window.addEventListener('load', () => {
    const app = 'view-pagina';
    const inputLimpar = $$(`
        #input_local, #input_titulo_interno, #input_titulo, #input_texto,
        #input_link, #input_target, #input_status, #input_api_status, #input_api_uri,
        #input_api_metodo, .bloco_api_body .input_geral, #input_id, #input_div_posicao,
        #input_link_empresa, #input_editor, #input_lista_valor, #input_lista_tipo,
        #input_botao_tipo, #input_imagem_altura, #input_div_direcao, #input_margem_topo,
        #input_margem_esquerda, #input_margem_direita, #input_margem_baixo, #input_icone_tipo,
        #input_icone_tamanho, #input_icone_nome, #input_icone_altura, #input_icone_cor,
        #input_icone_bg, #input_icone_borda_cor
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
    const inputIconeTipo = $('#input_icone_tipo');
    const inputIconeTamanho = $('#input_icone_tamanho');
    const inputIconeNome = $('#input_icone_nome');
    const inputIconeAltura = $('#input_icone_altura');
    const inputIconeCor = $('#input_icone_cor');
    const inputIconeBg = $('#input_icone_bg');
    const inputIconeBordaCor = $('#input_icone_borda_cor');
    const inputBotaoTipo = $('#input_botao_tipo');
    const inputEmpresaAtiva = $$('#bloco_empresa_ativa input');
    const inputEmpresaInativa = $$('#bloco_empresa_inativa input');

    const blocoConteudo = $('#bloco_view_conteudo');
    const blocoLinhaPadrao = $('#bloco_linha_padrao');

    // Bloco add
    const blocoTipo = $('.bloco_tipo');
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
    const blocoIconeBordaCor = $('.bloco_icone_borda_cor');
    const blocoBotaoTipo = $('.bloco_botao_tipo');
    const blocoEmpresaAtiva = $('#bloco_empresa_ativa');
    const blocoEmpresaInativa = $('#bloco_empresa_inativa');

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
        const naoPodeTerLista = inArray(item.tipo, [
            'botao',
            'botao-empresa',
            'botao-destaque',
            'botao-fixo',
            'linha',
            'imagem',
            'icone',
            'banner',
            'campanha',
            'titulo',
            'subtitulo',
            'texto',
            'relacionado',
            'editor',
            'tabela',
            'lista',
        ]);
        if (naoPodeTerLista) {
            blocoNovo.remove();
        } else if (typeof item['lista'] === 'object' && Object.keys(item['lista']).length > 0) {
            for (const [chave, itemNovo] of Object.entries(item['lista'])) {
                montarArticle(blocoNovo, itemNovo);
            }
        }

        const filhoLista = clone.children;
        let margemTopo,
            margemDireita,
            margemBaixo,
            margemEsquerda,
            margemTopoInput,
            margemDireitaInput,
            margemBaixoInput,
            margemEsquerdaInput;
        for (const filhoItem of filhoLista) {
            if (filhoItem.classe('margem_topo', '?')) {
                margemTopo = filhoItem;
                margemTopoInput = $('input', filhoItem);
            } else if (filhoItem.classe('conteudo_linha', '?')) {
                const filhoLinhaLista = filhoItem.children;
                for (const filhoLinha of filhoLinhaLista) {
                    if (filhoLinha.classe('margem_direita', '?')) {
                        margemDireita = filhoLinha;
                        margemDireitaInput = $('input', filhoLinha);
                    } else if (filhoLinha.classe('margem_esquerda', '?')) {
                        margemEsquerda = filhoLinha;
                        margemEsquerdaInput = $('input', filhoLinha);
                    }
                }
            } else if (filhoItem.classe('margem_baixo', '?')) {
                margemBaixo = filhoItem;
                margemBaixoInput = $('input', filhoItem);
            }
        }

        const conteudoGeral = $('.conteudo_geral', clone);
        for (const margemEvento of [margemTopoInput, margemBaixoInput, margemEsquerdaInput, margemDireitaInput]) {
            margemEvento.evento('change', () => {
                const blocoMargemAtual = margemEvento.closest('.margem');
                blocoMargemAtual.classe('margem_ativa', !vazio(margemEvento.valor()));

                botaoAtualizarGeral.aparecer();
                const margemEsquerdaAtiva = margemEsquerda.classe('margem_ativa', '?');
                const margemDireitaAtiva = margemDireita.classe('margem_ativa', '?');

                conteudoGeral.classe('margem_esquerda_ativa', false);
                conteudoGeral.classe('margem_direita_ativa', false);
                conteudoGeral.classe('margem_tudo_ativa', false);

                if (margemEsquerdaAtiva && margemDireitaAtiva) {
                    conteudoGeral.classe('margem_tudo_ativa', true);
                    return;
                }
                if (margemEsquerdaAtiva) {
                    conteudoGeral.classe('margem_esquerda_ativa', true);
                    conteudoGeral.classe('margem_direita_ativa', false);
                }
                if (margemDireitaAtiva) {
                    conteudoGeral.classe('margem_direita_ativa', true);
                    conteudoGeral.classe('margem_esquerda_ativa', false);
                }
            });
        }
        if (!vazio(item.margem_topo)) {
            margemTopoInput.valor(item.margem_topo);
            margemTopo.classe('margem_ativa', true);
        }
        if (!vazio(item.margem_direita)) {
            margemDireitaInput.valor(item.margem_direita);
            margemDireita.classe('margem_ativa', true);
        }
        if (!vazio(item.margem_baixo)) {
            margemBaixoInput.valor(item.margem_baixo);
            margemBaixo.classe('margem_ativa', true);
        }
        if (!vazio(item.margem_esquerda)) {
            margemEsquerdaInput.valor(item.margem_esquerda);
            margemEsquerda.classe('margem_ativa', true);
        }

        $('header h1', clone).texto(item.titulo_interno);
        $('header .status', clone).classe('status_' + item.status);
        $('header h1', clone).evento('dblclick', () => {
            clone.classe('margem_ativa');
        });

        bloco.aparecer();
        bloco.final(clone);
        listaAtual[item.id] = item;
        if (!naoPodeTerLista) {
            adicionarDragDrop(blocoNovo);
        }
    };

    let blocoListaAtual;
    botaoPopupAbrir.evento('click', () => {
        addSubGrupo(blocoConteudo);
    });

    const addSubGrupo = bloco => {
        blocoTipo.aparecer();
        inputTipo.valor('');
        PopupAdd.abrir();
        blocoListaAtual = bloco;
        limparObrigatorio();
    };

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

        if (item.botao_tipo == 'voltar') {
            blocoTitulo.sumir();
        }
        if (item.tipo == 'icone') {
            mudarIconeTipo();
        }
        const eBotao = verificarSeBotao(item.tipo);
        const eDiv = verificarSeDiv(item.tipo);
        blocoTipo.sumir();
        inputId.valor(id);
        inputTipo.valor(item.tipo);
        inputLocal.valor(item.local);
        inputTituloInterno.valor(item.titulo_interno);
        inputTitulo.valor(item.titulo || '');
        inputTexto.valor(item.texto || '');
        inputLink.valor(item.link || '');
        inputLinkEmpresa.valor(item.link_empresa || '');
        inputTarget.valor(item.target || '');
        inputDivDirecao.valor(eDiv && item.div_direcao ? item.div_direcao : '');
        inputDivPosicao.valor(eDiv && item.div_posicao ? item.div_posicao : '');
        inputStatus.valor(inArray(item.status, ['sim', 1]) ? 'sim' : 'nao');
        inputApiStatus.valor(item.api_status || 'nao');
        blocoApiSim.classe('display_none', !inArray(apiStatus, ['sim', 1]));
        inputApiMetodo.valor(item.api_metodo || '');
        inputApiBody.valor(item.api_body || '');
        inputApiUri.valor((item.api_uri || '').replace(/^\//, ''));
        inputTabela.valor(item.tipo == 'tabela' && item.tabela ? item.tabela : '');
        inputEditor.valor(item.tipo == 'editor' && item.editor ? item.editor : '');
        inputListaTipo.valor(item.tipo == 'lista' && item.lista_tipo ? item.lista_tipo : '');
        inputListaValor.valor(item.tipo == 'lista' && item.lista_valor ? item.lista_valor : '');
        inputImagemArquivo.valor(item.tipo == 'imagem' && item.imagem_arquivo ? item.imagem_arquivo : '');
        inputImagemAltura.valor(item.tipo == 'imagem' && item.imagem_altura ? item.imagem_altura : '');
        inputIconeTipo.valor(item.tipo == 'icone' && item.icone_tipo ? item.icone_tipo : '');
        inputIconeTamanho.valor(item.tipo == 'icone' && item.icone_tamanho ? item.icone_tamanho : '');
        inputIconeNome.valor(item.tipo == 'icone' && item.icone_nome ? item.icone_nome : '');
        inputIconeAltura.valor(item.tipo == 'icone' && item.icone_altura ? item.icone_altura : '');
        inputIconeCor.valor(item.tipo == 'icone' && item.icone_cor ? item.icone_cor : '');
        inputIconeBg.valor(item.tipo == 'icone' && item.icone_bg ? item.icone_bg : '');
        inputIconeBordaCor.valor(item.tipo == 'icone' && item.icone_borda_cor ? item.icone_borda_cor : '');
        inputBotaoTipo.valor(eBotao && item.botao_tipo ? item.botao_tipo : '');
        setarValorCheckbox(blocoEmpresaAtiva, item.empresa_ativa);
        setarValorCheckbox(blocoEmpresaInativa, item.empresa_inativa);
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

    botaoAtualizarGeral.evento('click', async () => {
        const lista = $$('article', blocoConteudo);
        const quantidade = lista.length;
        if (quantidade == 0) {
            botaoAtualizarGeral.sumir();
            return;
        }
        Loading.show();
        let i = 1;
        const grupo = {};
        for (const item of lista) {
            const filhoLista = item.children;
            let margemTopo, margemDireita, margemBaixo, margemEsquerda;
            for (const filhoItem of filhoLista) {
                if (filhoItem.classe('margem_topo', '?')) {
                    margemTopo = $('input', filhoItem);
                } else if (filhoItem.classe('conteudo_linha', '?')) {
                    const filhoLinhaLista = filhoItem.children;
                    for (const filhoLinha of filhoLinhaLista) {
                        if (filhoLinha.classe('margem_direita', '?')) {
                            margemDireita = $('input', filhoLinha);
                        } else if (filhoLinha.classe('margem_esquerda', '?')) {
                            margemEsquerda = $('input', filhoLinha);
                        }
                    }
                } else if (filhoItem.classe('margem_baixo', '?')) {
                    margemBaixo = $('input', filhoItem);
                }
            }

            const blocoPai = item.parentElement.closest('.item_pai');
            grupo[i] = {
                id: item.attr('data-id'),
                pai: blocoPai ? blocoPai.attr('data-id') : '',
                ordem: i,
                /* eslint-disable */
                margem_topo: margemTopo.valor(),
                margem_direita: margemDireita.valor(),
                margem_baixo: margemBaixo.valor(),
                margem_esquerda: margemEsquerda.valor(),
                /* eslint-enable */
            };
            ++i;
        }

        const resposta = await ajaxPost(
            LINK + '/app/ajax/' + app,
            {
                indice: 'ordem-html',
                grupo: JSON.stringify(grupo),
            },
            'Erro ao atualizar HTML, recarregue a página e tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        botaoAtualizarGeral.sumir();
    });

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
        const bodyReal = adicionarBody(tipo);

        const body = bodyReal;
        body['indice'] = add ? 'salvar-html' : 'atualizar-html';
        if (add) {
            body['pagina'] = id;
            body['pai'] = pai;
        } else {
            body['id'] = idNovo;
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
            const status = $('#bloco_item_' + idNovo + ' header .status');
            status.classe('status_nao', false);
            status.classe('status_sim', false);
            status.classe('status_' + body['status'], true);
        }
        PopupAdd.fechar();
    });

    const adicionarBody = tipo => {
        const titulo = inputTitulo.valor();
        const texto = inputTexto.valor();
        const tabela = inputTabela.valor();
        const listaValor = inputListaValor.valor();
        const linkEmpresa = inputLinkEmpresa.valor();
        const apiBody = inputApiBody.valor();
        return {
            tipo: tipo,
            local: inputLocal.valor(),
            titulo: !vazio(titulo) ? JSON.stringify(titulo) : null,
            status: inArray(inputStatus.valor(), ['sim', 1]) ? 'sim' : 'nao',
            texto: !vazio(texto) ? JSON.stringify(texto) : null,
            link: inputLink.valor(),
            target: inputTarget.valor(),
            tabela: !vazio(tabela) ? JSON.stringify(inputTabela.valor()) : null,
            editor: inputEditor.valor(),
            /* eslint-disable */
            titulo_interno: inputTituloInterno.valor(),
            imagem_arquivo: inputImagemArquivo.valor(),
            imagem_altura: inputImagemAltura.valor(),
            icone_tipo: inputIconeTipo.valor(),
            icone_tamanho: inputIconeTamanho.valor(),
            icone_nome: inputIconeNome.valor(),
            icone_altura: inputIconeAltura.valor(),
            icone_cor: inputIconeCor.valor(),
            icone_bg: inputIconeBg.valor(),
            icone_borda_cor: inputIconeBordaCor.valor(),
            lista_tipo: inputListaTipo.valor(),
            lista_valor: !vazio(listaValor) ? JSON.stringify(listaValor) : null,
            link_empresa: !vazio(linkEmpresa) ? JSON.stringify(linkEmpresa) : null,
            div_direcao: inputDivDirecao.valor(),
            div_posicao: inputDivPosicao.valor(),
            api_status: inArray(inputApiStatus.valor(), ['sim', 1]) ? 'sim' : 'nao',
            api_metodo: inputApiMetodo.valor(),
            api_body: !vazio(apiBody) ? JSON.stringify(apiBody) : null,
            api_uri: inputApiUri.valor(),
            botao_tipo: inputBotaoTipo.valor(),
            empresa_ativa: pegarValorCheckbox(blocoEmpresaAtiva),
            empresa_inativa: pegarValorCheckbox(blocoEmpresaInativa),
            /* eslint-enable */
        };
    };

    const validarDadoPopup = tipo => {
        return new Promise(resolve => {
            const apiStatus = inArray(inputApiStatus.valor(), ['sim', 1]);
            const iconeTipo = inputIconeTipo.valor();
            const eBotao = verificarSeBotao(tipo);
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
            } else if (eBotao && tipo != 'botao-fixo' && vazio(inputBotaoTipo.valor())) {
                mensagem = 'Escolha um tipo para o botão.';
            } else if ((tipo == 'titulo-texto' || tipo == 'texto') && vazio(inputTexto.valor())) {
                mensagem = 'Digite um texto para continuar.';
            } else if (tipo == 'magem') {
                mensagem = 'Digite uma margem para continuar.';
            } else if (verificarSeDiv(tipo) && vazio(inputDivDirecao.valor())) {
                mensagem = 'Escolha a direção do conteudo da div para continuar.';
            } else if (verificarSeDiv(tipo) && vazio(inputDivPosicao.valor())) {
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
            } else if (tipo == 'icone' && vazio(inputIconeTamanho.valor())) {
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

    const verificarSeBotao = tipo => {
        return inArray(tipo, ['botao', 'botao-empresa', 'botao-destaque', 'botao-fixo']);
    };
    const verificarSeDiv = tipo => {
        return inArray(tipo, ['div', 'bloco', 'resto']);
    };

    inputTipo.evento('formChange', () => {
        limparObrigatorio();
        const valor = inputTipo.valor();
        setarTipo(valor);
    });

    const mudarIconeTipo = () => {
        if (inputIconeTipo.valor() == 'normal') {
            blocoIconeBordaCor.sumir();
            return;
        }
        blocoIconeBordaCor.aparecer();
    };
    inputIconeTipo.evento('formChange', () => {
        mudarIconeTipo();
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
        } else if (verificarSeDiv(valor)) {
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
        inputTabela.valor('');
        blocoEmpresaAtiva.classe('oculto', true);
        blocoEmpresaInativa.classe('oculto', true);
        inputEmpresaAtiva.marcar(false);
        inputEmpresaInativa.marcar(false);
        if (blocoBodyLista) {
            blocoBodyLista.html('');
        }
    };

    const blocoCheckboxLista = $$('.bloco_checkbox');
    for (const bloco of blocoCheckboxLista) {
        const botaoMais = $('.botao_mais', bloco);
        const botaoMenos = $('.botao_menos', bloco);
        botaoMais.evento('click', () => {
            bloco.classe('oculto', false);
        });
        botaoMenos.evento('click', () => {
            bloco.classe('oculto', true);
        });
    }

    const pegarValorCheckbox = bloco => {
        const lista = $$('input:checked', bloco);
        if (lista.length == 0) {
            return null;
        }
        const retorno = [];
        for (const empresa of lista) {
            retorno.push(empresa.value);
        }
        return JSON.stringify(retorno);
    };
    const setarValorCheckbox = (bloco, valor) => {
        valor = typeof valor === 'string' ? jsonParse(valor) : valor;
        if (vazio(valor) || typeof valor !== 'object') {
            return;
        }
        valor.forEach(id => {
            $(`input[value="${id}"]`, bloco).marcar(true);
        });
    };
    inputEmpresaAtiva.evento('change', () => {
        resetarEmpresa(blocoEmpresaAtiva, inputEmpresaInativa);
    });
    inputEmpresaInativa.evento('change', () => {
        resetarEmpresa(blocoEmpresaInativa, inputEmpresaAtiva);
    });
    const resetarEmpresa = (ativar, desativar) => {
        const lista = $$('input:checked', ativar);
        if (lista.length == 0) {
            return;
        }
        desativar.marcar(false);
    };
});
