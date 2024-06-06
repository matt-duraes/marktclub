window.addEventListener('load', () => {
    const botao = $('#botao_bloquear_votacao');
    if (!botao) {
        return;
    }

    const id = $('#input_visualizar_id').valor();

    botao.evento('click', async () => {
        if (
            !(await Alerta.confirmar(
                'Bloquear',
                'Tem certeza que deseja bloquear esse item, ele não poderá ser editado após o bloqueio.',
                '!'
            ))
        ) {
            return;
        }
        bloquearVotacao();
    });

    const bloquearVotacao = async () => {
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'votacao-bloquear',
                id,
            },
            'Ocorreu um erro ao bloquear item.'
        );
        if (false === resposta) {
            Loading.hide();
            return;
        }
        window.location.reload();
    };
});
