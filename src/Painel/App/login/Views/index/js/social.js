window.addEventListener('load', () => {
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
    | GOOGLE
    |--------------------------------------------------------------------------
    */
    const socialGoogleId = document.getElementById('GOOGLE_CLIENT_ID').value;
    const vincularGoogle = function () {
        gapi.load('auth2', function () {
            auth2 = gapi.auth2.init({
                // eslint-disable-next-line camelcase
                client_id: socialGoogleId,
            });
            attachSignin(document.getElementById('botao_login_google'));
        });
    };
    document.getElementById('botao_login_google').addEventListener('click', () => {
        Loading.show();
    });

    const attachSignin = element => {
        auth2.attachClickHandler(
            element,
            {},
            googleUser => {
                const id = googleUser.getBasicProfile().getId();
                const token = googleUser.getAuthResponse().id_token;
                fazerLoginSocial(id, token, 'google');
            },
            () => {
                Loading.hide();
                Alerta.notificacao('Não foi possível validar sua conta do Google, por favor, tente novamente.', false);
            }
        );
    };
    vincularGoogle();

    /*
    |--------------------------------------------------------------------------
    | FACEBOOK
    |--------------------------------------------------------------------------
    */
    document.getElementById('botao_login_facebook').addEventListener('click', () => {
        Loading.show();
        FB.getLoginStatus(function (response) {
            let id, token;
            if (response.status === 'connected') {
                id = response.authResponse.userID;
                token = response.authResponse.accessToken;
                fazerLoginSocial(id, token, 'facebook');
                return;
            }
            FB.login(
                response => {
                    if (response.status === 'connected') {
                        id = response.authResponse.userID;
                        token = response.authResponse.accessToken;
                        fazerLoginSocial(id, token, 'facebook');
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
    | LOGIN SOCIAL
    |--------------------------------------------------------------------------
    */
    const hashSocial = document.querySelector('#bloco_login_social input[name=form_system_hash]').value;
    const fazerLoginSocial = async (id, token, tipo) => {
        let body = new FormData();
        body.append('id', base64Encode(id));
        body.append('token', token);
        body.append('tipo', tipo);
        body.append('form_system_hash', hashSocial);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/login/social', {
            method: 'POST',
            body,
        });
        if (response.status == 201 && json.link != undefined) {
            window.location.assign(json.link);
            return;
        }
        Loading.hide();
        fetchNotificacaoErro(response, 'Ocorreu um erro ao fazer login, por favor, tente novamente.');
    };
});
