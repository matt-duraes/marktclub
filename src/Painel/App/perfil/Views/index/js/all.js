// @template "painel"

window.addEventListener('load', () => {
    const LINK = document.getElementById('LINK').value;

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

    /*
    |--------------------------------------------------------------------------
    | MUDAR FOTO
    |--------------------------------------------------------------------------
    */
    const botaoAbrirMudarImagem = document.getElementById('botao_abrir_mudar_imagem');
    const blocoMudarImagem = document.getElementById('bloco_perfil_imagem');
    botaoAbrirMudarImagem.addEventListener('click', () => {
        blocoMudarImagem.classList.add('display_flex');
        setTimeout(() => {
            blocoMudarImagem.classList.add('abrir');
        }, 20);
    });

    /*
    |--------------------------------------------------------------------------
    | VINCULAR CONTA DO FACEBOOK
    |--------------------------------------------------------------------------
    */
    document.getElementById('botao_vincular_facebook').addEventListener('click', () => {
        oauth2Facebook('vincular');
    });

    const oauth2Facebook = acao => {
        Loading.show();
        FB.getLoginStatus(function (response) {
            let id, token;
            if (response.status === 'connected') {
                id = response.authResponse.userID;
                token = response.authResponse.accessToken;
                vincularContaSocial(id, token, 'facebook', acao);
                return;
            }
            FB.login(
                response => {
                    if (response.status === 'connected') {
                        id = response.authResponse.userID;
                        token = response.authResponse.accessToken;
                        vincularContaSocial(id, token, 'facebook', acao);
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
    };

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
                cookiepolicy: 'single_host_origin',
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
    const vincularContaSocial = async (id, token, tipo, acao) => {
        let body = new FormData();
        body.append('id', id);
        body.append('token', token);
        body.append('tipo', tipo);
        body.append('acao', acao);
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
        if (response.status == 201 && acao == 'imagem') {
            menuConfig.style.backgroundImage = 'url(' + json.dado.imagem + ')';
            perfilImagemPrincipal.style.backgroundImage = 'url(' + json.dado.imagem + ')';
            return;
        } else if (response.status != 201) {
            Alerta.notificacao('Ocorreu um erro ao vincular sua conta, por favor, tente novamente.', false);
            return;
        } else if (
            !(await Alerta.confirmar(
                'Conta vinculada',
                'Sua conta foi vinculada com sucesso, gostaria de usar sua foto de perfil da rede social no painel?',
                true
            ))
        ) {
            return;
        }

        if (tipo == 'facebook') {
            oauth2Facebook('imagem');
        }
    };

    /*
    |--------------------------------------------------------------------------
    | ARQUIVO
    |--------------------------------------------------------------------------
    */
    const botaoArquivoUpload = document.querySelector('#botao_upload_imagem_arquivo');
    botaoArquivoUpload.addEventListener('dragover', e => {
        e.preventDefault();
        // fazerUploadDoArquico(e.dataTransfer.items[i].getAsFile());
    });

    const hash = document.querySelector('#bloco_perfil_imagem input[name=form_system_hash]').value;
    botaoArquivoUpload.addEventListener('drop', e => {
        e.preventDefault();
        if (e.dataTransfer.files.length == 0) {
            return;
        }

        fazerUploadDoArquivo(e.dataTransfer.files[0]);
    });
    const fazerUploadDoArquivo = async arquivo => {
        const body = new FormData();
        body.append('arquivo', arquivo);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/perfil/imagem', {
            method: 'POST',
            body,
        });
    };
});
