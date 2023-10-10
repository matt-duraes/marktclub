window.addEventListener('load', () => {
    const blocoLinkLocation = $('#LINK_LOCATION');
    const linkLocation = blocoLinkLocation ? blocoLinkLocation.value : LINK;

    const pegarCaptchaParaLogin = () => {
        const textoBotao = botaoLogin.innerText;
        if (textoBotao == 'AGUARDE') {
            return;
        }
        Loading.form(blocoLogin, botaoLogin).show();

        grecaptcha.ready(function () {
            grecaptcha
                .execute(RECAPTCHA, { action: 'create_singup' })
                .then(function (token) {
                    fazerLogin(token);
                })
                .catch(() => {
                    Loading.form(blocoLogin, botaoLogin).hide();
                    Alerta.notificacao(
                        'Ocorreu um erro ao fazer o login, a página vai ser recarregada em 10 segundos.',
                        false
                    );
                    setInterval(() => {
                        window.location.reload();
                    }, 10000);
                });
        });
    };

    const fazerLogin = async token => {
        const login = inputLogin.value;
        const senha = inputSenha.value;

        let dado = new FormData();
        dado.append('form_system_hash', hash);
        dado.append('form_system_validacao', '');
        dado.append('form_system_captcha', token);
        dado.append('login', login);
        dado.append('senha', senha);

        const resposta = await fetch(LINK + '/login', {
            method: 'POST',
            body: dado,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        if (resposta.status == 201 && json.status == 'sucesso') {
            window.location.assign(linkLocation);
            Alerta.notificacao('Login realizado com sucesso, aguarde redirecionamento.', true);
            return;
        }

        Loading.form(blocoLogin, botaoLogin).hide();
        Alerta.notificacao(
            json.erro != undefined ? json.erro.mensagem : 'Erro ao fazer seu login, por favor, tente novamente.',
            false
        );
    };

    inputLogin.addEventListener('keyup', e => {
        if (e.key == 'Enter') {
            pegarCaptchaParaLogin();
        }
    });
    inputSenha.addEventListener('keyup', e => {
        if (e.key == 'Enter') {
            pegarCaptchaParaLogin();
        }
    });
    botaoLogin.addEventListener('click', e => {
        e.preventDefault();
        pegarCaptchaParaLogin();
    });
});
