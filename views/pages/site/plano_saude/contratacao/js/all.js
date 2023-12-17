// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Form"
// @system "Galeria"
// @system "Calendario"
// @system "Mascara"
// @resource "site/passo_passo"

const idSimulacao = $('#input_id_simulacao').value;
const botaoDadoPessoalProximo = $('#botao_dado_pessoal_proximo');
const botaoResponsavelAnterior = $('#botao_responsavel_anterior');
const botaoResponsavelProximo = $('#botao_responsavel_proximo');
const botaoContatoAnterior = $('#botao_contato_anterior');
const botaoContatoProximo = $('#botao_contato_proximo');
const botaoEnderecoAnterior = $('#botao_endereco_anterior');
const botaoContratarEnviar = $('#botao_contratar_enviar');

// DADOS PESSOAIS
const blocoDadoPessoal = $('#bloco_dado_pessoal');
const inputNome = $('#input_nome');
const inputNaturalidade = $('#input_naturalidade');
const inputCpf = $('#input_cpf');
const inputDataNascimento = $('#input_data_nascimento');
const inputGenero = $('#input_genero');
const inputEstadoCivil = $('#input_estado_civil');
const inputPeso = $('#input_peso');
const inputAltura = $('#input_altura');
const inputRg = $('#input_rg');
const inputOrgaoExpedidor = $('#input_orgao_expedidor');
const inputNomeMae = $('#input_nome_mae');

// RESPONSÁVEL
const blocoResponsavel = $('#bloco_responsavel');
const inputResponsavel = $('#input_responsavel');
const inputResponsavelNome = $('#input_responsavel_nome');
const inputResponsavelCpf = $('#input_responsavel_cpf');
const inputResponsavelRg = $('#input_responsavel_rg');
const inputResponsavelOrgaoExpedidor = $('#input_responsavel_orgao_expedidor');

// CONTATO
const blocoContato = $('#bloco_contato');
const inputEmailPessoal = $('#input_email_pessoal');
const inputTelefoneCelular = $('#input_telefone_celular');
const inputTelefoneResidencial = $('#input_telefone_residencial');
const inputTelefoneComercial = $('#input_telefone_comercial');
const inputRamal = $('#input_ramal');

// ENDEREÇO
const blocoEndereco = $('#bloco_endereco');
const inputCep = $('#input_cep');
const inputBairro = $('#input_bairro');
const inputLogradouro = $('#input_logradouro');
const inputNumero = $('#input_numero');
const inputComplemento = $('#input_complemento');
const inputCidade = $('#input_cidade');
const inputEstado = $('#input_estado');

executarPassoPasso();

// DADO PESSOAL
botaoDadoPessoalProximo.addEventListener('click', async () => {
    if (!(await validarInput(blocoDadoPessoal))) {
        return;
    }
    if (inputNome.value.trim().split(' ').length < 2) {
        Alerta.notificacao('Digite seu nome completo para continuar.', false);
        return;
    }
    irParaProximoPasso(botaoDadoPessoalProximo);
});

// RESPONSAVEL
botaoResponsavelAnterior.addEventListener('click', () => {
    irParaPassoAnterior(botaoResponsavelAnterior);
});
botaoResponsavelProximo.addEventListener('click', async () => {
    if (!(await validarInput(blocoResponsavel))) {
        return;
    }
    irParaProximoPasso(botaoResponsavelProximo);
});
inputResponsavel.addEventListener('change', () => {
    if (inputResponsavel.checked) {
        irParaProximoPasso(botaoResponsavelProximo);
        setarResponsavelComoUsuario();
        return;
    }
    formValue(inputResponsavelNome, '');
    formValue(inputResponsavelCpf, '');
    formValue(inputResponsavelRg, '');
    formValue(inputResponsavelOrgaoExpedidor, '');
});
const setarResponsavelComoUsuario = () => {
    formValue(inputResponsavelNome, inputNome.value);
    formValue(inputResponsavelCpf, inputCpf.value);
    formValue(inputResponsavelRg, inputRg.value);
    formValue(inputResponsavelOrgaoExpedidor, inputOrgaoExpedidor.value);
};

// CONTATO
botaoContatoAnterior.addEventListener('click', () => {
    irParaPassoAnterior(botaoContatoAnterior);
});
botaoContatoProximo.addEventListener('click', async () => {
    if (!(await validarInput(blocoContato))) {
        return;
    }
    irParaProximoPasso(botaoContatoProximo);
});

// ENDERECO
buscarEnderecoPeloCep(inputCep, inputLogradouro, inputNumero, inputBairro, inputCidade, inputEstado, true);
inputEstado.addEventListener('formChange', () => {
    buscarCidadePeloEstadoViaBrowser(inputCidade, inputEstado.value, '', 'Escolha uma cidade');
});
botaoEnderecoAnterior.addEventListener('click', () => {
    irParaPassoAnterior(botaoEnderecoAnterior);
});

// ENVIAR
botaoContratarEnviar.addEventListener('click', async () => {
    if (!validarInput(blocoEndereco)) {
        return;
    }
    Loading.show();
    const resposta = await ajaxPost(
        LINK + '/saude-contratacao',
        {
            /* eslint-disable camelcase */
            id_saude_simulacao: idSimulacao,
            data_nascimento: inputDataNascimento.value,
            estado_civil: inputEstadoCivil.value,
            orgao_expedidor: inputOrgaoExpedidor.value,
            responsavel_nome: inputResponsavelNome.value,
            responsavel_cpf: inputResponsavelCpf.value,
            responsavel_rg: inputResponsavelRg.value,
            responsavel_orgao_expedidor: inputResponsavelOrgaoExpedidor.value,
            email_pessoal: inputEmailPessoal.value,
            telefone_celular: inputTelefoneCelular.value,
            telefone_residencial: inputTelefoneResidencial.value,
            telefone_comercial: inputTelefoneComercial.value,
            telefone_comercial_ramal: inputRamal.value,
            nome_mae: inputNomeMae.value,
            endereco_cep: inputCep.value,
            endereco_bairro: inputBairro.value,
            endereco_logradouro: inputLogradouro.value,
            endereco_numero: inputNumero.value,
            endereco_complemento: inputComplemento.value,
            endereco_cidade: inputCidade.value,
            endereco_estado: inputEstado.value,
            /* eslint-enable camelcase */
            nome: inputNome.value,
            naturalidade: inputNaturalidade.value,
            cpf: inputCpf.value,
            genero: inputGenero.value,
            peso: inputPeso.value,
            altura: inputAltura.value,
            rg: inputRg.value,
        },
        'Erro ao salvar contratação, por favor, tente novamente.'
    );
    Loading.hide();
    if (false === resposta) {
        return;
    }
    await Alerta.mensagem(
        'Solicitação enviada',
        'Seus dados foram enviados com sucesso. Em média, o tempo de retorno do parceiro está sendo em 72 horas.',
        true
    );
    window.location.assign(LINK + '/saude');
});

$$('.tirar_tab input').evento('keydown', (e, el) => {
    if (!e.shiftKey && e.key == 'Tab') {
        e.preventDefault();
    }
});
