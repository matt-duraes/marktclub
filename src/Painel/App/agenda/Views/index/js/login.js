window.addEventListener('load', () => {
    const LINK = document.querySelector('#LINK').value;

    const botaoSincronizarAgenda = document.querySelector('#botao_sincronizar_agenda');
    if (!botaoSincronizarAgenda) {
        return;
    }

    const googleAppId = document.getElementById('GOOGLE_CLIENT_ID').value;
    botaoSincronizarAgenda.addEventListener('click', () => {
        const client = google.accounts.oauth2.initCodeClient({
            // eslint-disable-next-line camelcase
            client_id: googleAppId,
            scope: 'https://www.googleapis.com/auth/calendar.events',
            // eslint-disable-next-line camelcase
            ux_mode: 'popup',
            callback: response => {
                gerarToken(response.code);
            },
        });
        client.requestCode();
    });

    const hash = document.querySelector('#bloco_conectar input[name=form_system_hash]').value;
    const gerarToken = async code => {
        Loading.show();

        const body = new FormData();
        body.append('code', code);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');
        const response = await fetch(LINK + '/agenda/login', {
            method: 'POST',
            body,
        });

        if (response.status == 201) {
            window.location.reload();
            return;
        }
        Loading.hide();
        Alerta.notificacao('Erro ao vincular sua agenda, por favor, tente novamente.', false);
    };
});
