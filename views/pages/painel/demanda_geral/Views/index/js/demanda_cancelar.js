const demandaCancelar = () => {
    const inputMotivo = $('#input_demanda_cancelar_motivo');
    const botaoSalvar = $('#botao_demanda_cancelar_confirmar');

    inputMotivo.focus();
    formValue(inputMotivo, '');

    botaoSalvar.addEventListener('click', async () => {
        const motivo = inputMotivo.valor();
        if (vazio(motivo)) {
            Alerta.notificacao('Preencha o motivo do cancelamento para continuar.', false);
            return;
        }

        const blocoDemanda = $('#id_demanda_' + idDemanda);
        if (blocoDemanda && blocoDemanda.classe('na_sprint', '?')) {
            fazerRequestAdicionarRemoverDemandaSprint('remover', idDemanda, idSprint, motivo);
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/demanda/demanda-cancelar/' + idDemanda,
            {
                motivo,
            },
            'Ocorreu um erro ao cancelar demanda, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        PopupDemandaCancelar.fechar();
        PaginaFechar.fechar();

        if (!blocoDemanda) {
            return;
        }

        if (blocoSprintLista) {
            acaoAposCancelarDemandaLista(blocoDemanda);
            return;
        }
        acaoAposCancelarDemandaQuadro(blocoDemanda);
    });

    const acaoAposCancelarDemandaLista = bloco => {
        bloco.remove();
        const quantidade = $$('.linha', blocoSprintLista).length;
        if (quantidade > 0) {
            return;
        }
        const zero = $('.tarefa_zero', blocoSprintLista);
        if (zero) {
            zero.aparecer();
        }
        if (blocoBotaoSprint) {
            blocoBotaoSprint.sumir();
        }
    };

    const acaoAposCancelarDemandaQuadro = bloco => {
        const coluna = bloco.closest('.bloco_coluna');
        bloco.remove();
        contarTarefaDemanda(coluna);
        const lista = $$('.bloco_kambam_item', coluna);
        if (lista.length > 0) {
            return;
        }
        const zero = $('.tarefa_zero', coluna);
        if (zero) {
            zero.aparecer();
        }
    };
};
