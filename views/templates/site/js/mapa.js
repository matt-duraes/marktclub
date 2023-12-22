window.addEventListener('load', () => {
    const botaoLojaProxima = $$('.botao_loja_proxima');
    if (botaoLojaProxima.length == 0) {
        return;
    }

    botaoLojaProxima.forEach(botao => {
        botao.addEventListener('click', () => {
            if ('geolocation' in navigator) {
                pegarLocalizacao();
            } else {
                Alerta.notificacao('Seu navegador não tem permissão para pegar sua localização.', false);
            }
        });
    });

    let abortController;

    const pegarLocalizacao = () => {
        abortController = new AbortController();

        Loading.show();

        navigator.geolocation.getCurrentPosition(
            position => {
                Loading.hide();
                abortController.abort();
                window.location.assign(
                    LINK +
                        '/convenios?latitude=' +
                        encodeURI(position.coords.latitude) +
                        '&longitude=' +
                        encodeURI(position.coords.longitude)
                );
            },
            e => {
                Loading.hide();
                if (e.message == 'User denied Geolocation') {
                    Alerta.mensagem(
                        'Localização bloqueada',
                        'Você bloqueou a geolocalização, para poder mostrar as lojas próximas a você, precisamos que desbloquei sua localização e tente novamente.',
                        '!'
                    );
                    return;
                }
                Alerta.mensagem(
                    'Erro na localização',
                    'Ocorreu um erro ao pegar sua localização, verifique suas permissões no navegador e tente novamente.',
                    '!'
                );
            },
            { signal: abortController.signal }
        );
    };

    window.addEventListener('popstate', () => {
        if (abortController) {
            abortController.abort();
        }
        Loading.hide();
    });
});
