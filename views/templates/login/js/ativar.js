// @system "Alerta"

window.addEventListener('load', async () => {
    const queryString = window.location.search;
    const searchParams = new URLSearchParams(queryString);
    const hash = searchParams.get('hash');
    const tipoUsuario = searchParams.get('tipo_usuario');

    if (!hash || !tipoUsuario) {
        return;
    }

    const resposta = await ajaxPost(LINK + '/login/ativar-validar', {
        hash: hash,
    });

    if (resposta == false) {
        return;
    }

    criarPaginaAtivarSalvar({
        hash,
        tipoUsuario: tipoUsuario,
    });
});

const setarTipoInput = (valorData = '') => {
    const blocoTitular = document.querySelector('.bloco_titular');
    const tipoAtivacao = blocoTitular.getAttribute('data-ativacao');
    const input = document.querySelector('#input_buscar');
    const inputCpf = document.querySelector('#input_buscar_cpf');
    blocoTitular.classList.remove('display_none');
    inputCpf.parentNode.classList.add('display_none');
    input.parentNode.classList.remove('display_none');

    if ((tipoAtivacao == 'email' && valorData != 'dependente') || valorData == 'indicado') {
        inputCpf.parentNode.classList.add('display_none');
        input.setAttribute('placeholder', 'Digite o seu e-mail');
        input.setAttribute('type', 'email');
        return;
    }

    if (tipoAtivacao == 'siape' && valorData != 'dependente') {
        inputCpf.parentNode.classList.add('display_none');
        input.setAttribute('placeholder', 'Digite o seu SIAPE');
        return;
    }
    if (tipoAtivacao == 'matricula' && valorData != 'dependente') {
        inputCpf.parentNode.classList.add('display_none');
        input.setAttribute('placeholder', 'Digite a sua matrícula');
        return;
    }
    input.parentNode.classList.add('display_none');
    inputCpf.parentNode.classList.remove('display_none');
};
const loadingAtivarBuscar = () => {
    const botoesTipoUsuario = document.querySelectorAll('.botao_tipo_usuario');
    const blocoAviso = document.querySelector('.aviso_cadastro');
    let valorData = '';
    botoesTipoUsuario.forEach(botao => {
        botao.addEventListener('click', e => {
            const botaoDependente = $('.botao_dependente');
            const botaoTitular = $('.botao_titular');
            const botaoIndicado = $('.botao_indicado');

            valorData = botao.getAttribute('data-tipo');
            setarTipoInput(valorData);

            if (botaoDependente) {
                botaoDependente.classList.remove('cor_bg');
            }
            if (botaoTitular) {
                botaoTitular.classList.remove('cor_bg');
            }
            if (botaoIndicado) {
                botaoIndicado.classList.remove('cor_bg');
            }
            botao.classList.add('cor_bg');
            blocoAviso.classList.toggle('display_none', valorData != 'dependente');
            const blocoCiesc = $$('.bloco_ciesc');
            if (valorData == 'titular' && blocoCiesc.length > 0) {
                blocoCiesc.aparecer();
            } else if (valorData != 'titular' && blocoCiesc.length > 0) {
                blocoCiesc.sumir();
            }
        });
    });

    const RECAPTCHA = $('#RECAPTCHA').value;
    const RECAPTCHAV2 = $('#RECAPTCHA_V2').value;
    const formHash = $('#bloco_form_buscar input[name="form_system_hash"]').value;
    const form = $('#bloco_form_buscar');
    const botaoBuscar = $('#botao_buscar_usuario');
    const inputBuscar = $('#input_buscar');
    const inputBuscarCpf = $('#input_buscar_cpf');
    const inputLocalTrabalho = $('#input_local_trabalho');
    const inputCodigo = $('#input_codigo');
    const inputTermoAtivarBuscar = $('#input_termo_ativar_buscar');
    const blocoRecaptcha = $('#bloco_captcha_ativar');

    inputBuscar.focus();

    let captchaVersao = 3;
    const pegarCaptcha = async () => {
        if (captchaVersao == 2) {
            const captcha = grecaptcha.getResponse(0);
            if (captcha == '') {
                Alerta.notificacao('Marque o box de "Não sou um Robô" para continuar.', false);
                return false;
            }
            return 'v2.' + captcha;
        }

        return grecaptcha
            .execute(RECAPTCHA, { action: 'create_singup' })
            .then(function (token) {
                return 'v3.' + token;
            })
            .catch(async () => {
                await Alerta.mensagem('Erro ao carregar recaptcha', 'Recarregue a página e tente novamente.', false);
                return false;
            });
    };
    let captcha2AtivarBuscar;
    const mostrarCaptchaV2 = () => {
        if (captchaVersao == 2) {
            return;
        }
        captchaVersao = 2;
        blocoRecaptcha.classList.remove('display_none');
        captcha2AtivarBuscar = grecaptcha.render('bloco_captcha_ativar', {
            sitekey: RECAPTCHAV2,
            theme: 'light',
        });
    };

    const resetarCaptcha = () => {
        if (captchaVersao != 2) {
            return;
        }
        grecaptcha.reset(captcha2AtivarBuscar);
    };

    botaoBuscar.addEventListener('click', async () => {
        if (!(await validarInput(form))) {
            return;
        } else if (valorData == 'titular' && inputTermoAtivarBuscar && !inputTermoAtivarBuscar.checked) {
            Alerta.notificacao('Você precisa aceitar os termos para continuar.', false);
            return;
        }

        const captchaToken = await pegarCaptcha();
        if (captchaToken == '' && captchaVersao == 2) {
            Alerta.notificacao('Clique no box do captcha para continuar.', false);
            return;
        }

        let termo = '';
        if (inputTermoAtivarBuscar && valorData == 'titular') {
            termo = inputTermoAtivarBuscar.checked ? 'sim' : 'nao';
        }

        Loading.show();
        const body = new FormData();
        body.append('busca', inputBuscar.value ? inputBuscar.value : inputBuscarCpf.value);
        body.append('tipo_usuario', valorData);
        body.append('local_trabalho', inputLocalTrabalho ? inputLocalTrabalho.valor() : '');
        body.append('codigo', inputCodigo ? inputCodigo.valor() : '');
        body.append('termo', termo);
        body.append('form_system_hash', formHash);
        body.append('form_system_validacao', '');
        body.append('form_system_captcha', captchaToken);
        const resposta = await fetch(LINK + '/login/ativar-buscar', {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();

        const respostaJson = json instanceof Object;
        const deuErro = respostaJson && json.status == 'erro';
        if (!respostaJson || json.status === undefined) {
            resetarCaptcha();
            Alerta.notificacao('Ocorreu um erro ao tentar buscar seus dados, por favor, tente novamente.', false);
            return;
        } else if (deuErro && json.erro.captcha != undefined && false === json.erro.captcha && captchaVersao == 3) {
            Alerta.notificacao(
                'Não foi possível validar seu captcha, por favor, marque o box de "Não sou um robô" para continuar.',
                false
            );
            mostrarCaptchaV2();
            return;
        } else if (deuErro) {
            Alerta.notificacao(json.erro.mensagem || 'Erro ao buscar seus dados, por favor, tente novamente.', false);
            resetarCaptcha();
            return;
        }

        if (valorData == 'indicado') {
            criarPaginaAtivarSalvar({
                hash: resposta.dado.hash,
                tipoUsuario: valorData,
            });
            return;
        }
        json.dado.tipoUsuario = valorData;
        criarPaginaAtivarSalvar(json.dado);
    });
};

const criarPaginaAtivarSalvar = dado => {
    const body = new FormData();
    body.append('hash', dado.hash);
    body.append('cpf', dado.cpf);
    body.append('tipo_usuario', dado.tipoUsuario);
    body.append('imutavel', dado.imutavel);

    const PaginaAtivar = new Pagina(
        'ativar-conta',
        `${LINK}/login/ativar-salvar-pagina`,
        {
            method: 'POST',
            body,
        },
        true,
        false,
        loadingAtivar
    );
    PaginaAtivar.abrir();
};

const loadingAtivar = () => {
    const hash = $('#input_ativar_hash_busca').value;
    const cpf = $('#input_ativar_cpf_busca').value;
    const tipoUsuario = $('#input_tipo_usuario').value;

    const form = $('#bloco_form_ativar');

    const inputNome = $('#input_ativar_nome');
    const inputCpf = $('#input_ativar_cpf');
    const inputDataNascimento = $('#input_ativar_data_nascimento');
    const inputGenero = $('#input_ativar_genero');
    const inputEstadoCivil = $('#input_ativar_estado_civil');
    const inputGrupo = $('#input_ativar_grupo');
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

    const inputCargo = $('#input_ativar_cargo');
    const inputLotacao = $('#input_ativar_lotacao');
    if (
        inputEnderecoCep &&
        inputEnderecoLogradouro &&
        inputEnderecoNumero &&
        inputEnderecoBairro &&
        inputEnderecoCidade &&
        inputEnderecoEstado
    ) {
        buscarEnderecoPeloCep(
            inputEnderecoCep,
            inputEnderecoLogradouro,
            inputEnderecoNumero,
            inputEnderecoBairro,
            inputEnderecoCidade,
            inputEnderecoEstado,
            true
        );
    }
    if (inputEnderecoEstado && inputEnderecoCidade) {
        inputEnderecoEstado.evento('formChange', () => {
            buscarCidadePeloEstado(inputEnderecoCidade, inputEnderecoEstado.value, '', 'Escolha uma cidade');
        });
        if (buscarCidadePeloEstado.value != '') {
            buscarCidadePeloEstado(
                inputEnderecoCidade,
                inputEnderecoEstado.value,
                inputEnderecoCidade.value,
                'Escolha uma cidade'
            );
        }
    }
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
                nome: pegarValorInput(inputNome),
                cpf: inputCpf ? inputCpf.value : cpf,
                genero: pegarValorInput(inputGenero),
                senha: pegarValorInput(inputSenhaNova),
                termo: inputTermo.checked ? 'sim' : 'nao',
                /* eslint-disable */
                data_nascimento: pegarValorInput(inputDataNascimento),
                estado_civil: pegarValorInput(inputEstadoCivil),
                grupo: pegarValorInput(inputGrupo),
                email_pessoal: pegarValorInput(inputEmailPessoal),
                email_trabalho: pegarValorInput(inputEmailTrabalho),
                telefone_pessoal: pegarValorInput(inputTelefonePessoal),
                telefone_trabalho: pegarValorInput(inputTelefoneTrabalho),
                endereco_cep: pegarValorInput(inputEnderecoCep),
                endereco_logradouro: pegarValorInput(inputEnderecoLogradouro),
                endereco_numero: pegarValorInput(inputEnderecoNumero),
                endereco_complemento: pegarValorInput(inputEnderecoComplemento),
                endereco_bairro: pegarValorInput(inputEnderecoBairro),
                endereco_estado: pegarValorInput(inputEnderecoEstado),
                endereco_cidade: pegarValorInput(inputEnderecoCidade),
                trabalho_cargo: pegarValorInput(inputCargo),
                trabalho_empresa: pegarValorInput(inputLotacao),
                tipo_usuario: tipoUsuario || '',
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

const pegarValorInput = input => {
    if (!input) {
        return '';
    }

    return input.value;
};
