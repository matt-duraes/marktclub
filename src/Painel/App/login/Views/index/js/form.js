window.addEventListener('load', () => {
    const blocoLinkLocation = $('#LINK_LOCATION');
    let linkLocation = blocoLinkLocation ? blocoLinkLocation.value : LINK;
    if (linkLocation == '' || linkLocation == undefined || !linkLocation.startsWith(LINK)) {
        linkLocation = LINK;
    }

    let captchaVersao = 'v3.';
    const pegarCaptchaParaLogin = () => {
        const textoBotao = botaoLogin.innerText;
        if (textoBotao == 'AGUARDE') {
            return;
        }
        Loading.form(blocoLogin, botaoLogin).show();

        if (captchaVersao == 'v2.') {
            const captcha = grecaptcha.getResponse(0);
            if (captcha == '') {
                Loading.form(blocoLogin, botaoLogin).hide();
                Alerta.notificacao('Marque o box de "Não sou um Robô" para continuar.', false);
                return;
            }
            fazerLogin(captcha);
            return;
        }

        grecaptcha.ready(function () {
            grecaptcha
                .execute(RECAPTCHA, { action: 'create_singup' })
                .then(function (token) {
                    fazerLogin(token);
                })
                .catch(async () => {
                    Loading.form(blocoLogin, botaoLogin).hide();
                    await Alerta.mensagem(
                        'Erro ao carregar recaptcha',
                        'Ocorreu um erro ao fazer o login, a página vai ser recarregada, caso continue, entre em contato com o suporte.',
                        false
                    );
                    window.location.reload();
                });
        });
    };

    const fazerLogin = async token => {
        const login = inputLogin.value;
        const senha = inputSenha.value;

        let dado = new FormData();
        dado.append('form_system_hash', hash);
        dado.append('form_system_validacao', '');
        dado.append('form_system_captcha', captchaVersao + token);
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
        if (captchaVersao == 'v2.') {
            grecaptcha.reset();
        }

        Loading.form(blocoLogin, botaoLogin).hide();
        if (json.erro !== undefined && json.erro.captcha === false && captchaVersao == 'v3.') {
            Alerta.notificacao('Erro ao validar recaptcha, faça o desafio manual para continuar.', false);
            mostrarCaptchaV2();
            return;
        }
        Alerta.notificacao(
            json.erro != undefined ? json.erro.mensagem : 'Erro ao fazer seu login, por favor, tente novamente.',
            false
        );
    };

    const mostrarCaptchaV2 = () => {
        captchaVersao = 'v2.';

        grecaptcha.render('bloco_recaptcha_v2', {
            sitekey: RECAPTCHAV2,
            theme: 'light',
        });
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
