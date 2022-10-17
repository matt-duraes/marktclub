window.addEventListener('load', () => {
    const googleAppId = document.getElementById('GOOGLE_CLIENT_ID').value;
    const facebookAppId = document.getElementById('FACEBOOK_APP_ID').value;

    window.fbAsyncInit = function () {
        FB.init({
            appId: facebookAppId,
            cookie: true,
            xfbml: true,
            version: 'v15.0',
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

    document.getElementById('botao_login_facebook').addEventListener('click', () => {
        FB.getLoginStatus(function (response) {
            let id, token;
            if (response.status === 'connected') {
                id = response.authResponse.userID;
                token = response.authResponse.accessToken;
                loginComRedeSocial(id, token, '', 'facebook');
                return;
            }
            FB.login(
                response => {
                    if (response.status === 'connected') {
                        id = response.authResponse.userID;
                        token = response.authResponse.accessToken;
                        loginComRedeSocial(id, token, '', 'facebook');
                    } else {
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

    document.getElementById('botao_login_google').addEventListener('click', async () => {
        const client = google.accounts.oauth2.initCodeClient({
            // eslint-disable-next-line camelcase
            client_id: googleAppId,
            scope: 'profile',
            // eslint-disable-next-line camelcase
            ux_mode: 'popup',
            callback: response => {
                loginComRedeSocial('', '', response.code, 'google');
            },
        });
        client.requestCode();
    });

    const hashSocial = document.querySelector('#bloco_login_social input[name=form_system_hash]').value;
    const loginComRedeSocial = async (id, token, code, rede) => {
        Loading.show();

        const body = new FormData;
        body.append('id', id);
        body.append('token', token);
        body.append('code', code);
        body.append('rede', rede);
        body.append('form_system_hash', hashSocial);
        body.append('form_system_validacao', '');

        const resposta = await fetch(LINK + '/login/social', {
            method: 'POST',
            body
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();

        if (resposta.status == 201 && json.status == 'sucesso') {
            window.location.assign(json.dado.link);
            Alerta.notificacao('Login realizado com sucesso, aguarde redirecionamento.', true);
            return;
        }

        Alerta.notificacao(
            json.erro != undefined ?
            json.erro.mensagem : 'Erro ao fazer seu login, por favor, tente novamente.',
            false
        );
    };
});
