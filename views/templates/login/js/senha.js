window.addEventListener('load', () => {
    let usuario, hash;
    const blocoCpf = $('#bloco_cpf');
    const blocoCodigo = $('#bloco_codigo');
    const blocoSenha = $('#bloco_senha');

    const inputSenhaNova = $('#input_senha_nova');
    const inputSenhaRepetir = $('#input_senha_repetir');

    const inputCpf = $('#input_senha_cpf');
    const inputCodigo1 = $('#input_codigo_1');
    const inputCodigo2 = $('#input_codigo_2');
    const inputCodigo3 = $('#input_codigo_3');
    const inputCodigo4 = $('#input_codigo_4');
    const inputCodigo5 = $('#input_codigo_5');
    const inputCodigo6 = $('#input_codigo_6');

    const blocoReenviarContador = $('#bloco_reenviar_contador');
    const blocoReenviarNumero = $('#bloco_reenviar_numero');

    const botaoReenviarCodigo = $('#botao_reenviar_codigo');
    const botaoEnviarCodigo = $('#botao_enviar_codigo');
    const botaoValidarCodigo = $('#botao_validar_codigo');
    const botaoAlterarSenha = $('#botao_alterar_senha');

    let cpf;
    [botaoEnviarCodigo, botaoReenviarCodigo].forEach(botao => {
        botao.addEventListener('click', async () => {
            if (inputCpf.value == '') {
                Alerta.notificacao('Digite seu CPF para continuar.', false);
                return;
            }
            cpf = inputCpf.value;
            Loading.show();
            const resposta = await ajaxPost(
                LINK + '/login/senha-buscar',
                {
                    cpf,
                },
                'Erro ao buscar seu usuário, por favor, tente novamente.'
            );
            Loading.hide();
            if (false === resposta) {
                return;
            }

            usuario = resposta.dado.id;
            codigoEnviado();
        });
    });

    let contar, numero;
    const codigoEnviado = () => {
        blocoCpf.classList.add('display_none');
        blocoCodigo.classList.remove('display_none');
        botaoEnviarCodigo.classList.add('display_none');
        botaoValidarCodigo.classList.remove('display_none');
        blocoReenviarContador.classList.remove('display_none');
        botaoReenviarCodigo.classList.add('display_none');

        inputCodigoLista.forEach(input => {
            input.value = '';
        });

        numero = 59;
        blocoReenviarNumero.innerText = 60;
        if (contar) {
            clearInterval(contar);
        }
        contar = setInterval(() => {
            if (numero == 1) {
                blocoReenviarContador.classList.add('display_none');
                botaoReenviarCodigo.classList.remove('display_none');
                clearInterval(contar);
                return;
            }
            blocoReenviarNumero.innerText = numero--;
        }, 1000);
    };

    const inputCodigoLista = [inputCodigo1, inputCodigo2, inputCodigo3, inputCodigo4, inputCodigo5, inputCodigo6];
    inputCodigoLista.forEach(input => {
        input.addEventListener('paste', e => {
            e.preventDefault();
            const codigo = e.clipboardData.getData('text/plain').replace(/[^0-9]/g, '');
            if (codigo == '') {
                return;
            }
            const quantiade = codigo.length > 6 ? 6 : codigo.length;
            let i = 0;
            for (; i < quantiade; ++i) {
                inputCodigoLista[i].value = codigo[i];
            }
            if (quantiade == 6) {
                input.blur();
                validarCodigo();
                return;
            }
            inputCodigoLista[quantiade + 1].focus();
        });
        input.addEventListener('keyup', e => {
            if (!/^[0-9]$/.test(e.key)) {
                return;
            }
            let i = 0;
            let enviar = true;
            for (; i < 6; ++i) {
                if (!/^[0-9]{1}$/.test(inputCodigoLista[i].value)) {
                    enviar = false;
                }
                if (input == inputCodigoLista[i] && i < 5) {
                    inputCodigoLista[i + 1].focus();
                    break;
                } else if (input == inputCodigoLista[i] && i == 5) {
                    inputCodigo6.blur();
                    if (enviar) {
                        validarCodigo();
                    }
                }
            }
        });
        input.addEventListener('focus', () => {
            input.select();
        });
    });

    botaoValidarCodigo.addEventListener('click', () => {
        validarCodigo();
    });
    const validarCodigo = async () => {
        const codigo =
            inputCodigo1.value +
            inputCodigo2.value +
            inputCodigo3.value +
            inputCodigo4.value +
            inputCodigo5.value +
            inputCodigo6.value;
        if (!/^[0-9]{6}$/.test(codigo)) {
            Alerta.notificacao('Seu código deve ter 6 números para continuar.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/login/senha-validar',
            {
                codigo,
                usuario,
            },
            'Erro ao validar seu código, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        hash = resposta.dado.hash;
        codigoValidado();
    };
    const codigoValidado = () => {
        blocoCodigo.classList.add('display_none');
        blocoSenha.classList.remove('display_none');
        botaoValidarCodigo.classList.add('display_none');
        botaoAlterarSenha.classList.remove('display_none');
    };

    const alterarSenha = async () => {
        if (inputSenhaNova.value == '') {
            Alerta.notificacao('Digite sua nova senha para continuar.', false);
            return;
        } else if (inputSenhaNova.value != inputSenhaRepetir.value) {
            Alerta.notificacao('Você deve repetir sua senha para continuar.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/login/senha-alterar',
            {
                cpf,
                senha: inputSenhaNova.value,
                usuario,
                hash,
            },
            'Erro ao atualizar sua senha, por favor, tente novamente.'
        );
        if (false === resposta) {
            Loading.hide();
            return;
        }
        window.location.replace(resposta.dado.link);
    };
    adicionarEventoEnter([inputSenhaNova, inputSenhaRepetir], alterarSenha);
    adicionarEvento('click', botaoAlterarSenha, alterarSenha);
});
