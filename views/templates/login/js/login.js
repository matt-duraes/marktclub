window.addEventListener('load', () => {
    const blocoLinkLocation = $('#LINK_LOCATION');
    const RECAPTCHA = $('#RECAPTCHA').value;
    const RECAPTCHAV2 = $('#RECAPTCHA_V2').value;

    let linkLocation = blocoLinkLocation ? blocoLinkLocation.value : LINK;
    if (linkLocation == '' || linkLocation == undefined || !linkLocation.startsWith(LINK)) {
        linkLocation = LINK;
    }

    const blocoRecaptcha = $('#bloco_recaptcha_v2');

    const formHash = $('#bloco_form_login input[name="form_system_hash"]').value;
    const inputLogin = $('#input_login');
    const inputSenha = $('#input_senha');
    const tipoUsuarioSelecionado = localStorage.getItem('tipoUsuarioSelecionado');
    const botaoAtivar = $('#botao_ativar_conta');
    const botaoFazerLogin = $('#botao_fazer_login');
    const botaoEscolhaLogin = $$('.botao_abrir_menu_normal');
    const botaoRecuperarSenha = $('#botao_esqueceu_senha');

    const botaoEscolhaVoltar = $('#bloco_form_login header .voltar');

    if (botaoRecuperarSenha) {
        botaoRecuperarSenha.addEventListener('click', () => {
            PopupLogin.fechar();
            PopupSenha.abrir();
        });
    }

    const blocoLogin = $('#bloco_form_login');
    const blocoEscolha = $('#bloco_escolha_login');
    if (botaoEscolhaLogin.length > 0) {
        botaoEscolhaLogin.evento('click', () => {
            blocoLogin.aparecer();
            blocoEscolha.sumir();
            botaoEscolhaVoltar.aparecer();
            inputLogin.focus();
        });
    }
    botaoEscolhaVoltar.evento('click', () => {
        blocoLogin.sumir();
        blocoEscolha.aparecer();
    });

    botaoAtivar.addEventListener('click', () => {
        PopupLogin.fechar();
        PaginaAtivar.abrir();
    });
    botaoFazerLogin.addEventListener('click', () => {
        fazerLogin();
    });
    inputLogin.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            fazerLogin();
        }
    });
    inputSenha.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            fazerLogin();
        }
    });

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

        if (inputLogin.value == '') {
            Alerta.notificacao('Digite seu login para continuar.', false);
            return;
        } else if (inputSenha.value == '') {
            Alerta.notificacao('Digite sua senha para continuar.', false);
            return;
        } else if (captchaToken == '' && captchaVersao == 2) {
            Alerta.notificacao('Clique no box do captcha para continuar.', false);
            return;
        }

        Loading.show();
        const body = new FormData();
        body.append('login', inputLogin.value);
        body.append('senha', inputSenha.value);
        body.append('form_system_hash', formHash);
        body.append('form_system_validacao', '');
        body.append('form_system_captcha', captchaToken);
        body.append('tipo_usuario', tipoUsuarioSelecionado);
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
            Loading.hide();
            resetarCaptcha();
            Alerta.notificacao('Ocorreu um erro ao tentar fazer se login, por favor, tente novamente.', false);
            return;
        } else if (deuErro && json.erro.captcha != undefined && false === json.erro.captcha && captchaVersao == 3) {
            Loading.hide();
            Alerta.notificacao(
                'Não foi possível validar seu captcha, por favor, marque o box de "Não sou um robô" para continuar.',
                false
            );
            mostrarCaptchaV2();
            return;
        } else if (deuErro) {
            Loading.hide();
            Alerta.notificacao(json.erro.mensagem || 'Erro ao fazer seu login, por favor, tente novamente.', false);
            resetarCaptcha();
            return;
        }

        window.location.replace(linkLocation);
    };
});
