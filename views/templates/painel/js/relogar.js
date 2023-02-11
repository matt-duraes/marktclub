window.addEventListener('load', () => {
    const blocoRecaptcha = document.getElementById('RECAPTCHA');
    const RECAPTCHA = blocoRecaptcha.value;

    const blocoConfig = document.getElementById('bloco_config_template');
    const botaoBloquear = document.getElementById('botao_config_bloquear_sessao');
    const textoRelogar = document.getElementById('bloco_usuario_relogar_bloqueado');

    const blocoRelogar = document.getElementById('bloco_usuario_relogar');
    const botaoRelogar = blocoRelogar.querySelector('#botao_usuario_relogar');
    const blocoTextoInativo = blocoRelogar.querySelector('#bloco_usuario_relogar_inativo');
    const blocoTextoBloqueado = blocoRelogar.querySelector('#bloco_usuario_relogar_bloqueado');

    const inputSenha = blocoRelogar.querySelector('#input_relogar_senha');
    const cpf = blocoRelogar.querySelector('#input_relogar_cpf').value;
    const hash = blocoRelogar.querySelector('input[name=form_system_hash]').value;

    botaoBloquear.addEventListener('click', () => {
        fetch(LINK + '/bloquear');
        blocoConfig.classList.remove('aberto');
        setTimeout(() => {
            blocoConfig.classList.add('fechado');
        }, 320);

        blocoRelogar.classList.remove('display_none');
        textoRelogar.classList.remove('display_none');
        setTimeout(() => {
            blocoRelogar.classList.add('ativo');
        }, 50);
    });

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
        if (inputSenha.value == '') {
            Alerta.notificacao('Digite sua senha para continuar.', false);
            return;
        }
        Loading.show();

        grecaptcha.ready(function () {
            grecaptcha
                .execute(RECAPTCHA, { action: 'create_singup' })
                .then(function (token) {
                    relogarUsuario(token);
                })
                .catch(() => {
                    Loading.hide();
                    Alerta.notificacao(
                        'Ocorreu um erro ao fazer o login, a página vai ser recarregada em 10 segundos.',
                        false
                    );
                });
        });
    };

    const relogarUsuario = async token => {
        const body = new FormData();
        body.append('login', cpf);
        body.append('senha', inputSenha.value);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');
        body.append('form_system_captcha', token);

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
