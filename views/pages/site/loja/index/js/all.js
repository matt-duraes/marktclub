// @template "site"
// @resource "site/loja/favorito"
// @resource "site/loja/busca"
// @resource "site/busca"

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
    const pegarLocalizacao = () => {
        Loading.show();
        navigator.geolocation.getCurrentPosition(
            position => {
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
                if (e.code == 1) {
                    Alerta.mensagem(
                        'Localização bloqueada',
                        'Você bloqueou a geolocalização, para poder mostrar as lojas próximas a você, precisamos que desbloquei sua localização e tente novamente.',
                        '!'
                    );
                }
            }
        );
    };
});
