const demandaCancelar = () => {
    const inputMotivo = $('#input_demanda_cancelar_motivo');
    const botaoSalvar = $('#botao_demanda_cancelar_confirmar');

    inputMotivo.focus();
    formValue(inputMotivo, '');

    botaoSalvar.addEventListener('click', async () => {
        if (inputMotivo.value == '') {
            Alerta.notificacao('Preencha o motivo do cancelamento para continuar.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/demanda/demanda-cancelar/' + idDemanda,
            {
                motivo: inputMotivo.value,
            },
            'Ocorreu um erro ao cancelar demanda, por favor, tente novamente.'
        );
        Loading.hide();
        if (false == resposta) {
            return;
        }
        PopupDemandaCancelar.fechar();
        PaginaFechar.fechar();
        const blocoItem = $('.bloco_tarefa_item[data-id="' + idDemanda + '"]');
        if (!blocoItem) {
            return;
        }
        const coluna = blocoItem.closest('.bloco_coluna');
        blocoItem.remove();
        contarTarefaDemanda(coluna);
        const lista = coluna.querySelectorAll('.bloco_tarefa_item');
        if (lista.length > 0) {
            return;
        }
        const zero = coluna.querySelector('.tarefa_zero');
        if (zero) {
            zero.classList.remove('display_none');
        }
    });
};
