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

    let abortController; // Variável para armazenar o controller para abortar a solicitação

    const pegarLocalizacao = () => {
        abortController = new AbortController(); // Criar um novo AbortController

        Loading.show();

        navigator.geolocation.getCurrentPosition(
            position => {
                Loading.hide();
                abortController.abort(); // Cancelar a solicitação quando a localização é obtida
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
            { signal: abortController.signal } // Passar o signal do AbortController para a solicitação
        );
    };

    // Adicionar um listener para o evento popstate (quando o usuário clica no botão de voltar do navegador)
    window.addEventListener('popstate', () => {
        if (abortController) {
            abortController.abort(); // Cancelar a solicitação se o usuário clicar no botão de voltar
        }
        Loading.hide();
    });
});
