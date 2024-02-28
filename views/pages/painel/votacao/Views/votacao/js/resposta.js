window.addEventListener('load', () => {
    const botaoAdicionarResposta = $('#botao_adicionar_resposta');
    const blocoRespostaPadrao = $('#bloco_linha_resposta');

    const blocoRespostaLoading = $('#bloco_resposta_zero');
    const blocoRespostaZero = $('#bloco_resposta_zero');

    const blocoRespostaLista = $('#bloco_lista_resposta');
    new DragDrop().bloco(blocoRespostaLista).item('.linha').botao('i.drag').iniciar();

    botaoAdicionarResposta.evento('click', () => {
        if (botaoAdicionarResposta.classe('loading', '?')) {
            return;
        }
        const clone = blocoRespostaPadrao.clonar();
        const titulo = $('[name="resposta_titulo"]', clone);
        blocoRespostaLista.inicio(clone);
        titulo.focus();
        blocoRespostaZero.sumir();
    });

    blocoRespostaLista.evento('click', e => {
        const target = e.target;
        if (target.classe('deletar', '?') || target.closest('.deletar')) {
            removerResposta(target.closest('.linha'));
            return;
        } else if (target.classe('editar', '?') || target.closest('.editar')) {
            const bloco = target.closest('.linha');
            editarResposta(bloco.attr('data-id'));
        }
    });

    /*
    |--------------------------------------------------------------------------
    | REMOVER RESPOSTA
    |--------------------------------------------------------------------------
    */
    const removerResposta = linha => {
        if (!linha) {
            return;
        }
        linha.remover();
        if ($$('.linha', blocoRespostaLista).length > 0) {
            return;
        }
        blocoRespostaZero.aparecer();
    };

    /*
    |--------------------------------------------------------------------------
    | EDITAR RESPOSTA
    |--------------------------------------------------------------------------
    */
    const editarResposta = async id => {
        botaoAdicionarResposta.classe('loading', true);
        blocoRespostaZero.sumir();
        blocoRespostaLoading.aparecer();
        const resposta = await ajaxPost(LINK + '/app/ajax/votacao', {
            pergunta: id,
            acao: 'pergunta',
        });
    };

    const validarResposta = () => {
        const lista = listaResposta();
        const quantidade = lista.length;
        let i = 0;
        bodyResposta = {};
        for (; i < quantidade; ++i) {
            const item = lista[i];
            const titulo = $('input[name="resposta_titulo"]', item).valor();
            if (titulo == '') {
                Alerta.notificacao('Digite o tírulo para a pergunta ' + i + ' para continuar.', false);
                return false;
            }
        }
        return true;
    };
    const listaResposta = () => {
        return $('.linha', blocoRespostaLista);
    };
});
