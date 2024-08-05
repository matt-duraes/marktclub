window.addEventListener('load', () => {
    const blocoLinkLocation = $('#LINK_LOCATION');
    let linkLocation = blocoLinkLocation ? blocoLinkLocation.value : LINK;
    if (linkLocation == '' || linkLocation == undefined || !linkLocation.startsWith(LINK)) {
        linkLocation = LINK;
    }

    const inputLogin = $('#input_login');
    const inputSenha = $('#input_senha');

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
        if (false === resposta) {
            Loading.hide();
            return;
        }
        window.location.replace(linkLocation);
    };
});
