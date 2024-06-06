window.addEventListener('load', () => {
    const botao = $('#botao_cancelar_votacao');
    if (!botao) {
        return;
    }

    const id = $('#input_visualizar_id').valor();

    botao.evento('click', async () => {
        if (
            !(await Alerta.confirmar(
                'Cancelar',
                'Tem certeza que deseja cancelar esse item? Essa ação não poderá ser desfeita.',
                '!'
            ))
        ) {
            return;
        }
        cancelarVotacao();
    });

    const cancelarVotacao = async () => {
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'votacao-cancelar',
                id,
            },
            'Ocorreu um erro ao cancelar item.'
        );
        if (false === resposta) {
            Loading.hide();
            return;
        }
        window.location.reload();
    };
});
