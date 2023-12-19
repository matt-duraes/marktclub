window.addEventListener('load', () => {
    const LINK = $('#LINK').value;
    const cpf = $('#input_login');
    const senha = $('#input_senha');
    const erro = $('#bloco_erro');
    const botao = $('#botao_fazer_login');

    $('body').evento('dblclick', () => {
        $('#site').classe('display_none', false);
        cpf.focus();
    });

    botao.evento('click', async () => {
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
        }
        botao.classe('aguarde', true);
        botao.texto('Aguarde');

        const body = new FormData();
        body.append('login', cpf.value);
        body.append('senha', senha.value);
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

        if (json.status == 'erro') {
            botao.classe('aguarde', false);
            botao.texto('Login');
            erro.texto(json.erro.mensagem);
            return;
        }
        window.location.replace(LINK);
    });
});
