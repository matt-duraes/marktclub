window.addEventListener('load', () => {
    let pergunta = '';
    let resposta = '';

    const botaoSalvarResposta = $('.botao_resposta_salvar');
    const blocoRespostaPadrao = $('#bloco_linha_resposta');
    const blocoRespostaZero = $('.bloco_resposta_zero');
    const blocoRespostaLoading = $('.bloco_resposta_loading');
    const blocoPerguntaLista = $('#bloco_pergunta_lista');
    const blocoRespostaLista = $('.bloco_resposta_lista');

    const inputRespostaTitulo = $('input[name="resposta_titulo"]');
    const inputRespostaTexto = $('input[name="resposta_texto"]');
    const inputRespostaEscrever = $('input[name="resposta_escrever"]');
    const inputRespostaNulo = $('input[name="resposta_bloqueada"]');

    /*
    |--------------------------------------------------------------------------
    | ORDENAR
    |--------------------------------------------------------------------------
    */
    const ordenarResposta = () => {
        let listaIdResposta = [];
        for (const item of $$('.linha', blocoRespostaLista)) {
            listaIdResposta.push(item.attr('data-id'));
        }
        if (listaIdResposta.length == 0) {
            return;
        }
        ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'resposta-ordenar',
                pagina: 1,
                quantidade: 40,
                id: listaIdResposta,
            },
            'Ocorreu um erro ao ordenar respostas.'
        );
    };
    new DragDrop()
        .bloco(blocoRespostaLista)
        .item('.linha')
        .eventoFim(e => {
            ordenarResposta();
        })
        .botao('i.drag')
        .iniciar();

    /*
    |--------------------------------------------------------------------------
    | BUSCAR
    |--------------------------------------------------------------------------
    */
    const buscarResposta = async () => {
        blocoRespostaLoading.aparecer();

        const requisicao = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'resposta-listar',
                pergunta,
                pagina: 1,
                quantidade: 40,
            },
            'Ocorreu um erro ao listar respostas, recarregue a página e tente novamente.'
        );

        blocoRespostaLoading.sumir();
        if (false === requisicao || requisicao.dado.lista.length == 0) {
            blocoRespostaZero.aparecer();
            return;
        }
        for (const item of requisicao.dado.lista) {
            adicionarHtmlResposta(item);
        }
    };

    const PopupResposta = new Popup('Resposta', 'bloco_resposta_add', true, false);
    blocoPerguntaLista.evento('click', e => {
        const target = e.target;
        if (!target.classe('resposta', '?') && !target.closest('.resposta')) {
            return;
        }
        blocoRespostaZero.sumir();
        const bloco = target.closest('.linha');
        pergunta = bloco.attr('data-id');
        resetarResposta();
        PopupResposta.abrir();
        buscarResposta();
    });

    /*
    |--------------------------------------------------------------------------
    | ADD
    |--------------------------------------------------------------------------
    */
    botaoSalvarResposta.evento('click', async () => {
        if (inputRespostaTitulo.valor() == '') {
            Alerta.notificacao('Digite um título para a resposta.', false);
            return false;
        }

        Loading.show();
        const requisicao = await ajaxPost(
            LINK + '/app/ajax/votacao',
            pegarBodySalvar(),
            'Erro ao salvar resposta, por favor, tente novamente.'
        );
        Loading.hide();

        if (false === requisicao) {
            return;
        }

        const titulo = inputRespostaTitulo.valor();
        resetarResposta(false);
        blocoRespostaZero.sumir();
        if (resposta == '') {
            Alerta.notificacao('Resposta salva com sucesso!', true);
            adicionarHtmlResposta(requisicao.dado);
            return;
        }
        Alerta.notificacao('Resposta atualizada com sucesso!', true);
        atualizarHtmlResposta(resposta, titulo);
    });

    const pegarBodySalvar = () => {
        const indice = resposta == '' ? 'resposta-salvar' : 'resposta-atualizar';
        const body = {
            indice,
            titulo: inputRespostaTitulo.valor(),
            texto: inputRespostaTexto.valor(),
            /* eslint-disable */
            escrever_voto: inputRespostaNulo.checked ? 'sim' : 'nao',
            voto_nulo: inputRespostaEscrever.checked ? 'sim' : 'nao',
            /* eslint-enable */
        };

        if (resposta != '') {
            body.id = resposta;
        } else {
            body.pergunta = pergunta;
        }
        return body;
    };

    /*
    |--------------------------------------------------------------------------
    | BOTÕES RESPOSTA
    |--------------------------------------------------------------------------
    */
    blocoRespostaLista.evento('click', (e, item) => {
        const target = e.target;
        if (target.classe('deletar', '?') || target.closest('.deletar')) {
            deletarResposta(target.closest('.linha').attr('data-id'));
        } else if (target.classe('editar', '?') || target.closest('.editar')) {
            editarResposta(target.closest('.linha').attr('data-id'));
        }
    });

    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */
    const editarResposta = async id => {
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'resposta-buscar',
                id,
            },
            'Erro ao buscar resposta.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        adicionarValorResposta(resposta.dado);
    };

    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    */
    const deletarResposta = async id => {
        if (
            !(await Alerta.confirmar(
                'Deletar resposta',
                'Tem certeza que deseja deletar essa resposta? Essa ação não poderá ser desfeita.',
                '!'
            ))
        ) {
            return;
        }

        Loading.show();
        const requisicao = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'resposta-deletar',
                id,
            },
            'Erro ao deletar resposta.'
        );
        Loading.hide();
        if (false === requisicao) {
            return;
        }

        Alerta.notificacao('Resposta deletada com sucesso!', true);
        const bloco = $('#resposta_' + id);
        if (bloco) {
            bloco.remove();
        }
        if ($$('.linha', blocoRespostaLista).length == 0) {
            blocoRespostaZero.aparecer();
        }
    };

    /*
    |--------------------------------------------------------------------------
    | FUNÇÕES
    |--------------------------------------------------------------------------
    */
    const adicionarValorResposta = item => {
        resposta = item.id;
        inputRespostaTitulo.valor(item.titulo);
        inputRespostaTexto.valor(item.texto);
        inputRespostaEscrever.valor(item.escrever_voto);
        inputRespostaNulo.valor(item.voto_nulo);
    };
    const resetarResposta = limparHtml => {
        resposta = '';
        if (limparHtml === undefined) {
            blocoRespostaLista.html('');
        }
        inputRespostaTitulo.valor('');
        inputRespostaTexto.valor('');
        inputRespostaEscrever.valor(false);
        inputRespostaNulo.valor(false);
    };
    const adicionarHtmlResposta = item => {
        const bloco = blocoRespostaPadrao.clonar();
        bloco.attr('id', 'resposta_' + item.id);
        bloco.attr('data-id', item.id);
        $('h1', bloco).texto(item.titulo);
        blocoRespostaLista.final(bloco);
    };
    const atualizarHtmlResposta = (id, titulo) => {
        let bloco = $('#resposta_' + id);
        if (!bloco) {
            return;
        }
        $('h1', bloco).texto(titulo);
    };
});
