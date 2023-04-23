// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @system "Form"
// @system "Alerta"
// @system "Loading"

window.addEventListener('load', () => {

    /*
    |--------------------------------------------------------------------------
    | SENHA
    |--------------------------------------------------------------------------
    */

    const botaoSalvarSenha = document.querySelector('#botao_salvar_senha');
    const inputSenhaAtual = document.querySelector('#input_senha_atual');
    const inputSenhaNova = document.querySelector('#input_senha_nova');
    const inputSenhaRepetir = document.querySelector('#input_senha_repetir');
    const senhaLink = document.querySelector('#form_mudar_senha').getAttribute('action');

    const limparSenha = () => {
        inputSenhaAtual.value = '';
        inputSenhaNova.value = '';
        inputSenhaRepetir.value = '';
    };

    inputSenhaAtual.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            salvarNovaSenha();
        }
    });
    inputSenhaNova.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            salvarNovaSenha();
        }
    });
    inputSenhaRepetir.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            salvarNovaSenha();
        }
    });
    botaoSalvarSenha.addEventListener('click', (e) => {
        e.preventDefault();
        salvarNovaSenha();
    });

    const salvarNovaSenha = async () => {
        const senhaAtual = inputSenhaAtual.value;
        const senhaNova = inputSenhaNova.value;
        const senhaRepetir = inputSenhaRepetir.value;

        if (senhaAtual == '') {
            Alerta.notificacao('Digite sua senha atual para continuar.', false);
            return;
        } else if (senhaNova == '') {
            Alerta.notificacao('Digite sua nova senha para continuar.', false);
            return;
        } else if (senhaNova != senhaRepetir) {
            Alerta.notificacao('Sua nova senha e o campo repetir senha não estão iguais.', false);
            return;
        }

        Loading.show();
        const body = new FormData();
        body.append('senha_atual', senhaAtual);
        body.append('senha_nova', senhaNova);
        body.append('senha_repetir', senhaRepetir);

        const resposta = await fetch(senhaLink, {
            method: 'POST',
            body,
        });

        Loading.hide();

        if (resposta.status == 204) {
            Alerta.notificacao('Senha alterada com sucesso!', true);
            return;
        }

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Alerta.notificacao(
            json.erro.mensagem != undefined
                ? json.erro.mensagem
                : 'Ocorreu um erro ao tentar mudar sua senha, por favor, tente novamente.',
            false
        );
    };
});
