$ = document.querySelector.bind(document);
$$ = document.querySelectorAll.bind(document);
log = console.log.bind(console);
const body = document.querySelector('body');

post = async (link, body, erro, opcao) => {
    return await fazerRequisicao(link, 'POST', body, erro, opcao);
};
fazerRequisicao = async (link, metodo, body, erro, opcao) => {
    if (opcao == undefined || !opcao instanceof Object) {
        opcao = {};
    }

    if (body != undefined && body instanceof Object && metodo == 'POST') {
        const dado = new FormData();
        Object.entries(body).forEach(valores => {
            const [indice, valor] = valores;
            dado.append(indice, valor);
        });
        opcao.body = dado;
    }
    opcao.method = metodo;

    const resposta = await fetch(link, opcao);
    const status = resposta.status;
    if (status == 204) {
        return true;
    }
    const mensagemErro = erro == undefined ? 'Erro a fazer a requisição, por favor, tente novamente.' : erro;
    let json;
    try {
        json = await resposta.json();
    } catch (e) {
        Alerta.notificacao(mensagemErro, false);
        return false;
    }
    if (!(json instanceof Object) || json.status == undefined) {
        Alerta.notificacao(mensagemErro, false);
        return false;
    } else if (json.status != 'sucesso') {
        Alerta.notificacao(
            json.erro != undefined && json.erro.mensagem != undefined ? json.erro.mensagem : mensagemErro,
            false
        );
        return false;
    }
    return json;
};
pegarElementoModelo = id => {
    const bloco = document.getElementById(id);
    bloco.removeAttribute('id');
    return bloco;
};

