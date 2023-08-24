window.addEventListener('load', () => {
    const form = $('#form_solicitacao_automovel');
    const botao = $('#botao_enviar_automovel');
    const inputEnderecoEstado = $('#input_solicitacao_endereco_estado');
    const inputEnderecoCidade = $('#input_solicitacao_endereco_cidade');
    const inputMontadora = $('#input_solicitacao_montadora');
    const inputModelo = $('#input_solicitacao_modelo');
    const inputVersao = $('#input_solicitacao_versao');
    const inputCor = $('#input_solicitacao_cor');
    const inputMensagem = $('#input_solicitacao_mensagem');

    inputEnderecoEstado.addEventListener('formChange', () => {
        buscarCidadePeloEstado(inputEnderecoCidade, inputEnderecoEstado.value, '', 'Escolha uma cidade');
    });
    const salvarSolicitacao = async () => {
        if (!(await validarInput(form))) {
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/automovel/solicitacao',
            {
                /* eslint-disable */
                endereco_estado: inputEnderecoEstado.value,
                endereco_cidade: inputEnderecoCidade.value,
                /* eslint-enable */
                montadora: inputMontadora.value,
                modelo: inputModelo.value,
                versao: inputVersao.value,
                cor: inputCor.value,
                mensagem: inputMensagem.value,
            },
            'Ocorreu um erro ao salvar solicitação, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        Alerta.mensagem('Solicitação enviada', 'Sua solicitação foi enviada com sucesso.', true);
        limparFormulario(form);
    };
    adicionarEventoEnter([inputMontadora, inputModelo, inputVersao, inputCor], salvarSolicitacao);
    adicionarEvento('click', botao, salvarSolicitacao);
});
