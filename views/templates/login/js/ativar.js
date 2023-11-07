const setarTipoInput = (valorData = '') => {
    const dataTipo = document.querySelector('.bloco_titular');
    const tipoAtivacao = dataTipo.getAttribute('data-ativacao');
    const input = document.querySelector('#input_buscar');

    if (tipoAtivacao == 'siape' && valorData != 'dependente') {
        input.setAttribute('placeholder', 'Digite o seu SIAPE');
        return;
    }
    if (tipoAtivacao == 'matricula' && valorData != 'dependente') {
        input.setAttribute('placeholder', 'Digite a sua matrícula');
        return;
    }

    if (tipoAtivacao == 'email' && valorData != 'dependente') {
        input.setAttribute('placeholder', 'Digite o seu e-mail');
        input.setAttribute('type', 'email');
        return;
    }

    input.setAttribute('placeholder', 'Digite o seu CPF');
    input.setAttribute('data-mascara', '000.000.000-00');
    input.setAttribute('inputmode', 'numeric');
};

const loadingAtivarBuscar = () => {
    setarTipoInput();
    const botoesTipoUsuario = document.querySelectorAll('.botao_tipo_usuario');
    let valorData = '';
    botoesTipoUsuario.forEach(botao => {
        botao.addEventListener('click', e => {
            valorData = botao.getAttribute('data-tipo');
            setarTipoInput(valorData);
            if (valorData == 'dependente') {
                $('.botao_titular').classList.remove('cor_bg');
                $('.botao_dependente').classList.add('cor_bg');
                return;
            }
            $('.botao_dependente').classList.remove('cor_bg');
            $('.botao_titular').classList.add('cor_bg');
        });
    });

    const form = $('#bloco_form_buscar');
    const botaoBuscar = $('#botao_buscar_usuario');
    const inputBuscar = $('#input_buscar');

    inputBuscar.focus();

    botaoBuscar.addEventListener('click', async () => {
        if (!(await validarInput(form))) {
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(LINK + '/login/ativar-buscar', {
            busca: inputBuscar.value,
            tipo_usuario: valorData,
        });
        Loading.hide();
        if (false == resposta) {
            return;
        }
        const PaginaAtivar = new Pagina(
            'ativar-conta',
            `${LINK}/login/ativar-salvar?hash=${resposta.dado.hash}&cpf=${resposta.dado.cpf}`,
            undefined,
            true,
            false,
            loadingAtivar
        );
        PaginaAtivar.abrir();
    });
};

const loadingAtivar = () => {
    const hash = $('#input_ativar_hash_busca').value;
    const cpf = $('#input_ativar_cpf_busca').value;

    const form = $('#bloco_form_ativar');

    const inputNome = $('#input_ativar_nome');
    const inputCpf = $('#input_ativar_cpf');
    const inputDataNascimento = $('#input_ativar_data_nascimento');
    const inputGenero = $('#input_ativar_genero');
    const inputEstadoCivil = $('#input_ativar_estado_civil');
    const inputEmailPessoal = $('#input_ativar_email_pessoal');
    const inputEmailTrabalho = $('#input_ativar_email_trabalho');
    const inputTelefonePessoal = $('#input_ativar_telefone_pessoal');
    const inputTelefoneTrabalho = $('#input_ativar_telefone_trabalho');
    const inputEnderecoCep = $('#input_ativar_endereco_cep');
    const inputEnderecoLogradouro = $('#input_ativar_endereco_logradouro');
    const inputEnderecoNumero = $('#input_ativar_endereco_numero');
    const inputEnderecoComplemento = $('#input_ativar_endereco_complemento');
    const inputEnderecoBairro = $('#input_ativar_endereco_bairro');
    const inputEnderecoEstado = $('#input_ativar_endereco_estado');
    const inputEnderecoCidade = $('#input_ativar_endereco_cidade');
    const inputSenhaNova = $('#input_ativar_senha_nova');
    const inputSenhaRepetir = $('#input_ativar_senha_repetir');
    const inputTermo = $('#input_ativar_termo');

    buscarEnderecoPeloCep(
        inputEnderecoCep,
        inputEnderecoLogradouro,
        inputEnderecoNumero,
        inputEnderecoBairro,
        inputEnderecoCidade,
        inputEnderecoEstado,
        true
    );
    inputEnderecoEstado.addEventListener('formChange', () => {
        buscarCidadePeloEstado(inputEnderecoCidade, inputEnderecoEstado.value, '', 'Escolha uma cidade');
    });

    const botaoSalvar = $('#botao_ativar_usuario');
    inputNome.focus();

    const salvarUsuario = async () => {
        if (!(await validarInput(form))) {
            return;
        } else if (inputSenhaNova.value != inputSenhaRepetir.value) {
            Alerta.notificacao('O campo repetir senha não é igual a senha digitada.', false);
            return;
        } else if (!inputTermo.checked) {
            Alerta.notificacao('Você precisa aceitar os termos para continuar.', false);
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/login/ativar-salvar',
            {
                hash,
                nome: inputNome.value,
                cpf: inputCpf ? inputCpf.value : cpf,
                genero: inputGenero.value,
                senha: inputSenhaNova.value,
                termo: inputTermo.checked ? 'sim' : 'nao',
                /* eslint-disable */
                data_nascimento: inputDataNascimento.value,
                estado_civil: inputEstadoCivil.value,
                email_pessoal: inputEmailPessoal.value,
                email_trabalho: inputEmailTrabalho.value,
                telefone_pessoal: inputTelefonePessoal.value,
                telefone_trabalho: inputTelefoneTrabalho.value,
                endereco_cep: inputEnderecoCep.value,
                endereco_logradouro: inputEnderecoLogradouro.value,
                endereco_numero: inputEnderecoNumero.value,
                endereco_complemento: inputEnderecoComplemento.value,
                endereco_bairro: inputEnderecoBairro.value,
                endereco_estado: inputEnderecoEstado.value,
                endereco_cidade: inputEnderecoCidade.value,
                /* eslint-enable */
            },
            'Erro ao ativar seu usuário, por favor, tente novamente.'
        );

        if (false == resposta) {
            Loading.hide();
            return;
        }
        window.location.replace(LINK);
    };
    botaoSalvar.addEventListener('click', salvarUsuario);
};
