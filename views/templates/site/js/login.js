window.addEventListener('load', () => {
    const blocoLogin = $('#bloco_login_relogar');
    if (!blocoLogin) {
        return;
    }
    const inputLogin = $('#input_relogar_login');
    const inputSenha = $('#input_relogar_senha');

    const botaoLogin = $('#botao_refazer_login');

    inputLogin.addEventListener('click', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            fazerLogin();
        }
    });
    inputSenha.addEventListener('click', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            fazerLogin();
        }
    });
    botaoLogin.addEventListener('click', e => {
        e.preventDefault();
        fazerLogin();
    });

    const fazerLogin = async () => {
        if (inputLogin.value == '') {
            Alerta.notificacao('Digite seu login para relogar.', false);
            return;
        } else if (inputSenha.value == '') {
            Alerta.notificacao('Digite sua senha para relogar.', false);
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
        Loading.hide();
        if (false === resposta) {
            return;
        }
        blocoLogin.classList.remove('ativo');
        setTimeout(() => {
            blocoLogin.classList.add('display_none');
        }, 300);
    };
});
