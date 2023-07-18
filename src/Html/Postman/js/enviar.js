const salvarRequisicao = async e => {
    const bloco = e.target.closest('.bloco_requisicao');
    const resposta = await mandarRequisicao(bloco, 'salvar');
    if (false == resposta) {
        return;
    }
    const botaoSalvar = bloco.querySelector('.botao_salvar');
    botaoSalvar.classList.add('display_none');
};
const enviarRequisicao = async e => {
    const bloco = e.target.closest('.bloco_requisicao');
    if (bloco.classList.contains('loading')) {
        return;
    }
    bloco.classList.add('loading');

    const blocoRespostaJson = bloco.querySelector('.bloco_resposta_json');
    const blocoRespostaBody = bloco.querySelector('.bloco_resposta_body');
    const blocoRespostaHtml = bloco.querySelector('.bloco_resposta_html');
    const blocoRespostaRequisicao = bloco.querySelector('.bloco_resposta_requisicao');
    const blocoStatusHtml = bloco.querySelector('.bloco_codigo_html');
    const blocoBotao = bloco.querySelector('.bloco_resposta_botao');
    const blocoBotaoAtivo = blocoBotao.querySelector('.ativo');
    const blocoRespostaAtivo = bloco.querySelector('ativo');
    const botaoHtml = blocoBotao.querySelector('.botao_resposta_html');
    const botaoJson = blocoBotao.querySelector('.botao_resposta_json');

    if (blocoBotaoAtivo) {
        blocoBotaoAtivo.classList.remove('ativo');
    }
    if (blocoRespostaAtivo) {
        blocoRespostaAtivo.classList.remove('ativo');
    }

    blocoRespostaJson.innerHTML = '';
    blocoRespostaBody.innerHTML = '';
    blocoRespostaRequisicao.innerHTML = '';
    blocoRespostaHtml.removeAttribute('srcdoc');
    blocoBotao.classList.add('display_none');
    blocoStatusHtml.classList.remove('erro');
    blocoStatusHtml.classList.remove('sucesso');
    blocoStatusHtml.innerText = '';
    blocoRespostaJson.classList.remove('ativo');
    blocoRespostaBody.classList.remove('ativo');
    blocoRespostaHtml.classList.remove('ativo');
    blocoRespostaRequisicao.classList.remove('ativo');

    const resposta = await mandarRequisicao(bloco, 'requisicao');
    bloco.classList.remove('loading');
    if (false == resposta) {
        return;
    }
    let json;
    try {
        json = JSON.parse(resposta.dado.retorno);
        json = JSON.stringify(json, null, 2);
    } catch (e) {
        json = '';
    }
    const body = resposta.dado.retorno != '' ? resposta.dado.retorno.replace(/\</g, '&lt;').replace(/\>/g, '&gt;') : '';
    blocoBotao.classList.remove('display_none');
    if (json != '') {
        botaoJson.classList.add('ativo');
        blocoRespostaJson.classList.add('ativo');
    } else {
        botaoHtml.classList.add('ativo');
        blocoRespostaHtml.classList.add('ativo');
        json = 'Resposta não é um json';
    }
    let requisicao = resposta.dado.requisicao || '';
    if (requisicao != '') {
        requisicao = JSON.stringify(requisicao, null, 2);
    }
    blocoRespostaBody.innerHTML = body;
    blocoRespostaJson.innerHTML = json;
    blocoRespostaRequisicao.innerHTML = requisicao;
    blocoRespostaHtml.setAttribute('srcdoc', pegarHtmlIframe(resposta.dado.retorno));
    const erro = resposta.dado.codigo_html >= 400 ? 'erro' : 'sucesso';
    blocoStatusHtml.classList.add(erro);
    blocoStatusHtml.innerText = resposta.dado.codigo_html;
    criarVariaveis(bloco, resposta.dado.retorno);
};
const criarVariaveis = (bloco, resposta) => {
    let json;
    try {
        json = JSON.parse(resposta);
    } catch (e) {
        json = false;
    }
    if (false == json) {
        return;
    }
    const blocoVar = bloco.querySelectorAll('.bloco_parametro_variavel li');
    blocoVar.forEach(item => {
        const check = item.querySelector('.bloco_checkbox .check');
        const chave = item.querySelector('.chave').value;
        const valor = item.querySelector('.valor');
        if (!check.checked || !chave.startsWith('$')) {
            return;
        }
        let valorTemp = json;
        valor.value.split('.').forEach(item => {
            try {
                valorTemp = valorTemp[item];
            } catch (e) {
                valorTemp = undefined;
            }
        });
        if (typeof valorTemp == 'string' || typeof valorTemp == 'number') {
            variavelLocal[chave] = valorTemp;
        }
    });
};
const pegarHtmlIframe = html => {
    if (!html.includes('<html') || html.includes('PRE PRINT EXIT') || html.includes('VAR_DUMP EXIT')) {
        return `<style>* {color: #FFF;}</style> ${html}`;
    }
    return html;
};
const mandarRequisicao = async (bloco, acao) => {
    const id = bloco.getAttribute('data-id');
    const blocoParametro = bloco.querySelector('.bloco_parametro_parametro');
    const blocoBody = bloco.querySelector('.bloco_parametro_body');
    const blocoHeader = bloco.querySelector('.bloco_parametro_header');
    const blocoJson = bloco.querySelector('.bloco_parametro_json');
    const blocoVariavel = bloco.querySelector('.bloco_parametro_variavel');
    const parPar = acao == 'salvar' ? pegarLinha(blocoParametro) : pegarParametro(blocoParametro);
    const parBody = acao == 'salvar' ? pegarLinha(blocoBody) : pegarParametro(blocoBody);
    const parHeader = acao == 'salvar' ? pegarLinha(blocoHeader) : pegarParametro(blocoHeader);
    const parVariavel = acao == 'salvar' ? pegarLinha(blocoVariavel) : variavelLocal;
    const parJson = acao == 'salvar' ? pegarLinha(blocoJson) : pegarParametro(blocoJson);
    const inputToken = bloco.querySelector('.input_token');
    const inputMetodo = bloco.querySelector('.input_metodo');
    const inputUri = bloco.querySelector('.input_uri');
    const inputDocumentacao = bloco.querySelector('.input_documentacao');
    const inputDocDescricao = bloco.querySelector('.input_descricao');
    const inputDocRequisicao = bloco.querySelector('.input_requisicao');
    const inputDocResposta = bloco.querySelector('.input_resposta');
    const menu = blocoMenuLista.querySelector('#' + id + '_menu');
    const pai = menu ? menu.closest('.grupo') : null;
    return await post('__postman', {
        acao: acao,
        id: id,
        pai: pai ? pai.getAttribute('data-nome') : '',
        token: inputToken.value,
        metodo: inputMetodo.value,
        uri: inputUri.value,
        parametro: JSON.stringify(parPar),
        body: JSON.stringify(parBody),
        header: JSON.stringify(parHeader),
        variavel: JSON.stringify(parVariavel),
        json: JSON.stringify(parJson),
        documentacao: inputDocumentacao.checked ? 'sim' : 'nao',
        descriaco: inputDocDescricao.value,
        requisicao: inputDocRequisicao.value,
        resposta: inputDocResposta.value,
    });
};
const pegarParametro = bloco => {
    const retorno = [];
    const lista = bloco.querySelectorAll('li');
    lista.forEach(item => {
        const check = item.querySelector('.bloco_checkbox input');
        const tipo = item.querySelector('.tipo').value;
        const indice = item.querySelector('.chave').value.trim();
        const valor = item.querySelector('.valor').value.trim();
        if (!check.checked || indice == '') {
            return;
        }
        retorno.push([tipo, indice, valor]);
    });
    return retorno;
};
const pegarLinha = bloco => {
    const retorno = [];
    const lista = bloco.querySelectorAll('li');
    lista.forEach(item => {
        const check = item.querySelector('.bloco_checkbox input');
        const tipo = item.querySelector('.tipo').value;
        const indice = item.querySelector('.chave').value.trim();
        const valor = item.querySelector('.valor').value.trim();
        const descricao = item.querySelector('.descricao').value.trim();
        if (indice == '') {
            return;
        }
        retorno.push([tipo, check.checked, indice, valor, descricao]);
    });
    return retorno;
};
