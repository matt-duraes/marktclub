window.addEventListener('load', () => {
    const APP = document.getElementById('APP').value;
    const LINK = document.getElementById('LINK').value;
    const linkVoltar = document.getElementById('LINK_VOLTAR').value;

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

            const response = await fetch(LINK + '/app/salvar/' + APP, {
                method: 'POST',
                body,
            });

            let json;
            try {
                json = await response.json();
            } catch (error) {
                json = {};
            }

            botaoSalvar.classList.remove('aguarde');
            if (response.status != 201 && response.status != 204) {
                Alerta.notificacao(
                    json.erro.mensagem != undefined
                        ? json.erro.mensagem
                        : 'Ocorreu um erro ao salvar seus dados, por favor, tente novamente.',
                    false
                );
                return;
            }

            resetarCampoPassword();

            // if (json.historico === true) {
            //     const bodyHistorico = new FormData();
            //     bodyHistorico.append('location', editar ? 0 : 1);
            //     const Historico = new Pagina(
            //         'Histórico',
            //         LINK + '/historico',
            //         { method: 'POST', body: bodyHistorico },
            //         false,
            //         false,
            //         () => document.querySelector('#input_historico_atualizar_texto').focus()
            //     );
            //     Historico.abrir();
            //     return;
            // } else if (!editar) {
            //     Alerta.mensagem('Dados salvos!', 'Seus dados foram salvos com sucesso!', {
            //         icone: 'ok',
            //     }).then(() => window.location.assign(linkVoltar));
            //     return;
            // }
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
        const listaInput = form.querySelectorAll('input[name], textarea[name], .fw_form_tag');
        let body;
        if (retorno == 'array') {
            body = [];
        } else {
            body = new FormData();
        }
        let tipo, isArray, name, value, inputFile, inputReal, lista;
        let ArrayLista = [];
        listaInput.forEach(input => {
            if (input.classList.contains('fw_form_tag')) {
                name = input.getAttribute('data-name') + '[]';
                lista = input.querySelectorAll('.fw_form_tag_item');
                lista.forEach(item => {
                    value = item.querySelector('span').innerText;
                    if (retorno == 'array') {
                        body.push(value);
                    } else {
                        body.append(name, value);
                    }
                });
                return;
            }

            tipo = input.getAttribute('type');
            name = input.getAttribute('name');
            isArray = /\[\]$/.test(name);

            if (name == undefined || /^\_/.test(name)) {
                return;
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
        return body;
    };
});
