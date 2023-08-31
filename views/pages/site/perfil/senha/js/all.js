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

    const limparSenha = () => {
        formValue(inputSenhaAtual, '');
        formValue(inputSenhaNova, '');
        formValue(inputSenhaRepetir, '');
    };

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
        const resposta = await ajaxPost(
            LINK + '/perfil/alterar-senha',
            {
                /* eslint-disable */
                senha_atual: senhaAtual,
                senha_nova: senhaNova,
                senha_repetir: senhaRepetir,
                /* eslint-enable */
            },
            'Erro ao alterar sua senha, por favor, tente novamente.'
        );

        Loading.hide();
        if (false === resposta) {
            return;
        }
        limparSenha();
        Alerta.notificacao('Senha alterada com sucesso!', true);
    };

    adicionarEventoEnter([inputSenhaAtual, inputSenhaNova, inputSenhaRepetir], salvarNovaSenha);
    adicionarEvento('click', botaoSalvarSenha, salvarNovaSenha);
});
