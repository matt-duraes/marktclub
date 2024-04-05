window.addEventListener('load', () => {
    const APP = document.getElementById('APP').value;

    const form = document.querySelector('#bloco_app_add');
    const blocoGeral = document.querySelector('#bloco_app_add');
    const botaoSalvar = blocoGeral.querySelector('#botao_salvar_geral');

    /*
    |--------------------------------------------------------------------------
    | SALVAR DADO
    |--------------------------------------------------------------------------
    */
    if (botaoSalvar) {
        const blocoIdInicial = form.querySelector('input[name=id]');
        const idInicial = blocoIdInicial ? blocoIdInicial.value : null;
        if (blocoIdInicial) {
            blocoIdInicial.parentNode.removeChild(blocoIdInicial);
        }

        const hashValidacao = document.getElementById('hash_id_hash').value;
        const listaInput = form.querySelectorAll('input[name]');
        listaInput.forEach(input => {
            input.addEventListener('keydown', e => {
                if (e.key == 'Enter') {
                    e.preventDefault();
                    salvarDadosDoFormulario();
                }
            });
        });

        botaoSalvar.addEventListener('click', e => {
            e.preventDefault();
            salvarDadosDoFormulario();
        });

        const salvarDadosDoFormulario = async () => {
            if (botaoSalvar.classList.contains('aguarde')) {
                return;
            }
            botaoSalvar.classList.add('aguarde');

            const body = pegarDadosDosInputs();

            let editar = false;
            if (idInicial != null) {
                body.append('id', idInicial);
                editar = true;
            }
            body.append('form_system_hash', hashValidacao);
            body.append('form_system_validacao', '');

            const resposta = await fetch(LINK + '/app/salvar/' + APP, {
                method: 'POST',
                body,
            });

            const json = await respostaJson(
                resposta,
                'Ocorreu um erro ao tentar salvar os dados, por favor, tente novamente.'
            );
            botaoSalvar.classList.remove('aguarde');

            if (false === json) {
                return;
            }

            resetarCampoPassword();

            if (!editar) {
                const resposta = await Alerta.mensagem('Dados salvos!', 'Seus dados foram salvos com sucesso!', 'ok');
                if (resposta) {
                    window.location.reload();
                }
                return;
            }
            Alerta.notificacao('Seus dados foram salvos com sucesso!', true);
        };

        const resetarCampoPassword = () => {
            const blocoSenha = form.querySelectorAll('.bloco_senha');
            let blocoInput;
            blocoSenha.forEach(bloco => {
                bloco.classList.remove('mostrar_senha');
                blocoInput = bloco.querySelectorAll('input');
                blocoInput.forEach(input => {
                    input.value = '';
                    input.setAttribute('type', 'password');
                });
            });
        };
    }

    const pegarDadosDosInputs = retorno => {
        const listaInput = form.querySelectorAll('input[name], textarea[name], .fw_form_tag, .fw_form_indice_valor');
        let body;
        if (retorno == 'array') {
            body = [];
        } else {
            body = new FormData();
        }
        let tipo, isArray, name, mascara, value, lista;
        let ArrayLista = [];
        listaInput.forEach(input => {
            if (input.classList.contains('fw_form_tag')) {
                name = input.getAttribute('data-name');
                nameArray = name + '[]';
                lista = input.querySelectorAll('.fw_form_tag_item');
                if (lista.length == 0) {
                    if (retorno != 'array') {
                        body.append(name, '');
                    }
                    return;
                }
                lista.forEach(item => {
                    value = item.querySelector('span').innerText;
                    if (retorno == 'array') {
                        body.push(value);
                    } else {
                        body.append(nameArray, value);
                    }
                });
                return;
            } else if (input.classList.contains('fw_form_indice_valor')) {
                name = input.getAttribute('data-name');
                lista = input.querySelectorAll('.fw_form_indice_valor_lista .fw_form_indice_valor_linha');
                if (lista.length == 0) {
                    if (retorno != 'array') {
                        body.append(name, '');
                    }
                    return;
                }
                let indiceValor = {};
                lista.forEach((item, i) => {
                    indiceValor[i] = [
                        item.querySelector('.fw_form_indice_valor_indice').innerText,
                        item.querySelector('.fw_form_indice_valor_valor').innerText,
                    ];
                });
                indiceValor = JSON.stringify(indiceValor);
                if (retorno == 'array') {
                    body.push(indiceValor);
                } else {
                    body.append(name, indiceValor);
                }
                return;
            }

            tipo = input.getAttribute('type');
            name = input.getAttribute('name');
            mascara = input.getAttribute('data-mascara');
            isArray = /\[\]$/.test(name);

            if (name == undefined || /^\_/.test(name)) {
                return;
            } else if (mascara == 'dinheiro') {
                value = input.value.replace(/\./g, '').replace(',', '.');
            } else if (tipo == 'checkbox' && isArray && input.checked) {
                value = input.value;
            } else if (tipo == 'checkbox' && isArray && !input.checked) {
                if (
                    ((retorno == 'array' && body[name] == undefined) || (retorno == undefined && !body.has(name))) &&
                    !ArrayLista.includes(name)
                ) {
                    ArrayLista.push(name);
                }
                return;
            } else if (tipo == 'checkbox') {
                value = input.checked ? 1 : 0;
            } else {
                value = input.value;
            }
            if (input.classList.contains('input_url') && value != '') {
                value = 'https://' + value;
            }

            if (retorno == 'array') {
                body.push(value);
            } else {
                body.append(name, value);
            }
        });
        ArrayLista.forEach(item => {
            if (retorno == 'array' && body[item] == undefined) {
                body.push([]);
            } else if (retorno == undefined && !body.has(item)) {
                body.append(item.replace(/\[\]$/, ''), []);
            }
        });
        const arquivoLista = $$('.fw_form_arquivo_lista');
        if (arquivoLista && retorno == undefined) {
            for (const bloco of arquivoLista) {
                const arquivoName = bloco.attr('data-name');
                if (!body.has(arquivoName + '[]')) {
                    body.append(arquivoName, '');
                }
            }
        }
        return body;
    };
});
