const blocoAbaModelo = pegarElementoModelo('bloco_aba_modelo');
const blocoRequisicaoModelo = pegarElementoModelo('bloco_requisicao_modelo');
const blocoLinhaModelo = pegarElementoModelo('bloco_linha_modelo');

const blocoAbaLista = $('#bloco_aba_lista');
const blocoRequisicaoLista = $('#bloco_requisicao_lista');
const blocoRequisicaoVazio = $('#bloco_requisicao_vazio');

const abrirNovaAba = (requisicao, grupo) => {
    const id = requisicao.getAttribute('data-id');
    const metodo = requisicao.getAttribute('data-metodo');
    const nome = requisicao.getAttribute('data-nome');
    const abaExiste = blocoAbaLista.querySelector('#' + id + '_aba');
    const abaAtiva = blocoAbaLista.querySelector('.aba.ativo');
    const requisicaoExiste = blocoRequisicaoLista.querySelector('#' + id + '_requisicao');
    const requisicaoAtiva = blocoRequisicaoLista.querySelector('.bloco_requisicao.ativo');

    if (abaAtiva && abaExiste && abaAtiva == abaExiste) {
        return;
    }
    if (abaAtiva) {
        abaAtiva.classList.remove('ativo');
    }
    if (requisicaoAtiva) {
        requisicaoAtiva.classList.remove('ativo');
    }
    if (abaExiste) {
        abaExiste.classList.add('ativo');
    } else {
        adicionarNovaAba(id, metodo, nome);
    }
    if (requisicaoExiste) {
        requisicaoExiste.classList.add('ativo');
    } else {
        adicionarNovaRequisicao(id, grupo ? grupo.getAttribute('data-nome') : '');
    }
};
const adicionarNovaAba = (id, metodo, nome) => {
    const clone = blocoAbaModelo.cloneNode(true);
    const blocoMetodo = clone.querySelector('.metodo');
    clone.setAttribute('id', id + '_aba');
    clone.setAttribute('data-id', id);
    blocoMetodo.innerText = metodo;
    blocoMetodo.classList.add(metodo);
    clone.querySelector('.nome').innerText = nome;
    blocoAbaLista.appendChild(clone);
    clone.scrollIntoView();
};
const adicionarNovaRequisicao = async (id, pai) => {
    const clone = blocoRequisicaoModelo.cloneNode(true);
    clone.setAttribute('id', id + '_requisicao');
    blocoRequisicaoLista.prepend(clone);

    blocoRequisicaoLista.classList.remove('display_none');
    blocoRequisicaoVazio.classList.add('display_none');

    const resposta = await post(
        '__postman',
        {
            acao: 'buscar-requisicao',
            id: id,
            pai: pai,
        },
        'Ocorreu um erro ao buscar requisição.'
    );

    if (false === resposta) {
        return;
    }
    adicionarDadoAoRequest(clone, resposta.dado);
};
const adicionarDadoAoRequest = (bloco, resposta) => {
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
    botaoEnviar.addEventListener('click', enviarRequisicao);

    const inputToken = bloco.querySelector('.input_token');
    const blocoMetodo = bloco.querySelector('.bloco_metodo');
    const inputMetodo = bloco.querySelector('.input_metodo');
    const inputUri = bloco.querySelector('.input_uri');
    const inputDescricao = bloco.querySelector('.input_descricao');
    const inputRequisicao = bloco.querySelector('.input_requisicao');
    const inputResposta = bloco.querySelector('.input_resposta');
    inputToken.value = resposta.token;
    if (!blocoMetodo.classList.contains('inativo')) {
        inputMetodo.value = resposta.metodo;
    }
    inputUri.value = resposta.uri;
    inputDescricao.value = resposta.documentacao.descricao;
    inputRequisicao.value = resposta.documentacao.requisicao;
    inputResposta.value = resposta.documentacao.resposta;
    const blocoParametro = bloco.querySelector('.bloco_parametro_parametro');
    const blocoBody = bloco.querySelector('.bloco_parametro_body');
    const blocoHeader = bloco.querySelector('.bloco_parametro_header');
    const blocoVar = bloco.querySelector('.bloco_parametro_variavel');
    montarParametro(blocoParametro, resposta.parametro);
    montarParametro(blocoBody, resposta.body);
    montarParametro(blocoHeader, resposta.header);
    montarParametro(blocoVar, resposta.variavel);
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
const montarParametro = (bloco, lista) => {
    bloco.innerHTML = '';
    lista.forEach(item => {
        adicionarNovaLinha(bloco, lista[1], lista[2], lista[3]);
    });
    adicionarNovaLinha(bloco, false, '', '');
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
