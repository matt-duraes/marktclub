window.addEventListener('load', () => {
    const botaoIndicarLoja = $$('.botao_indicar_loja');
    if (!botaoIndicarLoja) {
        return;
    }
    const indicarParceiro = () => {
        const form = $('#form_indicar_loja');
        const inputNome = $('#input_indicar_loja_nome');
        const inputTelefone = $('#input_indicar_loja_telefone');
        const inputEmail = $('#input_indicar_loja_email');
        const inputMensagem = $('#input_indicar_loja_nome_mensagem');

        const botaoEnviarIndicacao = document.querySelector('#botao_enviar_indicacao');
        botaoEnviarIndicacao.addEventListener('click', async () => {
            if (!(await validarInput(form))) {
                return;
            }
            Loading.show();
            const resposta = await ajaxPost(
                LINK + '/convenios/indicar',
                {
                    nome: inputNome.value,
                    telefone: inputTelefone.value,
                    email: inputEmail.value,
                    mensagem: inputMensagem.value,
                },
                'Ocorre um erro ao fazer sua indicação, por favor, tente novamente.'
            );

            Loading.hide();
            if (false === resposta) {
                return;
            }

            formValue(inputNome, '');
            formValue(inputTelefone, '');
            formValue(inputEmail, '');
            formValue(inputMensagem, '');

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

    const paginaIndicar = new Pagina(
        'Indicar um Parceiro',
        LINK + '/indique-um-parceiro',
        {},
        true,
        true,
        indicarParceiro
    );
    const abrirIndicacao = () => {
        paginaIndicar.abrir();
    };
    adicionarEvento('click', botaoIndicarLoja, abrirIndicacao);
});
