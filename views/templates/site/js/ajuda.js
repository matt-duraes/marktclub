// @system "Form"

window.addEventListener('load', () => {
    const botaoAbrirAjuda = document.getElementById('botao_abrir_ajuda');
    if (!botaoAbrirAjuda) {
        return;
    }
    const funcoesAjuda = () => {
        const botaoParceiro = document.querySelector('.botao_indicar_loja');
        botaoParceiro.addEventListener('click', () => {
            paginaIndicarParceiro.abrir();
        });
    };

    const enviarDados = () => {
        const form = $('#form_indicar_loja');
        const inputNome = $('#input_indicar_loja_nome');
        const inputTelefone = $('#input_indicar_loja_telefone');
        const inputEmail = $('#input_indicar_loja_email');
        const inputMensagem = $('#input_indicar_loja_nome_mensagem');
        const botaoEnviarIndicacao = document.querySelector('#botao_enviar_indicacao');

        botaoEnviarIndicacao.addEventListener('click', () => {
            const resposta = ajaxPost(
                LINK + '/convenios/indicar',
                {
                    nome: inputNome.value,
                    telefone: inputTelefone.value,
                    email: inputEmail.value,
                    mensagem: inputMensagem.value,
                },
                'Ocorre um erro ao fazer sua indicação, por favor, tente novamente.'
            );

            if (false === resposta) {
                return;
            }

            inputNome.value = '';
            inputTelefone.value = '';
            inputEmail.value = '';
            inputMensagem.value = '';

            Alerta.mensagem(
                'Indicação realizada',
                `Você indica a empresa que gostaria de ter desconto exclusivo e nós negociaremos o melhor para você!
                <br> Esse processo completo leva em torno de 90 dias.
                <br> Para garantirmos exclusividade e segurança, fazemos pesquisa sobre o histórico no Reclame Aqui,
                aprovação das condições de desconto e análise jurídica, mas não se preocupe que avisaremos por e-mail a finalização do processo.`,
                true
            );
        });
    };

    const paginaAjuda = new Pagina('Ajuda', LINK + '/ajuda', {}, true, true, funcoesAjuda);
    const paginaIndicarParceiro = new Pagina(
        'Indicar um Parceiro',
        LINK + '/indique-um-parceiro',
        null,
        true,
        true,
        enviarDados
    );

    botaoAbrirAjuda.addEventListener('click', () => {
        paginaAjuda.abrir();
    });
});
