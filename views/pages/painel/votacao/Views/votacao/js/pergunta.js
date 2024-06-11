window.addEventListener('load', () => {
    const botaoPopupPergunta = $('#botao_adicionar_pergunta');
    if (!botaoPopupPergunta) {
        return;
    }

    const botaoSalvarPergunta = $('#botao_pergunta_salvar');
    const blocoPerguntaPadrao = $('#bloco_linha_pergunta');
    const blocoPerguntaZero = $('#bloco_pergunta_zero');
    const blocoPerguntaLoading = $('#bloco_pergunta_loading');
    const blocoPerguntaLista = $('#bloco_pergunta_lista');
    const inputPerguntaTitulo = $('input[name="pergunta_titulo"]');
    const inputPerguntaTexto = $('textarea[name="pergunta_texto"]');
    const inputPerguntaTipo = $('input[name="pergunta_tipo"]');
    const inputPerguntaNulo = $('input[name="pergunta_nulo"]');
    const votacao = $('#input_visualizar_id').valor();
    let pergunta = '';

    /*
    |--------------------------------------------------------------------------
    | ORDENAR
    |--------------------------------------------------------------------------
    */
    const ordenarPergunta = () => {
        let listaIdPergunta = [];
        for (const item of $$('.linha', blocoPerguntaLista)) {
            listaIdPergunta.push(item.attr('data-id'));
        }
        if (listaIdPergunta.length == 0) {
            return;
        }
        ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'pergunta-ordenar',
                pagina: 1,
                quantidade: 40,
                id: listaIdPergunta,
            },
            'Ocorreu um erro ao ordenar perguntas.'
        );
    };
    new DragDrop()
        .bloco(blocoPerguntaLista)
        .item('.linha')
        .eventoFim(e => {
            ordenarPergunta();
        })
        .botao('i.drag')
        .iniciar();

    /*
    |--------------------------------------------------------------------------
    | BUSCAR
    |--------------------------------------------------------------------------
    */
    const buscarPergunta = async () => {
        botaoPopupPergunta.classe('loading', true);
        const resposta = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'pergunta-listar',
                votacao,
                pagina: 1,
                quantidade: 40,
            },
            'Ocorreu um erro ao listar tarefas, recarregue a página e tente novamente.'
        );
        botaoPopupPergunta.classe('loading', false);
        blocoPerguntaLoading.sumir();
        if (false === resposta || resposta.dado.lista.length == 0) {
            blocoPerguntaZero.aparecer();
            return;
        }
        for (const item of resposta.dado.lista) {
            adicionarHtmlPergunta(item);
        }
    };
    buscarPergunta();

    /*
    |--------------------------------------------------------------------------
    | ADD
    |--------------------------------------------------------------------------
    */
    const PopupPergunta = new Popup('Pergunta', 'bloco_pergunta_add', true, false);
    botaoPopupPergunta.evento('click', () => {
        pergunta = '';
        resetarPergunta();
        PopupPergunta.abrir();
    });

    botaoSalvarPergunta.evento('click', async () => {
        if (!(await validarPergunta())) {
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/votacao',
            pegarBodySalvar(),
            'Erro ao salvar pergunta, por favor, tente novamente.'
        );
        Loading.hide();

        if (false === resposta) {
            return;
        }
        blocoPerguntaZero.sumir();
        if (pergunta == '') {
            resetarPergunta();
            Alerta.notificacao('Pergunta salvar com sucesso!', true);
            adicionarHtmlPergunta(resposta.dado);
            return;
        }
        Alerta.notificacao('Pergunta atualizada com sucesso!', true);
        atualizarHtmlPergunta(pergunta, inputPerguntaTitulo.valor());
    });

    const pegarBodySalvar = () => {
        const indice = pergunta == '' ? 'pergunta-salvar' : 'pergunta-atualizar';
        const body = {
            indice,
            titulo: inputPerguntaTitulo.valor(),
            texto: inputPerguntaTexto.valor(),
            tipo: inputPerguntaTipo.valor(),
            /* eslint-disable */
            pode_nulo: inputPerguntaNulo.checked ? 'sim' : 'nao',
            /* eslint-enable */
        };

        if (pergunta != '') {
            body.id = pergunta;
        } else {
            body.votacao = votacao;
        }
        return body;
    };

    const validarPergunta = () => {
        if (inputPerguntaTitulo.valor() == '') {
            Alerta.notificacao('Digite um título para a pergunta.', false);
            return false;
        } else if (inputPerguntaTipo.valor() == '') {
            Alerta.notificacao('Escolha um tipo para a pertunta.', false);
            return false;
        }
        return true;
    };

    /*
    |--------------------------------------------------------------------------
    | BOTÕES PERGUNTA
    |--------------------------------------------------------------------------
    */
    blocoPerguntaLista.evento('click', (e, item) => {
        const target = e.target;
        if (target.classe('editar', '?') || target.closest('.editar')) {
            abrirPopupEdicao(target.closest('.linha').attr('data-id'));
        } else if (target.classe('deletar', '?') || target.closest('.deletar')) {
            deletarPergunta(target.closest('.linha').attr('data-id'));
        }
    });

    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    */
    const deletarPergunta = async id => {
        if (
            !(await Alerta.confirmar(
                'Deletar pergunta',
                'Tem certeza que deseja deletar essa pergunta? Essa ação não poderá ser desfeita.',
                '!'
            ))
        ) {
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'pergunta-deletar',
                id,
            },
            'Erro ao deletar pergunta.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }

        Alerta.notificacao('Pergunta deletada com sucesso!', true);
        const bloco = $('#pergunta_' + id);
        if (bloco) {
            bloco.remove();
        }

        if ($$('.linha', blocoPerguntaLista).length == 0) {
            blocoPerguntaZero.aparecer();
        }
    };

    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */
    const abrirPopupEdicao = async id => {
        resetarPergunta();
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'pergunta-buscar',
                id,
            },
            'Erro ao buscar pergunta.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        adicionarValorPergunta(resposta.dado);
        PopupPergunta.abrir();
    };

    /*
    |--------------------------------------------------------------------------
    | FUNÇÕES
    |--------------------------------------------------------------------------
    */
    const adicionarValorPergunta = item => {
        pergunta = item.id;
        inputPerguntaTitulo.valor(item.titulo);
        inputPerguntaTexto.valor(item.texto);
        inputPerguntaTipo.valor(item.tipo);
        inputPerguntaNulo.valor(item.pode_nulo);
    };
    const resetarPergunta = () => {
        pergunta = '';
        inputPerguntaTitulo.valor('');
        inputPerguntaTexto.valor('');
        inputPerguntaTipo.valor('');
        inputPerguntaNulo.valor(false);
    };

    const adicionarHtmlPergunta = item => {
        const bloco = blocoPerguntaPadrao.clonar();
        bloco.attr('id', 'pergunta_' + item.id);
        bloco.attr('data-id', item.id);
        $('h1', bloco).texto(item.titulo);
        blocoPerguntaLista.final(bloco);
    };
    const atualizarHtmlPergunta = (id, titulo) => {
        let bloco = $('#pergunta_' + id);
        if (!bloco) {
            return;
        }
        $('h1', bloco).texto(titulo);
    };
});
