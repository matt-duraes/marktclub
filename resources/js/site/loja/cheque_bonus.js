const PaginaGeral = new Pagina();
const salvarChequeBonus = () => {
    const blocoDependente = $('#bloco_dependente');
    const blocoTitular = $('#bloco_titular');
    const blocoEndereco = $('#bloco_endereco');
    const blocoTermo = $('#bloco_termo');

    const botaoSalvar = $('#botao_solicitar_cheque_bonus');

    const automovel = $('#input_automovel').value;
    const inputTipoUsuario = $('#input_cheque_tipo_usuario');
    const inputDependenteNome = $('#input_cheque_dependente_nome');
    const inputDependenteEmail = $('#input_cheque_dependente_email');
    const inputDependenteCpf = $('#input_cheque_dependente_cpf');
    const inputDependenteRg = $('#input_cheque_dependente_rg');
    const inputDependenteGrauParentesco = $('#input_cheque_dependente_grau_parentesco');
    const inputDependenteDataNascimento = $('#input_cheque_dependente_data_nascimento');
    const inputNome = $('#input_nome');
    const inputEmailPessoal = $('#input_email_pessoal');
    const inputEstadoCivil = $('#input_cheque_estado_civil');
    const inputRg = $('#input_cheque_rg');
    const inputTelefoneCelular = $('#input_cheque_telefone_celular');
    const inputDataNascimento = $('#input_cheque_data_nascimento');
    const inputEnderecoCep = $('#input_cheque_endereco_cep');
    const inputEnderecoLogradouro = $('#input_cheque_endereco_logradouro');
    const inputEnderecoNumero = $('#input_cheque_endereco_numero');
    const inputEnderecoComplemento = $('#input_cheque_endereco_complemento');
    const inputEnderecoBairro = $('#input_cheque_endereco_bairro');
    const inputEnderecoEstado = $('#input_cheque_endereco_estado');
    const inputEnderecoCidade = $('#input_cheque_endereco_cidade');
    const inputTermo = $('#input_termo');
    const inputAtualizar = $('#input_atualizar');

    inputTipoUsuario.evento('formChange', () => {
        blocoDependente.classList.add('display_none');
        blocoTitular.classList.add('display_none');
        blocoEndereco.classList.add('display_none');
        botaoSalvar.classList.add('display_none');
        blocoTermo.classList.add('display_none');

        if (inputTipoUsuario.value == '') {
            return;
        } else if (inputTipoUsuario.value == 'dependente') {
            blocoDependente.classList.remove('display_none');
        } else if (inputTipoUsuario.value == 'titular') {
            limparDepdente();
        }

        botaoSalvar.classList.remove('display_none');
        blocoTitular.classList.remove('display_none');
        blocoEndereco.classList.remove('display_none');
        blocoTermo.classList.remove('display_none');
    });
    const limparDepdente = () => {
        formValue(inputDependenteNome, '');
        formValue(inputDependenteEmail, '');
        formValue(inputDependenteCpf, '');
        formValue(inputDependenteRg, '');
        formValue(inputDependenteGrauParentesco, '');
        formValue(inputDependenteDataNascimento, '');
    };
    buscarEnderecoPeloCep(
        inputEnderecoCep,
        inputEnderecoLogradouro,
        inputEnderecoNumero,
        inputEnderecoBairro,
        inputEnderecoCidade,
        inputEnderecoEstado,
        true
    );
    inputEnderecoEstado.evento('formChange', () => {
        buscarCidadePeloEstado(inputEnderecoCidade, inputEnderecoEstado.value, '', 'Escolha uma cidade');
    });

    // Salvar com enter
    [
        inputDependenteNome,
        inputDependenteEmail,
        inputDependenteCpf,
        inputDependenteRg,
        inputDependenteDataNascimento,
        inputNome,
        inputEmailPessoal,
        inputRg,
        inputTelefoneCelular,
        inputDataNascimento,
        inputEnderecoLogradouro,
        inputEnderecoNumero,
        inputEnderecoComplemento,
        inputEnderecoBairro,
    ].forEach(input => {
        input.addEventListener('keydown', e => {
            if (e.key == 'Enter') {
                salvarSolicitacao();
            }
        });
    });
    botaoSalvar.addEventListener('click', () => {
        salvarSolicitacao();
    });
    const salvarSolicitacao = async () => {
        if (
            (inputTipoUsuario.value == 'dependente' && !(await validarInput(blocoDependente))) ||
            !(await validarInput(blocoTitular)) ||
            !(await validarInput(blocoEndereco))
        ) {
            return;
        } else if (!inputTermo.checked) {
            Alerta.notificacao(
                'Marque o box com a autorização para compartilhamento de dados para poder continuar.',
                false
            );
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/convenios/cheque-bonus',
            {
                /* eslint-disable */
                email_pessoal: inputEmailPessoal.value,
                tipo_usuario: inputTipoUsuario.value,
                dependente_nome: inputDependenteNome.value,
                dependente_email: inputDependenteEmail.value,
                dependente_cpf: inputDependenteCpf.value,
                dependente_rg: inputDependenteRg.value,
                dependente_grau_parentesco: inputDependenteGrauParentesco.value,
                dependente_data_nascimento: inputDependenteDataNascimento.value,
                estado_civil: inputEstadoCivil.value,
                telefone_celular: inputTelefoneCelular.value,
                data_nascimento: inputDataNascimento.value,
                endereco_cep: inputEnderecoCep.value,
                endereco_logradouro: inputEnderecoLogradouro.value,
                endereco_numero: inputEnderecoNumero.value,
                endereco_complemento: inputEnderecoComplemento.value,
                endereco_bairro: inputEnderecoBairro.value,
                endereco_estado: inputEnderecoEstado.value,
                endereco_cidade: inputEnderecoCidade.value,
                data_termo: 'sim',
                /* eslint-enable */
                nome: inputNome.value,
                rg: inputRg.value,
                atualizar: inputAtualizar && inputAtualizar.checked ? 'sim' : 'nao',
                automovel,
            },
            'Erro ao solicitar cheque bônus, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        Alerta.mensagem(
            'Solicitação enviada',
            'A cheque bônus foi solicitada com sucesso e será encaminhada para seu e-mail após assinatura do documento, em até 8h úteis.',
            true
        );
        PaginaGeral.fechar();
    };
};
const procedimentoChequeBonus = parceiro => {
    const id = parceiro.getAttribute('data-url');
    const PaginaCheque = new Pagina(
        'cheque-bonus-' + id,
        LINK + '/convenios/cheque-bonus/' + id,
        undefined,
        true,
        true,
        salvarChequeBonus
    );

    parceiro.addEventListener('click', () => {
        PaginaCheque.abrir();
    });
};
