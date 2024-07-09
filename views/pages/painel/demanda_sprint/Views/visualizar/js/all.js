window.addEventListener('load', () => {
    const botaoIniciar = $('#botao_sprint_iniciar');
    const botaoConcluir = $('#botao_sprint_concluir');
    const botaoCancelar = $('#botao_sprint_cancelar');

    const PopupInicar = new Popup('bloco_popup_iniciar');
    const PopupConcluir = new Popup('bloco_popup_concluir');
    const PopupCancelar = new Popup('bloco_popup_cancelar');
    botaoIniciar.evento('click', () => {
        //
    });
    botaoConcluir.evento('click', () => {
        //
    });

    botaoCancelar.evento('click', async () => {
        if (
            !(await Alerta.confirmar(
                'Cancelar sprint',
                'Tem certeza que deseja cancelar essa sprint? Todas as demandas não concluídas irão voltar para o backlog.'
            ))
        ) {
            return;
        }
        botaoCancelar.remover();
    });
});