window.addEventListener('load', () => {
    let variavelLocal = {};
    let requisicaoId;

    const blocoAbaModelo = pegarElementoModelo('bloco_aba_modelo');
    const blocoAbaLista = document.getElementById('bloco_aba_lista');
    const botaoNovaAba = document.getElementById('botao_nova_aba');

    const blocoRequestModelo = pegarElementoModelo('bloco_request_modelo');
    const blocoRequestLista = document.getElementById('bloco_request_lista');

    const blocoLinhaModelo = pegarElementoModelo('bloco_linha_modelo');

    const blocoVazio = document.getElementById('bloco_vazio');

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    body.addEventListener('input', e => {
        if (e.target.classList.contains('monitorar_salvar') || e.target.closest('.monitorar_salvar')) {
            mostrarBotaoSalvar(e.target.closest('.bloco_request'));
        }
    });
    const mostrarBotaoSalvar = bloco => {
        if (bloco.getAttribute('data-salvar') != 'sim') {
            return;
        }
        const botao = bloco.querySelector('.botao_salvar');
        botao.classList.remove('display_none');
    };

    /*
    |--------------------------------------------------------------------------
    | ABA
    |--------------------------------------------------------------------------
    */
    const fecharAba = aba => {
        const ativo = aba.classList.contains('ativa');
        const id = aba.getAttribute('data-id');
        aba.parentNode.removeChild(aba);
        if (ativo) {
            abrirPrimeiraAba();
        }
        fecharRota(id);
    };
    const abrirPrimeiraAba = () => {
        const lista = blocoAbaLista.querySelectorAll('.aba');
        if (lista.length == 0) {
            blocoVazio.classList.remove('display_none');
            blocoRequestLista.classList.add('display_none');
            return;
        }
        const aba = lista[0];
        aba.classList.add('ativa');
        const bloco = document.querySelector('#bloco_request_' + aba.getAttribute('data-id'));
        bloco.classList.remove('display_none');
    };
    blocoAbaLista.addEventListener('click', e => {
        if (e.target.classList.contains('fechar') || e.target.closest('.fechar')) {
            fecharAba(e.target.closest('.aba'));
            return;
        }
        const aba = e.target.closest('.aba');
        const id = aba.getAttribute('data-id');
        adicionarNovaAba(id);
    });

    const adicionarNovaAba = (id, metodo, uri) => {
        requisicaoId = id;
        const abaAtiva = blocoAbaLista.querySelector('.aba.ativa');
        const abaExiste = blocoAbaLista.querySelector('#' + id + '_aba');
        if (abaAtiva && abaExiste && abaAtiva == abaExiste) {
            abaExiste.scrollIntoView();
            return;
        } else if (abaAtiva) {
            abaAtiva.classList.remove('ativa');
        }
        abrirRota(id);
        if (abaExiste) {
            abaExiste.classList.add('ativa');
            abaExiste.scrollIntoView();
            return;
        }

        const clone = blocoAbaModelo.cloneNode(true);
        clone.setAttribute('id', id + '_aba');
        clone.setAttribute('data-id', id);
        clone.querySelector('.metodo').innerText = metodo;
        clone.querySelector('.uri').innerText = uri;
        blocoAbaLista.appendChild(clone);
        blocoAbaLista.scrollLeft = blocoAbaLista.scrollWidth;
    };
    botaoNovaAba.addEventListener('click', () => {
        const id = 'id_' + Math.floor(Date.now() * Math.random()).toString(36);
        adicionarNovaAba(id, 'GET', 'Temporario');
    });

    /*
    |--------------------------------------------------------------------------
    | ENVIAR REQUEST
    |--------------------------------------------------------------------------
    */
    const enviarRequest = async e => {
        const bloco = e.target.closest('.bloco_request');
        const resposta = await mandarRequisicao(bloco, 'request');

        let retorno;
        try {
            retorno = JSON.parse(resposta.retorno);
            retorno = JSON.stringify(retorno, null, 2);
        } catch (e) {
            retorno = resposta.retorno.replace(/\</g, '&lt;').replace(/\>/g, '&gt;');
        }
        const blocoResposta = bloco.querySelector('.bloco_resposta_html');
        const blocoStatus = bloco.querySelector('.bloco_codigo_html');
        blocoResposta.innerHTML = retorno;
        const erro = resposta.codigo_html >= 400 ? 'erro' : 'sucesso';
        blocoStatus.classList.add(erro);
        blocoStatus.innerText = resposta.codigo_html;
    };
    const mandarRequisicao = async (bloco, acao) => {
        const blocoParametro = bloco.querySelector('.bloco_parametro_parametro');
        const blocoBody = bloco.querySelector('.bloco_parametro_body');
        const blocoHeader = bloco.querySelector('.bloco_parametro_header');
        const blocoVariavel = bloco.querySelector('.bloco_parametro_variavel');
        const parPar = acao == 'salvar' ? pegarLinha(blocoParametro) : pegarParametro(blocoParametro);
        const parBody = acao == 'salvar' ? pegarLinha(blocoBody) : pegarParametro(blocoBody);
        const parHeader = acao == 'salvar' ? pegarLinha(blocoHeader) : pegarParametro(blocoHeader);
        const parVariavel = acao == 'salvar' ? pegarLinha(blocoVariavel) : variavelLocal;
        const inputJson = bloco.querySelector('.input_json');
        const inputToken = bloco.querySelector('.input_token');
        const inputMetodo = bloco.querySelector('.input_metodo');
        const inputUri = bloco.querySelector('.input_uri');

        const body = new FormData();
        body.append('acao', acao);
        body.append('id', requisicaoId);
        body.append('token', inputToken.value);
        body.append('metodo', inputMetodo.value);
        body.append('uri', inputUri.value);
        body.append('parametro', JSON.stringify(parPar));
        body.append('body', JSON.stringify(parBody));
        body.append('header', JSON.stringify(parHeader));
        body.append('variavel', JSON.stringify(parVariavel));
        body.append('json', JSON.stringify(inputJson.value.trim()));

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
    | MANIPULAR ROTA
    |--------------------------------------------------------------------------
    */
    const fecharRota = id => {
        const bloco = document.querySelector('#' + id + '_requisicao');
        if (!bloco) {
            return;
        }
        bloco.parentNode.removeChild(bloco);
    };
    const abrirRota = id => {
        const blocoExiste = blocoRequestLista.querySelector('#' + id + '_requisicao');
        const blocoAtivo = blocoRequestLista.querySelector('.bloco_request.ativo');

        if (blocoExiste && blocoExiste == blocoAtivo) {
            return;
        } else if (blocoExiste) {
            blocoExiste.classList.remove('display_none');
            blocoExiste.classList.add('ativo');
        }
        if (blocoAtivo) {
            blocoAtivo.classList.add('display_none');
            blocoAtivo.classList.remove('ativo');
        }

        if (blocoExiste) {
            return;
        }

        const clone = blocoRequestModelo.cloneNode(true);
        clone.setAttribute('id', id + '_requisicao');
        blocoRequestLista.appendChild(clone);
        blocoRequestLista.classList.remove('display_none');
        blocoVazio.classList.add('display_none');

        const existeMenu = document.querySelector('.bloco_menu .request[data-id="' + id + '"]');
        clone.setAttribute('data-salvar', existeMenu ? 'sim' : 'nao');
        if (existeMenu) {
            clone.querySelector('.input_metodo').value = existeMenu.getAttribute('data-metodo');
            clone.querySelector('.bloco_metodo').classList.add('inativo');
        }

        buscarDadoRequest(id, clone);
    };

    const buscarDadoRequest = async (id, bloco) => {
        bloco.classList.add('loading');

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
            fecharAba(document.querySelector('#' + id + '_aba'));
            return;
        }
        const inputToken = bloco.querySelector('.input_token');
        const blocoMetodo = bloco.querySelector('.bloco_metodo');
        const inputMetodo = bloco.querySelector('.input_metodo');
        const inputUri = bloco.querySelector('.input_uri');
        const inputDescricao = bloco.querySelector('.input_descricao');
        const inputRequisicao = bloco.querySelector('.input_requisicao');
        const inputResposta = bloco.querySelector('.input_resposta');

        inputToken.value = json.token;
        if (!blocoMetodo.classList.contains('inativo')) {
            inputMetodo.value = json.metodo;
        }
        inputUri.value = json.uri;
        inputDescricao.value = json.doc.descricao;
        inputRequisicao.value = json.doc.requisicao;
        inputResposta.value = json.doc.resposta;

        const blocoParametro = bloco.querySelector('.bloco_parametro_parametro');
        const blocoBody = bloco.querySelector('.bloco_parametro_body');
        const blocoHeader = bloco.querySelector('.bloco_parametro_header');
        const blocoVar = bloco.querySelector('.bloco_parametro_variavel');
        montarParametro(blocoParametro, json.parametro);
        montarParametro(blocoBody, json.body);
        montarParametro(blocoHeader, json.header);
        montarParametro(blocoVar, json.variavel);
        adicionarEventosBloco(bloco);
    };

    const abrirNovoParametro = e => {
        const bloco = e.target.closest('.parametro');
        const parametro = bloco.querySelector('.' + e.target.getAttribute('data-id'));
        const botaoAtivo = bloco.querySelector('.item.ativo');
        const blocoAtivo = bloco.querySelector('.bloco_scroll.ativo');
        if (botaoAtivo) {
            botaoAtivo.classList.remove('ativo');
        }
        if (blocoAtivo) {
            blocoAtivo.classList.remove('ativo');
        }
        e.target.classList.add('ativo');
        parametro.classList.add('ativo');
    };
    const adicionarEventosBloco = bloco => {
        const botaoParametro = bloco.querySelector('.botao_parametro');
        const botaoBody = bloco.querySelector('.botao_body');
        const botaoHeader = bloco.querySelector('.botao_header');
        const botaoJson = bloco.querySelector('.botao_json');
        const botaoVariavel = bloco.querySelector('.botao_variavel');
        const botaoDocumentacao = bloco.querySelector('.botao_documentacao');
        const botaoSalvar = bloco.querySelector('.botao_salvar');
        const botaoEnviar = bloco.querySelector('.botao_enviar');

        botaoParametro.addEventListener('click', abrirNovoParametro);
        botaoBody.addEventListener('click', abrirNovoParametro);
        botaoHeader.addEventListener('click', abrirNovoParametro);
        botaoJson.addEventListener('click', abrirNovoParametro);
        botaoVariavel.addEventListener('click', abrirNovoParametro);
        botaoDocumentacao.addEventListener('click', abrirNovoParametro);
        botaoEnviar.addEventListener('click', enviarRequest);
    };

    const montarParametro = (bloco, lista) => {
        bloco.innerHTML = '';
        lista.forEach(item => {
            adicionarNovaLinha(bloco, lista[1], lista[2], lista[3]);
        });
        adicionarNovaLinha(bloco, false, '', '');
    };
    const monitorarUltimaLinha = e => {
        if (e.target.value == '') {
            return;
        }
        const bloco = e.target.closest('.bloco_scroll');
        const linha = e.target.closest('li');
        const inputChave = linha.querySelector('.chave');
        const inputValor = linha.querySelector('.valor');
        const inputCheck = linha.querySelector('.bloco_checkbox input');
        const blocoCheck = linha.querySelector('.bloco_checkbox span');
        const botaoDeletar = linha.querySelector('.deletar');

        blocoCheck.classList.remove('display_none');
        inputCheck.disabled = false;
        inputCheck.checked = true;
        botaoDeletar.classList.remove('display_none');
        inputChave.removeEventListener('keyup', monitorarUltimaLinha);
        inputValor.removeEventListener('keyup', monitorarUltimaLinha);
        adicionarNovaLinha(bloco, false, '', '');
    };
    const deletarLinha = e => {
        const linha = e.target.closest('li');
        const botaoDeletar = linha.querySelector('.deletar');
        botaoDeletar.removeEventListener('click', deletarLinha);
        linha.parentNode.removeChild(linha);
    };
    const adicionarNovaLinha = (bloco, check, chave, valor) => {
        const clone = blocoLinhaModelo.cloneNode(true);
        const botaoDeletar = clone.querySelector('.deletar');
        const inputChave = clone.querySelector('.chave');
        const inputValor = clone.querySelector('.valor');
        const inputCheck = clone.querySelector('.check');
        const blocoCheck = clone.querySelector('.bloco_checkbox span');

        inputCheck.checked = check;
        inputChave.innerText = chave;
        inputValor.innerText = valor;
        bloco.appendChild(clone);
        botaoDeletar.addEventListener('click', deletarLinha);

        if (chave == '') {
            blocoCheck.classList.add('display_none');
            botaoDeletar.classList.add('display_none');
            inputCheck.disabled = true;
            inputChave.addEventListener('keyup', monitorarUltimaLinha);
            inputValor.addEventListener('keyup', monitorarUltimaLinha);
        }
    };
});
