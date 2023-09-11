const loadingLogin = () => {
    const inputLogin = $('#input_login');
    const inputSenha = $('#input_senha');

    const botaoAtivar = $('#botao_ativar_conta');
    const botaoFazerLogin = $('#botao_fazer_login');
    const botaoDependente = $('#botao_abrir_dependente');
    const botaoRecuperarSenha = $('#botao_esqueceu_senha');

    if (botaoRecuperarSenha) {
        botaoRecuperarSenha.addEventListener('click', () => {
            PaginaSenha.abrir();
        });
    }

    const blocoLogin = $('#bloco_form_login');
    const blocoEscolha = $('#bloco_escolha_login');
    if (botaoDependente) {
        botaoDependente.addEventListener('click', () => {
            blocoLogin.classList.remove('display_none');
            blocoEscolha.classList.add('display_none');
            inputLogin.focus();
        });
    }

    inputLogin.focus();

    botaoAtivar.addEventListener('click', () => {
        PaginaAtivarBuscar.abrir();
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

    const fazerLogin = async () => {
        if (inputLogin.value == '') {
            Alerta.notificacao('Digite seu login para continuar.', false);
            return;
        } else if (inputSenha.value == '') {
            Alerta.notificacao('Digite sua senha para continuar.', false);
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/login/login',
            {
                login: inputLogin.value,
                senha: inputSenha.value,
            },
            'Erro ao fazer seu login, por favor, tente novamente'
        );
        if (!resposta) {
            Loading.hide();
            return;
        }
        window.location.replace(resposta.dado.link);
    };
};
