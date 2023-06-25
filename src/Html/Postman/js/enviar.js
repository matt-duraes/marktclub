const enviarRequisicao = async e => {
    const bloco = e.target.closest('.bloco_requisicao');
    const resposta = await mandarRequisicao(bloco, 'requisicao');
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
