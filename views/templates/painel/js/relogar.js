window.addEventListener('load', () => {
    const blocoRecaptcha = document.getElementById('RECAPTCHA');
    const RECAPTCHA = blocoRecaptcha.value;

    const blocoRelogar = document.getElementById('bloco_usuario_relogar');
    const botaoRelogar = blocoRelogar.querySelector('#botao_usuario_relogar');
    const blocoTextoInativo = blocoRelogar.querySelector('#bloco_usuario_relogar_inativo');
    const blocoTextoBloqueado = blocoRelogar.querySelector('#bloco_usuario_relogar_bloqueado');

    const inputSenha = blocoRelogar.querySelector('#input_relogar_senha');
    const cpf = blocoRelogar.querySelector('#input_relogar_cpf').value;
    const hash = blocoRelogar.querySelector('input[name=form_system_hash]').value;

    inputSenha.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            pegarCaptchaParaLogin();
        }
    });
    botaoRelogar.addEventListener('click', e => {
        e.preventDefault();
        pegarCaptchaParaLogin();
    });
    const pegarCaptchaParaLogin = () => {
        Loading.show();

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

    const relogarUsuario = async () => {
        if (inputSenha.value == '') {
            Alerta.notificacao('Digite sua senha para continuar.', false);
            return;
        }

        Loading.show();

        const body = new FormData();
        body.append('login', cpf);
        body.append('senha', inputSenha.value);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');
        body.append('form_system_captch');

        const resposta = await fetch(LINK + '/login', {
            body,
            method: 'POST',
        });

        Loading.hide();
        if (resposta.status != 201) {
            Alerta.notificacao('Erro ao relogar no sistema, verifique a senha digitada e tente novamente.', false);
            return;
        }

        blocoRelogar.classList.remove('ativo');
        setTimeout(() => {
            blocoRelogar.classList.add('display_none');
            blocoTextoInativo.classList.add('display_none');
            blocoTextoBloqueado.classList.add('display_none');
        }, 300);
    };
});
