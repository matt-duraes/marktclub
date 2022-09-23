// @template "painel"

window.addEventListener('load', () => {
    const LINK = document.getElementById('LINK').value;

    const botaoAbrirMudarImagem = document.getElementById('botao_abrir_mudar_imagem');
    const blocoMudarImagem = document.getElementById('bloco_perfil_imagem');
    botaoAbrirMudarImagem.addEventListener('click', () => {
        blocoMudarImagem.classList.add('display_flex');
        setTimeout(() => {
            blocoMudarImagem.classList.add('abrir');
        }, 20);
    });

    return;

    const facebookAppId = document.getElementById('FACEBOOK_APP_ID').value;
    window.fbAsyncInit = function () {
        FB.init({
            appId: facebookAppId,
            cookie: true,
            xfbml: true,
            version: 'v11.0',
        });
        FB.AppEvents.logPageView();
    };
    (function (d, s, id) {
        var js,
            fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = 'https://connect.facebook.net/en_US/sdk.js';
        fjs.parentNode.insertBefore(js, fjs);
    })(document, 'script', 'facebook-jssdk');

    /*
    |--------------------------------------------------------------------------
    | FACEBOOK
    |--------------------------------------------------------------------------
    */
    document.getElementById('botao_vincular_facebook').addEventListener('click', () => {
        Loading.show();
        FB.getLoginStatus(function (response) {
            let id, token;
            if (response.status === 'connected') {
                id = response.authResponse.userID;
                token = response.authResponse.accessToken;
                vincularContaSocial(id, token, 'facebook');
                return;
            }
            FB.login(
                response => {
                    if (response.status === 'connected') {
                        id = response.authResponse.userID;
                        token = response.authResponse.accessToken;
                        vincularContaSocial(id, token, 'facebook');
                    } else {
                        Loading.hide();
                        Alerta.notificacao(
                            'Não foi possível validar sua conta do Facebook, por favor, tente novamente.',
                            false
                        );
                    }
                },
                { scope: 'public_profile,email' }
            );
        });
    });

    /*
    |--------------------------------------------------------------------------
    | GOOGLE
    |--------------------------------------------------------------------------
    */
    const socialGoogleId = document.getElementById('GOOGLE_CLIENT_ID').value;
    const vincularGoogle = function () {
        gapi.load('auth2', function () {
            auth2 = gapi.auth2.init({
                clientId: socialGoogleId,
            });
            attachSignin(document.getElementById('botao_vincular_google'));
        });
    };
    document.getElementById('botao_vincular_google').addEventListener('click', () => {
        Loading.show();
    });

    const attachSignin = element => {
        auth2.attachClickHandler(
            element,
            {},
            googleUser => {
                const id = googleUser.getBasicProfile().getId();
                const token = googleUser.getAuthResponse().id_token;
                vincularContaSocial(id, token, 'google');
            },
            () => {
                Loading.hide();
                Alerta.notificacao('Não foi possível vincular sua conta do Google, por favor, tente novamente.', false);
            }
        );
    };
    vincularGoogle();

    /*
    |--------------------------------------------------------------------------
    | VINCULAR REDE SOCIAL
    |--------------------------------------------------------------------------
    */
    const menuConfig = document.getElementById('botao_menu_config');
    const perfilImagemPrincipal = document.getElementById('perfil_imagem_principal');
    const inputHash = document.querySelector('#bloco_vinculo_social input[name=form_system_hash]').value;
    const vincularContaSocial = async (id, token, tipo) => {
        let body = new FormData();
        body.append('id', id);
        body.append('token', token);
        body.append('tipo', tipo);
        body.append('form_system_hash', inputHash);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/perfil/social', {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await response.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();
        if (response.status == 201 && json.imagem) {
            menuConfig.style.backgroundImage = 'url(' + json.imagem + ')';
            perfilImagemPrincipal.style.backgroundImage = 'url(' + json.imagem + ')';
            return;
        }
        fetchNotificacaoErro(response, 'Ocorreu um erro ao vincular sua conta, por favor, tente novamente.');
    };
});
