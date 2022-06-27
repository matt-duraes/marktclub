window.addEventListener('load', () => {
    const botaoLista = document.querySelectorAll('#bloco_app_visualizar .botao_status');
    if (botaoLista.length == 0) {
        return;
    }

    botaoLista.forEach(botao => {
        const status = botao.getAttribute('data-status') || '';
        const mensagem = botao.getAttribute('data-mensagem') || '';
        console.log(status);
        console.log(mensagem);
        if (status == '' || mensagem == '') {
            return;
        }
        botao.addEventListener('click', async () => {
            const resposta = await Alerta.confirmar('Confirmar mudança', mensagem, false);
            if (resposta) {
                mudarStatus(status);
            }
        });
    });

    const idVisualizar = document.querySelector('#input_visualizar_id').value;
    const appVisualizar = document.querySelector('#input_visualizar_app').value;
    const mudarStatus = async status => {
        Loading.show();

        const body = new FormData();
        body.append('id', idVisualizar);
        body.append('status', status);
        body.append('app', appVisualizar);
        const resposta = await fetch(LINK + '/app/status', {
            body,
            method: 'POST',
        });

        Loading.hide();

        if (resposta.status == 204) {
            const resposta = await Alerta.mensagem('Status alterado', 'O status foi alterado com sucesso!', true);
            if (resposta) {
                window.location.reload();
            }
            return;
        }
        Alerta.notificacao('Ocorreu um erro ao alterar seu status.', false);
    };
});
