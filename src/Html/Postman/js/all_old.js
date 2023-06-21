window.addEventListener('load', () => {
    let requisicaoId;

    const body = document.querySelector('body');

    const inputToken = document.querySelector('.input_token');
    const inputMetodo = document.querySelector('.input_metodo');
    const inputUri = document.querySelector('.input_uri');
    const inputJson = document.querySelector('.input_json');

    const blocoAba = document.getElementById('bloco_aba');
    const blocoModelo = document.getElementById('bloco_modelo');
    const blocoVazio = document.getElementById('bloco_vazio');
    const blocoRequest = document.getElementById('bloco_request');
    const blocoErro = document.getElementById('bloco_erro');

    const blocoResposta = document.querySelector('.bloco_resposta_html');
    const blocoCodigoHtml = document.querySelector('.bloco_codigo_html');
    const blocoParametro = document.querySelector('.bloco_parametro');
    const blocoBody = document.querySelector('.bloco_body');
    const blocoHeader = document.querySelector('.bloco_header');
    const blocoJson = document.querySelector('.bloco_json');

    const botaoNovaAba = document.getElementById('botao_nova_aba');

    const botaoParametro = document.querySelector('.botao_parametro');
    const botaoBody = document.querySelector('.botao_body');
    const botaoHeader = document.querySelector('.botao_header');
    const botaoJson = document.querySelector('.botao_json');
    const botaoEnviar = document.querySelector('.botao_enviar');
    const botaoSalvar = document.querySelector('.botao_salvar');

    body.addEventListener('input', e => {
        if (e.target.classList.contains('monitorar_salvar') || e.target.closest('.monitorar_salvar')) {
            botaoSalvar.classList.remove('display_none');
        }
    });

    const iconeFechar = height => {
        return `<svg height="${height}" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"  xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg>`;
    };

    /*
    |--------------------------------------------------------------------------
    | ABA
    |--------------------------------------------------------------------------
    */
    const fecharAba = aba => {
        const ativo = aba.classList.contains('ativa');
        aba.parentNode.removeChild(aba);

        if (ativo) {
            abrirPrimeiraAba();
        }
    };
    const abrirPrimeiraAba = () => {
        const lista = blocoAba.querySelectorAll('.aba');
        if (lista.length == 0) {
            blocoVazio.classList.remove('display_none');
            blocoRequest.classList.add('display_none');
            return;
        }
        lista[0].classList.add('ativa');
    };
    blocoAba.addEventListener('click', e => {
        if (e.target.classList.contains('fechar') || e.target.closest('.fechar')) {
            fecharAba(e.target.closest('.aba'));
            return;
        }
    });

    const adicionarNovaAba = (id, temp, metodo, uri) => {
        const abaAtiva = blocoAba.querySelector('.aba.ativa');
        const abaExiste = blocoAba.querySelector('.aba[data-id="' + id + '"]');
        if (abaAtiva && abaExiste && abaAtiva == abaExiste) {
            abaExiste.scrollIntoView();
            return;
        } else if (abaAtiva) {
            abaAtiva.classList.remove('ativa');
        }
        abrirRota(id);

        if (!abaExiste) {
            const dataTemp = temp ? 'data-temp="' + id + '"' : '';
            blocoAba.insertAdjacentHTML(
                'beforeend',
                `
                <div class="aba ativa" data-id="${id}" ${dataTemp}>
                    <div class="metodo">${metodo}</div>
                    <div class="uri">${uri}</div>
                    <div class="fechar">${iconeFechar(8)}</div>
                </div>
                `
            );
            blocoAba.scrollLeft = blocoAba.scrollWidth;
        } else {
            abaExiste.classList.add('ativa');
            abaExiste.scrollIntoView();
        }
    };
    botaoNovaAba.addEventListener('click', () => {
        const id = 'id_' + Math.floor(Date.now() * Math.random()).toString(36);
        adicionarNovaAba(id, true, 'GET', 'Temporario');
    });
    /*
    |--------------------------------------------------------------------------
    | SALVAR REQUEST
    |--------------------------------------------------------------------------
    */
    const salvarRequisicao = async () => {
        if (botaoSalvar.classList.contains('display_none') || botaoSalvar.classList.contains('carregando')) {
            return;
        }
        botaoSalvar.classList.add('carregando');
        const json = await mandarRequisicao('salvar');
        if (json.status || '' != 'sucesso') {
            Alerta.notificacao(json.erro.mensagem || 'Erro ao salvar requisicão.', false);
        }
        botaoSalvar.classList.remove('carregando');
    };
    body.addEventListener('keydown', e => {
        if ((e.ctrlKey || e.metaKey) && e.key == 's') {
            e.preventDefault();
            salvarRequisicao();
        }
    });
    botaoSalvar.addEventListener('click', () => {
        salvarRequisicao();
    });
    /*
    |--------------------------------------------------------------------------
    | MANDAR REQUEST
    |--------------------------------------------------------------------------
    */
    botaoEnviar.addEventListener('click', async () => {
        const json = await mandarRequisicao('request');
        let retorno;
        try {
            retorno = JSON.parse(json.retorno);
            retorno = JSON.stringify(retorno, null, 2);
        } catch (e) {
            retorno = json.retorno.replace(/\</g, '&lt;').replace(/\>/g, '&gt;');
        }
        blocoResposta.innerHTML = retorno;
        const erro = json.codigo_html >= 400 ? 'erro' : 'sucesso';
        blocoCodigoHtml.classList.add(erro);
        blocoCodigoHtml.innerText = json.codigo_html;
    });
    const mandarRequisicao = async acao => {
        const parPar = acao == 'salvar' ? pegarLinha(blocoParametro) : pegarParametro(blocoParametro);
        const parBody = acao == 'salvar' ? pegarLinha(blocoBody) : pegarParametro(blocoBody);
        const parHeader = acao == 'salvar' ? pegarLinha(blocoHeader) : pegarParametro(blocoHeader);
        const parJson = inputJson.value.trim();
        blocoCodigoHtml.classList.remove('erro');
        blocoCodigoHtml.classList.remove('sucesso');
        blocoCodigoHtml.innerText = '';

        const body = new FormData();
        body.append('acao', acao);
        body.append('id', requisicaoId);
        body.append('token', inputToken.value);
        body.append('metodo', inputMetodo.value);
        body.append('uri', inputUri.value);
        body.append('parametro', JSON.stringify(parPar));
        body.append('body', JSON.stringify(parBody));
        body.append('header', JSON.stringify(parHeader));
        body.append('json', JSON.stringify(parJson));

        const resposta = await fetch('__postman', {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (e) {
            json = {};
        }
        return json;
    };
    const pegarParametro = bloco => {
        const retorno = {};
        const lista = bloco.querySelectorAll('li');
        lista.forEach(item => {
            const check = item.querySelector('.bloco_checkbox input');
            const indice = item.querySelector('.chave').value.trim();
            const valor = item.querySelector('.valor').value.trim();
            if (!check.checked || indice == '') {
                return;
            }
            retorno[indice] = valor;
        });
        return retorno;
    };
    const pegarLinha = bloco => {
        const retorno = [];
        const lista = bloco.querySelectorAll('li');
        lista.forEach(item => {
            const check = item.querySelector('.bloco_checkbox input');
            const indice = item.querySelector('.chave').value.trim();
            const valor = item.querySelector('.valor').value.trim();
            if (indice == '') {
                return;
            }
            retorno.push(['texto', check.checked, indice, valor]);
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
            const id = botao.getAttribute('data-id');
            const metodo = botao.getAttribute('data-metodo');
            const uri = botao.getAttribute('data-uri');
            adicionarNovaAba(id, false, metodo, uri);
        });
    });
    const abrirRota = id => {
        const blocoExiste = document.querySelector('#bloco_request_' + id);
        if (blocoExiste) {
            console.log('existe');
            return;
        }
        const blocoClone = blocoModelo.cloneNode(true);
        blocoClone.classList.remove('display_none');
        blocoClone.setAttribute('id', 'bloco_request_' + id);
        blocoRequest.appendChild(blocoClone);
        blocoRequest.classList.remove('display_none');
        blocoVazio.classList.add('display_none');
    };
    const abrirRotaOld = id => {
        limparBotoes();
        limparParametros();
        botaoParametro.classList.add('hover');
        blocoParametro.classList.remove('display_none');

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

        inputMetodo.value = '';
        inputToken.value = '';
        inputUri.value = '';
        inputJson.value = '';
    };

    const buscarDadoRequest = async id => {
        requisicaoId = id;
        limparTudo();
        const body = new FormData();
        body.append('acao', 'buscar');
        body.append('id', id);
        const resposta = await fetch('__postman', {
            method: 'POST',
            body,
        });
        let json;
        try {
            json = await resposta.json();
        } catch (e) {
            json = {};
        }
        if (json.uri == undefined) {
            Alerta.notificacao('Erro o carregar dados, por favor, tente novamente.', false);
            blocoErro.classList.remove('display_none');
            return;
        }
        blocoRequest.classList.remove('display_none');
        inputMetodo.value = json.metodo;
        inputToken.value = json.token;
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

    const montarParametro = (bloco, lista) => {
        bloco.innerHTML = '';
        lista.forEach(item => {
            const checked = item[1] ? 'checked' : '';
            bloco.insertAdjacentHTML(
                'beforeend',
                `
                <li>
                    <div class="bloco_checkbox">
                        <input type="checkbox" class="monitorar_salvar" ${checked}>
                        <span>
                            <svg height="10" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z"/></svg>
                        </span>
                    </div>
                    <input class="chave monitorar_salvar" type="text" value="${item[2]}" name="key" placeholder="chave">
                    <input class="valor monitorar_salvar" type="text" value="${item[3]}" name="value" placeholder="valor">
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
                    <input type="checkbox" class="monitorar_salvar" checked>
                    <span><svg height="10" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M91,12.2c-4.2-3-10-1.9-13,2.3L42.1,65.8L20.9,44.6c-3.6-3.6-9.6-3.6-13.2,0c-3.6,3.6-3.6,9.6,0,13.2l29,29  c1.8,1.8,4.2,2.7,6.6,2.7c0,0,0,0,0.1,0c0,0,0.7,0,0.9,0c2.6-0.2,5.1-1.6,6.8-3.9l42.3-60.4C96.3,20.9,95.2,15.1,91,12.2z"/></svg></span>
                </div>
                <input class="chave monitorar_salvar" type="text" name="key" placeholder="chave">
                <input class="valor monitorar_salvar" type="text" name="value" placeholder="valor">
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
