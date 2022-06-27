// @system "Form"
// @system "Loading"
// @system "Alerta"

window.addEventListener('load', () => {
    const link = document.querySelector('#form_login').getAttribute('action');
    const botao = document.querySelector('#botao_fazer_login');
    const login = document.querySelector('#input_login');
    const senha = document.querySelector('#input_senha');
    const hash = document.querySelector('input[name="form_system_hash"]').value;

    const blocoRecaptcha = document.getElementById('RECAPTCHA');
    const RECAPTCHA = blocoRecaptcha.value;
    blocoRecaptcha.parentNode.removeChild(blocoRecaptcha);

    const pegarCaptchaParaLogin = () => {
        if (login.value == '') {
            Alerta.notificacao('Digite seu login para continuar.', false);
            return;
        } else if (senha.value == '') {
            Alerta.notificacao('Digite sua senha para continuar.', false);
            return;
        }

        Loading.show();

        grecaptcha.ready(function () {
            grecaptcha
                .execute(RECAPTCHA, { action: 'create_singup' })
                .then(function (token) {
                    fazerLogin(token);
                })
                .catch(() => {
                    Loading.hide();
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
        const body = new FormData();
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');
        body.append('form_system_captcha', token);
        body.append('login', login.value);
        body.append('senha', senha.value);

        const resposta = await fetch(link, {
            method: 'POST',
            body,
        });
        if (resposta.status == 201) {
            window.location.reload();
            return;
        }

        let json;
        try {
            json = await resposta.json();
        } catch (erro) {
            json = {};
        }

        Loading.hide();

        Alerta.notificacao(
            json.erro.mensagem != undefined ? json.erro.mensagem : 'O login e/ou senha digitados não estão corretos.',
            false
        );
    };

    login.addEventListener('keyup', e => {
        if (e.key == 'Enter') {
            pegarCaptchaParaLogin();
        }
    });
    senha.addEventListener('keyup', e => {
        if (e.key == 'Enter') {
            pegarCaptchaParaLogin();
        }
    });
    botao.addEventListener('click', async () => {
        pegarCaptchaParaLogin();
    });
});
