// @template "login"
// @system "Loading"
// @system "Alerta"
// @system "Mascara"
// @system "Form"
// @resource "site/login/animarLogin"

window.addEventListener('load', () => {
    const inputLogin = document.getElementById('input_login');
    const inputSenha = document.getElementById('input_senha');
    const botaoLogin = document.getElementById('botao_fazer_login');

    if (inputLogin) {
        inputLogin.addEventListener('keydown', e => {
            if (e.key == 'Enter') {
                fazerLogin();
            }
        });
        inputSenha.addEventListener('keydown', e => {
            if (e.key == 'Enter') {
                fazerLogin();
            }
        });
        botaoLogin.addEventListener('click', e => {
            e.preventDefault();
            fazerLogin();
        });
    }
    const fazerLogin = async e => {
        const login = inputLogin.value;
        const senha = inputSenha.value;
        if (!login && !senha) {
            Alerta.notificacao('Digite o login ou a senha para prosseguir');
            return false;
        }

        const resposta = await ajaxPost(LINK + '/login', { login, senha }, 'Erro ao fazer o login, tente novamente.');
        if (false === resposta) {
            return;
        }
        window.location.assign(resposta.dado.link);
    };

    //IR PARA RECUPERAR SENHA

    document.getElementById('botao_ir_codigo').addEventListener('click', async e => {
        e.preventDefault();

        /*  if (usarRecaptcha) {
            grecaptcha.reset(captchaLogin);
        } */

        const inputLoginRecuperarSenha = document.getElementById('input_login_recuperar_senha');
        const inputAuthLogin = document.querySelector('#auth_bloco_login .login input');
        inputLoginRecuperarSenha.value = inputAuthLogin.value;

        const inputSenha = document.querySelector('#auth_bloco_login .senha input');
        inputSenha.value = '';

        animarBloco(
            document.getElementById('auth_bloco_login'),
            document.getElementById('auth_bloco_codigo'),
            inputLoginRecuperarSenha.focus(),
            'esquerda'
        );
    });

    document.getElementById('botao_enviar_codigo').addEventListener('click', async e => {
        e.preventDefault();

        const form = document.getElementById('auth_form_codigo');
        const botao = this;

        let captcha = '';
        /*  if (usarRecaptcha) {
            captcha = grecaptcha.getResponse(captchaRecuperarSenha);
            if (captcha === '') {
                Alerta.mensagem('Campo obrigatório!', 'Marque o box de "Não sou um robô" para continuar.');
                return false;
            }
        } */

        loading.show(form);

        const inputLoginRecuperarSenha = document.getElementById('input_login_recuperar_senha');
        const login = inputLoginRecuperarSenha.value;

        const body = new FormData();
        body.append('login', login);
        body.append('client_id', '');
        body.append('captcha', captcha);

        const resposta = await fetch('/auth/enviar-codigo', {
            method: 'POST',
            body,
        });

        if (resposta.status !== 201) {
            Alerta.notificacao(
                json.erro.mensagem !== undefined
                    ? json.erro.mensagem
                    : 'Ocorreu um erro ao enviar código, por favor, tente novamente.',
                false
            );
            return;
        }
        const authBlocoCodigo = document.querySelector('#auth_bloco_codigo');
        const authBlocoValidarCodigo = document.querySelector('#auth_bloco_validar_codigo');
        const authBlocoValidarCodigoInput = document.querySelector(
            '#auth_bloco_validar_codigo .codigo input:first-child'
        );

        animarBloco(authBlocoCodigo, authBlocoValidarCodigo, authBlocoValidarCodigoInput, 'esquerda');
    });

    //VALIDAR CÓDIGO

    async function validarCodigo() {
        const form = document.getElementById('auth_form_validar_codigo');
        const botao = document.getElementById('botao_validar_codigo');
        let codigo = '';

        const codigoInputs = form.querySelectorAll('.codigo input');
        codigoInputs.forEach(input => {
            codigo += input.value;
        });

        loading.show(form, botao);

        const loginInput = document.getElementById('input_login_recuperar_senha');
        const clientID = 'YOUR_CLIENT_ID';

        const body = new FormData();
        body.append('codigo', codigo);
        body.append('client_id', '');
        body.append('login', loginInput.value);

        const resposta = await fetch('/auth/validar-codigo', {
            method: 'POST',
            body,
        });
        if (resposta.status !== 201) {
            Alerta.notificacao(
                json.erro.mensagem !== undefined
                    ? json.erro.mensagem
                    : 'Ocorreu um erro ao validar código, por favor, tente novamente.',
                false
            );
            return;
        }

        const authBlocoValidarCodigo = document.getElementById('auth_bloco_validar_codigo');
        const authBlocoNovaSenha = document.getElementById('auth_bloco_nova_senha');
        const novaSenhaInput = document.querySelector('#auth_bloco_nova_senha .nova_senha input');

        animarBloco(authBlocoValidarCodigo, authBlocoNovaSenha, novaSenhaInput, 'esquerda');
    }

    document.getElementById('botao_validar_codigo').addEventListener('click', async e => {
        e.preventDefault();
        validarCodigo();
    });
});
