// @template "painel"

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

    /*
    |--------------------------------------------------------------------------
    | MUDAR FOTO
    |--------------------------------------------------------------------------
    */
    const botaoAbrirMudarImagem = document.getElementById('botao_abrir_mudar_imagem');
    const blocoMudarImagem = document.getElementById('bloco_perfil_imagem');

    const abrirBlocoMudarPerfil = () => {
        blocoMudarImagem.classList.add('display_flex');
        setTimeout(() => {
            blocoMudarImagem.classList.add('abrir');
        }, 20);
    };

    if (location.hash && location.hash === '#mudar-imagem') {
        history.replaceState({}, '', location.href.replace('#mudar-imagem', ''));
        abrirBlocoMudarPerfil();
    }

    botaoAbrirMudarImagem.addEventListener('click', () => {
        abrirBlocoMudarPerfil();
    });
    const fecharPopupMudarImagem = () => {
        blocoMudarImagem.classList.remove('abrir');
        setTimeout(() => {
            blocoMudarImagem.classList.remove('display_flex');
        }, 300);
    };

    const botaoPerfilImagemFecharDesktop = document.getElementById('botao_perfil_imagem_fechar_desktop');
    const botaoPerfilImagemFecharMobile = document.getElementById('botao_perfil_imagem_fechar_mobile');
    botaoPerfilImagemFecharDesktop.addEventListener('click', () => {
        fecharPopupMudarImagem();
    });
    botaoPerfilImagemFecharMobile.addEventListener('click', () => {
        fecharPopupMudarImagem();
    });
    blocoMudarImagem.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_perfil_imagem') {
            fecharPopupMudarImagem();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | VINCULAR CONTA DO FACEBOOK
    |--------------------------------------------------------------------------
    */
    document.getElementById('botao_vincular_imagem_facebook').addEventListener('click', () => {
        oauth2Facebook('imagem');
    });
    document.getElementById('botao_vincular_facebook').addEventListener('click', () => {
        oauth2Facebook('vincular');
    });

    const oauth2Facebook = acao => {
        FB.getLoginStatus(function (response) {
            let id, token;
            if (response.status === 'connected') {
                id = response.authResponse.userID;
                token = response.authResponse.accessToken;
                vincularContaSocial(id, token, '', 'facebook', acao);
                return;
            }
            FB.login(
                response => {
                    if (response.status === 'connected') {
                        id = response.authResponse.userID;
                        token = response.authResponse.accessToken;
                        vincularContaSocial(id, token, '', 'facebook', acao);
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
    };

    /*
    |--------------------------------------------------------------------------
    | GOOGLE
    |--------------------------------------------------------------------------
    */
    document.getElementById('botao_vincular_google').addEventListener('click', async () => {
        oauth2Google('vincular');
    });
    document.getElementById('botao_vincular_imagem_google').addEventListener('click', async () => {
        oauth2Google('imagem');
    });

    const oauth2Google = acao => {
        const client = google.accounts.oauth2.initCodeClient({
            // eslint-disable-next-line camelcase
            client_id: googleAppId,
            scope: 'email profile',
            // eslint-disable-next-line camelcase
            ux_mode: 'popup',
            callback: response => {
                vincularContaSocial('', '', response.code, 'google', acao);
            },
        });
        client.requestCode();
    };
    /*
    |--------------------------------------------------------------------------
    | VINCULAR REDE SOCIAL
    |--------------------------------------------------------------------------
    */
    const menuConfig = document.getElementById('botao_menu_config');
    const perfilImagemPrincipal = document.getElementById('perfil_imagem_principal');
    const perfilImagemMenuConfig = document.getElementById('menu_config_imagem_perfil');
    const inputHash = document.querySelector('#bloco_vinculo_social input[name=form_system_hash]').value;

    const vincularContaSocial = async (id, token, code, rede, acao) => {
        Loading.show();

        let body = new FormData();
        body.append('id', id);
        body.append('token', token);
        body.append('code', code);
        body.append('rede', rede);
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
            setarNovaImagem(json.dado.imagem);
            fecharPopupMudarImagem();
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

        if (rede == 'facebook') {
            oauth2Facebook('imagem');
        } else if (rede == 'google') {
            oauth2Google('imagem');
        }
    };

    /*
    |--------------------------------------------------------------------------
    | ARQUIVO
    |--------------------------------------------------------------------------
    */
    const botaoArquivoUpload = document.querySelector('#botao_upload_imagem_arquivo');
    const blocoArquivoPerfil = document.querySelector('#bloco_upload_imagem_perfil');
    botaoArquivoUpload.addEventListener('dragover', e => {
        e.preventDefault();
    });
    blocoMudarImagem.addEventListener('dragover', e => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'copy';
        blocoArquivoPerfil.classList.add('drag');
    });
    blocoMudarImagem.addEventListener('dragleave', e => {
        e.preventDefault();
        blocoArquivoPerfil.classList.remove('drag');
    });
    blocoMudarImagem.addEventListener('drop', e => {
        e.preventDefault();
        blocoArquivoPerfil.classList.remove('drag');
        return;
    });

    const hash = document.querySelector('#bloco_perfil_imagem input[name=form_system_hash]').value;
    botaoArquivoUpload.addEventListener('drop', e => {
        e.preventDefault();
        blocoArquivoPerfil.classList.remove('drag');
        if (e.dataTransfer.files.length == 0) {
            return;
        }

        fazerUploadDoArquivo(e.dataTransfer.files[0]);
    });
    botaoArquivoUpload.addEventListener('change', () => {
        const arquivo = botaoArquivoUpload.files[0];
        if (!arquivo) {
            return;
        }
        botaoArquivoUpload.value = '';
        fazerUploadDoArquivo(arquivo);
    });

    const fazerUploadDoArquivo = async arquivo => {
        Loading.show();

        const body = new FormData();
        body.append('arquivo', arquivo);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/perfil/imagem', {
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

        if (response.status == 201) {
            fecharPopupMudarImagem();
            setarNovaImagem(json.dado.imagem);
            return;
        }

        Alerta.notificacao(json.erro.mensagem, false);
    };

    const setarNovaImagem = imagem => {
        if (menuConfig) {
            menuConfig.style.backgroundImage = 'url(' + imagem + ')';
        }
        if (perfilImagemPrincipal) {
            perfilImagemPrincipal.style.backgroundImage = 'url(' + imagem + ')';
        }
        if (perfilImagemMenuConfig) {
            perfilImagemMenuConfig.style.backgroundImage = 'url(' + imagem + ')';
        }
    };
});
