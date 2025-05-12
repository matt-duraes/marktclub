window.addEventListener('load', () => {
    const LINK = $('#LINK').value;
    const cpf = $('#input_login');
    const senha = $('#input_senha');
    const erro = $('#bloco_erro');
    const botao = $('#botao_fazer_login');
    const hash = $('input[name="form_system_hash"]');
    const validacao = $('input[name="form_system_validacao"]');

    const RECAPTCHA = $('#RECAPTCHA').value;
    const RECAPTCHAV2 = $('#RECAPTCHA_V2').value;
    const blocoRecaptcha = $('#bloco_recaptcha_v2');

    $('body').evento('dblclick', () => {
        $('#site').classe('display_none', false);
        cpf.focus();
    });

    $$('#input_login, #input_senha').evento('keydown', e => {
        if (e.key == 'Enter') {
            fazerLogin();
        }
    });

    botao.evento('click', () => {
        fazerLogin();
    });

    const resetarCaptcha = () => {
        if (captchaVersao != 2) {
            return;
        }
        grecaptcha.reset(captcha2AtivarBuscar);
    };

    let captchaVersao = 3;
    const pegarCaptcha = async () => {
        if (captchaVersao == 2) {
            const captcha = grecaptcha.getResponse(0);
            if (captcha == '') {
                Alerta.notificacao('Marque o box de "Não sou um Robô" para continuar.', false);
                return false;
            }
            return 'v2.' + captcha;
        }

        return grecaptcha
            .execute(RECAPTCHA, { action: 'create_singup' })
            .then(function (token) {
                return 'v3.' + token;
            })
            .catch(async () => {
                await Alerta.mensagem('Erro ao carregar recaptcha', 'Recarregue a página e tente novamente.', false);
                return false;
            });
    };
    let captcha2Login;
    const mostrarCaptchaV2 = () => {
        if (captchaVersao == 2) {
            return;
        }
        captchaVersao = 2;
        blocoRecaptcha.classList.remove('display_none');
        captcha2Login = grecaptcha.render('bloco_recaptcha_v2', {
            sitekey: RECAPTCHAV2,
            theme: 'light',
        });
    };

    const fazerLogin = async () => {
        const captchaToken = await pegarCaptcha();

        if (botao.classe('aguarde', '?')) {
            return;
        }

        erro.texto('');
        if (cpf.value == '') {
            erro.texto('Digite seu CPF para continuar.');
            return;
        } else if (senha.value == '') {
            erro.texto('Digite sua senha para continuar.');
            return;
        } else if (captchaToken == '' && captchaVersao == 2) {
            Alerta.notificacao('Clique no box do captcha para continuar.', false);
            return;
        }
        botao.classe('aguarde', true);
        botao.texto('Aguarde');

        const body = new FormData();
        body.append('login', cpf.value);
        body.append('senha', senha.value);
        body.append('form_system_hash', hash.value);
        body.append('form_system_validacao', '');
        body.append('form_system_captcha', captchaToken);
        const resposta = await fetch(LINK + '/login/login', {
            method: 'POST',
            body,
        });
        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        const respostaJson = json instanceof Object;
        const deuErro = respostaJson && json.status == 'erro';
        if (!respostaJson || json.status === undefined) {
            botao.classe('aguarde', false);
            botao.texto('Login');
            resetarCaptcha();
            Alerta.notificacao('Ocorreu um erro ao tentar fazer se login, por favor, tente novamente.', false);
            return;
        } else if (deuErro && json.erro.captcha != undefined && false === json.erro.captcha && captchaVersao == 3) {
            botao.classe('aguarde', false);
            botao.texto('Login');
            Alerta.notificacao(
                'Não foi possível validar seu captcha, por favor, marque o box de "Não sou um robô" para continuar.',
                false
            );
            mostrarCaptchaV2();
            return;
        } else if (deuErro) {
            botao.classe('aguarde', false);
            botao.texto('Login');
            Alerta.notificacao(json.erro.mensagem || 'Erro ao fazer seu login, por favor, tente novamente.', false);
            resetarCaptcha();
            return;
        }

        window.location.replace(LINK);
    };
});
