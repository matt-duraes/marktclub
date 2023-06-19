window.addEventListener('load', () => {
    const inputToken = document.getElementById('input_token');
    const inputMetodo = document.getElementById('input_metodo');
    const inputUri = document.getElementById('input_uri');
    const inputJson = document.getElementById('input_json');

    const blocoVazio = document.getElementById('bloco_vazio');
    const blocoRequest = document.getElementById('bloco_request');
    const blocoErro = document.getElementById('bloco_erro');
    const blocoLoading = document.getElementById('bloco_loading');
    const blocoResposta = document.getElementById('bloco_resposta');
    const blocoParametro = document.getElementById('bloco_parametro');
    const blocoBody = document.getElementById('bloco_body');
    const blocoHeader = document.getElementById('bloco_header');
    const blocoJson = document.getElementById('bloco_json');

    const botaoParametro = document.getElementById('botao_parametro');
    const botaoBody = document.getElementById('botao_body');
    const botaoHeader = document.getElementById('botao_header');
    const botaoJson = document.getElementById('botao_json');
    const botaoEnviar = document.getElementById('botao_enviar');

    /*
    |--------------------------------------------------------------------------
    | MANDAR REQUEST
    |--------------------------------------------------------------------------
    */
    botaoEnviar.addEventListener('click', async () => {
        const parPar = pegarParametro(blocoParametro);
        const parBody = pegarParametro(blocoBody);
        const parHeader = pegarParametro(blocoHeader);
        const parJson = inputJson.value.trim();

        const body = new FormData();
        body.append('acao', 'request');
        body.append('token', inputToken.value);
        body.append('metodo', inputMetodo.value);
        body.append('uri', inputUri.value);
        body.append('parametro', JSON.stringify(parPar));
        body.append('body', JSON.stringify(parBody));
        body.append('header', JSON.stringify(parHeader));
        body.append('json', JSON.stringify(parJson));

        const resposta = await fetch('__api', {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (e) {
            json = {};
        }
        console.log(json);
    });
    const pegarParametro = bloco => {
        const retorno = {};
        const lista = bloco.querySelectorAll('li');
        lista.forEach(item => {
            const check = item.querySelector('.bloco_checkbox input');
            const indice = item.querySelector('.chave').value;
            const valor = item.querySelector('.valor').value;
            if (!check.checked || indice == '') {
                return;
            }
            retorno[indice] = valor;
        });
        return retorno;
    };
    /*
    |--------------------------------------------------------------------------
    | CLIQUE NO MENU
    |--------------------------------------------------------------------------
    */
    const listaMenu = document.querySelectorAll('.bloco_menu .grupo');
    listaMenu.forEach(grupo => {
        const botao = grupo.querySelector('.nome');
        botao.addEventListener('click', e => {
            if (e.target.classList.contains('.nome') || e.target.closest('.nome')) {
                grupo.classList.toggle('fechado');
            }
        });
    });

    const listaRota = document.querySelectorAll('.bloco_menu .request');
    listaRota.forEach(botao => {
        botao.addEventListener('click', () => {
            abrirRota(botao);
        });
    });
    const abrirRota = botao => {
        limparBotoes();
        limparParametros();
        botaoParametro.classList.add('hover');
        blocoParametro.classList.remove('display_none');

        const id = botao.getAttribute('data-id');
        blocoVazio.classList.add('display_none');
        blocoRequest.classList.remove('display_none');
        blocoResposta.innerHTML = '';
        buscarDadoRequest(id);
    };
    const limparBotoes = () => {
        botaoParametro.classList.remove('hover');
        botaoBody.classList.remove('hover');
        botaoHeader.classList.remove('hover');
        botaoJson.classList.remove('hover');
    };
    const limparParametros = () => {
        blocoParametro.classList.add('display_none');
        blocoBody.classList.add('display_none');
        blocoHeader.classList.add('display_none');
        blocoJson.classList.add('display_none');
    };
    botaoParametro.addEventListener('click', () => {
        limparBotoes();
        limparParametros();
        botaoParametro.classList.add('hover');
        blocoParametro.classList.remove('display_none');
    });
    botaoBody.addEventListener('click', () => {
        limparBotoes();
        limparParametros();
        botaoBody.classList.add('hover');
        blocoBody.classList.remove('display_none');
    });
    botaoHeader.addEventListener('click', () => {
        limparBotoes();
        limparParametros();
        botaoHeader.classList.add('hover');
        blocoHeader.classList.remove('display_none');
    });
    botaoJson.addEventListener('click', () => {
        limparBotoes();
        limparParametros();
        botaoJson.classList.add('hover');
        blocoJson.classList.remove('display_none');
    });

    /*
    |--------------------------------------------------------------------------
    | REMOVE UMA LINHA
    |--------------------------------------------------------------------------
    */
    blocoParametro.addEventListener('click', e => {
        removerLinha(e);
    });
    blocoBody.addEventListener('click', e => {
        removerLinha(e);
    });
    blocoHeader.addEventListener('click', e => {
        removerLinha(e);
    });
    const removerLinha = e => {
        if (!e.target.classList.contains('deletar') && !e.target.closest('.deletar')) {
            return;
        }
        const linha = e.target.closest('li');
        linha.parentNode.removeChild(linha);
    };

    /*
    |--------------------------------------------------------------------------
    | BUSCA DADOS DO REQUEST SELECIONADO
    |--------------------------------------------------------------------------
    */
    const limparTudo = () => {
        blocoRequest.classList.add('display_none');
        blocoErro.classList.add('display_none');
        blocoBody.innerHTML = '';
        blocoHeader.innerHTML = '';
        blocoParametro.innerHTML = '';
        blocoResposta.innerHTML = '';

        formValue(inputMetodo, '');
        formValue(inputToken, '');
        inputUri.value = '';
        inputJson.value = '';
    };

    const buscarDadoRequest = async id => {
        limparTudo();
        blocoLoading.classList.remove('display_none');
        const body = new FormData();
        body.append('acao', 'buscar');
        body.append('id', id);
        const resposta = await fetch('__api', {
            method: 'POST',
            body,
        });
        let json;
        try {
            json = await resposta.json();
        } catch (e) {
            json = {};
        }
        blocoLoading.classList.add('display_none');
        if (json.uri == undefined) {
            Alerta.notificacao('Erro o carregar dados, por favor, tente novamente.', false);
            blocoErro.classList.remove('display_none');
            return;
        }
        blocoRequest.classList.remove('display_none');
        formValue(inputMetodo, json.metodo);
        formValue(inputToken, json.token);
        inputUri.value = json.uri;

        if (json.json != '') {
            inputJson.value = json.json;
        }
        montarParametro(blocoParametro, json.parametro);
        montarParametro(blocoBody, json.body);
        montarParametro(blocoHeader, json.header);
        if (json.body.length > 0) {
            limparBotoes();
            limparParametros();
            botaoBody.classList.add('hover');
            blocoBody.classList.remove('display_none');
        }
    };
    buscarDadoRequest('teste');
    const montarParametro = (bloco, lista) => {
        bloco.innerHTML = '';
        lista.forEach(item => {
            const checked = item[1] ? 'checked' : '';
            bloco.insertAdjacentHTML(
                'beforeend',
                `
                <li>
                    <div class="bloco_checkbox">
                        <input type="checkbox" ${checked}>
                        <span>
                            <svg height="10" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z"/></svg>
                        </span>
                    </div>
                    <input class="chave" type="text" value="${item[2]}" name="key" placeholder="chave">
                    <input class="valor" type="text" value="${item[3]}" name="value" placeholder="valor">
                    <i class="deletar"><svg height="17" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" x="0px" y="0px"><g data-name="Application, Delete"><path d="M13,37a4,4,0,0,0,4,4H31a4,4,0,0,0,4-4V16H13Zm2-19H33V37a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2Zm7,16H20V23h2Zm6,0H26V23h2Zm3.41-23-4-4H20.59l-4,4H9v2H39V11Zm-10-2h5.18l2,2H19.41Z"/></g></svg></i>
                </li>
                `
            );
        });
        adicionarNovoItemVazio(bloco);
    };
    const adicionarNovoItemVazio = bloco => {
        const itemNovo = bloco.querySelector('.novo');
        if (itemNovo) {
            itemNovo.classList.remove('novo');
            removeAcaoAntigoItemNovo(itemNovo);
        }
        bloco.insertAdjacentHTML(
            'beforeend',
            `
            <li class="novo">
                <div class="bloco_checkbox">
                    <input type="checkbox" checked>
                    <span><svg height="10" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z"/></svg></span>
                </div>
                <input class="chave" type="text" name="key" placeholder="chave">
                <input class="valor" type="text" name="value" placeholder="valor">
                <i class="deletar"><svg height="17" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" x="0px" y="0px"><g data-name="Application, Delete"><path d="M13,37a4,4,0,0,0,4,4H31a4,4,0,0,0,4-4V16H13Zm2-19H33V37a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2Zm7,16H20V23h2Zm6,0H26V23h2Zm3.41-23-4-4H20.59l-4,4H9v2H39V11Zm-10-2h5.18l2,2H19.41Z"/></g></svg></i>
            </li>
            `
        );
        adicionarAcaoItemNovo(bloco);
    };
    const removeAcaoAntigoItemNovo = item => {
        const lista = item.querySelectorAll('.chave, .valor');
        lista.forEach(input => {
            input.removeEventListener('keyup', adicionarEventoItemNovo);
        });
    };
    const adicionarAcaoItemNovo = bloco => {
        const itemNovo = bloco.querySelector('.novo');
        if (!itemNovo) {
            return;
        }
        const lista = itemNovo.querySelectorAll('.chave, .valor');
        lista.forEach(input => {
            input.addEventListener('keyup', adicionarEventoItemNovo);
        });
    };
    const adicionarEventoItemNovo = e => {
        const input = e.target;
        if (!input || input.value == '') {
            return;
        }
        const bloco = input.closest('.bloco_parametro');
        adicionarNovoItemVazio(bloco);
    };
});
