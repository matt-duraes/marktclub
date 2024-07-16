const botaoNovaAba = $('#botao_nova_aba');

const blocoAbaModelo = pegarElementoModelo('bloco_aba_modelo');
const blocoRequisicaoModelo = pegarElementoModelo('bloco_requisicao_modelo');
const blocoLinhaModelo = pegarElementoModelo('bloco_linha_modelo');

const blocoAbaLista = $('#bloco_aba_lista');
const blocoRequisicaoLista = $('#bloco_requisicao_lista');
const blocoRequisicaoVazio = $('#bloco_requisicao_vazio');

botaoNovaAba.addEventListener('click', () => {
    const id = 'id_temp_' + Math.floor(Date.now() * Math.random()).toString(36);
    abrirNovaAba(id, 'GET', 'Temporario');
});

blocoAbaLista.addEventListener('click', e => {
    const clickFechar = e.target.classList.contains('fechar') || e.target.closest('.fechar');
    const clickAba = e.target.classList.contains('aba') || e.target.closest('.aba');
    if (clickFechar) {
        fecharAbaAberta(e.target.closest('.aba'));
    } else if (clickAba) {
        abrirAbaJaAberta(e.target.classList.contains('aba') ? e.target : e.target.closest('.aba'));
    }
});
blocoRequisicaoLista.addEventListener('input', e => {
    if (!e.target.classList.contains('monitorar_salvar') && !e.target.closest('.monitorar_salvar')) {
        return;
    }
    const bloco = e.target.closest('.bloco_requisicao');
    const botaoSalvar = bloco.querySelector('.botao_salvar');
    if (botaoSalvar) {
        botaoSalvar.classList.remove('display_none');
    }
});
const fecharAbaAberta = async aba => {
    if (!aba) {
        return;
    }
    const id = aba.getAttribute('data-id');
    const menu = blocoMenuLista.querySelector('#' + id + '_menu');
    const bloco = blocoRequisicaoLista.querySelector('#' + id + '_requisicao');
    const botaoSalvar = bloco.querySelector('.botao_salvar');
    if (!botaoSalvar || botaoSalvar.classList.contains('display_none')) {
        confirmarFecharAba(bloco, menu, aba);
    } else if (
        await Alerta.confirmar('confirmar', 'Tem certeza que deseja fechar essa requisição sem salvar?', false)
    ) {
        confirmarFecharAba(bloco, menu, aba);
    }
};
const confirmarFecharAba = (bloco, menu, aba) => {
    if (menu) {
        menu.classList.remove('aberto');
        menu.classList.remove('ativo');
    }
    if (bloco) {
        bloco.parentNode.removeChild(bloco);
    }
    const abaAberta = aba.classList.contains('ativo');
    aba.parentNode.removeChild(aba);
    if (abaAberta) {
        abrirAbaJaAberta(blocoAbaLista.querySelector('.aba'));
    }
};

const abrirAbaJaAberta = aba => {
    if (!aba) {
        blocoRequisicaoVazio.classList.remove('display_none');
        blocoRequisicaoLista.classList.add('display_none');
        return;
    }
    const id = aba.getAttribute('data-id');
    const menu = blocoMenuLista.querySelector('#' + id + '_menu');
    let grupo, nome, metodo;
    if (menu) {
        menu.classList.add('aberto');
        metodo = menu.getAttribute('data-metodo');
        nome = menu.getAttribute('data-nome');
        grupo = menu.closest('.grupo');
        if (grupo) {
            grupo.classList.remove('fechado');
        }
    }
    abrirNovaAba(id, metodo, nome, grupo);
};

const abrirNovaAba = (id, metodo, nome, grupo) => {
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
    clone.setAttribute('data-id', id);
    clone.setAttribute('id', id + '_requisicao');
    blocoRequisicaoLista.prepend(clone);

    blocoRequisicaoLista.classList.remove('display_none');
    blocoRequisicaoVazio.classList.add('display_none');

    const eVazio = id.startsWith('id_temp_');
    const acao = eVazio ? 'requisicao-vazia' : 'buscar-requisicao';
    const resposta = await post(
        '__postman',
        {
            acao: acao,
            id: id,
            pai: pai,
        },
        'Ocorreu um erro ao buscar requisição.'
    );

    if (false === resposta) {
        return;
    }
    adicionarDadoAoRequest(clone, resposta.dado, eVazio);
};
const adicionarDadoAoRequest = (bloco, resposta, vazio) => {
    const botaoParametro = bloco.querySelector('.botao_parametro');
    const botaoBody = bloco.querySelector('.botao_body');
    const botaoHeader = bloco.querySelector('.botao_header');
    const botaoJson = bloco.querySelector('.botao_json');
    const botaoVariavel = bloco.querySelector('.botao_variavel');
    const botaoDocumentacao = bloco.querySelector('.botao_documentacao');
    const botaoSalvar = bloco.querySelector('.botao_salvar');
    const botaoEnviar = bloco.querySelector('.botao_enviar');
    const botaoRespostaJson = bloco.querySelector('.botao_resposta_json');
    const botaoRespostaBody = bloco.querySelector('.botao_resposta_body');
    const botaoRespostaHtml = bloco.querySelector('.botao_resposta_html');
    const botaoRespostaRequisicao = bloco.querySelector('.botao_resposta_requisicao');

    const inputToken = bloco.querySelector('.input_token');
    const blocoMetodo = bloco.querySelector('.bloco_metodo');
    const inputMetodo = bloco.querySelector('.input_metodo');
    const inputUri = bloco.querySelector('.input_uri');
    const inputDocumentacao = bloco.querySelector('.input_documentacao');
    const inputDescricao = bloco.querySelector('.input_descricao');
    const inputRequisicao = bloco.querySelector('.input_requisicao');
    const inputResposta = bloco.querySelector('.input_resposta');
    const inputScope = bloco.querySelector('.input_scope');

    botaoParametro.addEventListener('click', abrirNovoParametro);
    botaoBody.addEventListener('click', abrirNovoParametro);
    botaoHeader.addEventListener('click', abrirNovoParametro);
    botaoJson.addEventListener('click', abrirNovoParametro);
    botaoVariavel.addEventListener('click', abrirNovoParametro);
    botaoDocumentacao.addEventListener('click', abrirNovoParametro);
    botaoEnviar.addEventListener('click', enviarRequisicao);
    inputMetodo.addEventListener('change', mudarTipoMetodo);
    if (!vazio) {
        botaoSalvar.addEventListener('click', salvarRequisicao);
    } else {
        botaoSalvar.parentNode.removeChild(botaoSalvar);
        botaoDocumentacao.parentNode.removeChild(botaoDocumentacao);
    }

    botaoRespostaJson.addEventListener('click', mudarTipoResposta);
    botaoRespostaBody.addEventListener('click', mudarTipoResposta);
    botaoRespostaHtml.addEventListener('click', mudarTipoResposta);
    botaoRespostaRequisicao.addEventListener('click', mudarTipoResposta);

    inputToken.value = resposta.token;
    if (!blocoMetodo.classList.contains('inativo')) {
        inputMetodo.value = resposta.metodo;
    }
    inputUri.value = resposta.uri;
    inputDocumentacao.checked = resposta.documentacao.status;
    inputDescricao.value = resposta.documentacao.descricao;
    inputRequisicao.value = resposta.documentacao.requisicao;
    inputResposta.value = resposta.documentacao.resposta;
    inputScope.value = resposta.documentacao.scope || '';
    const blocoParametro = bloco.querySelector('.bloco_parametro_parametro');
    const blocoBody = bloco.querySelector('.bloco_parametro_body');
    const blocoJson = bloco.querySelector('.bloco_parametro_json');
    const blocoHeader = bloco.querySelector('.bloco_parametro_header');
    const blocoVar = bloco.querySelector('.bloco_parametro_variavel');
    montarParametro(blocoParametro, resposta.parametro);
    montarParametro(blocoBody, resposta.body);
    montarParametro(blocoHeader, resposta.header);
    montarParametro(blocoVar, resposta.variavel);
    montarParametro(blocoJson, resposta.json);

    if (resposta.body.length > 0) {
        botaoBody.classList.add('ativo');
        blocoBody.classList.add('ativo');
    } else if (resposta.json != '') {
        botaoJson.classList.add('ativo');
        blocoJson.classList.add('ativo');
    } else {
        botaoParametro.classList.add('ativo');
        blocoParametro.classList.add('ativo');
    }

    new Sortable(blocoParametro, {
        animation: 150,
        draggable: '.linha_principal_body',
        handle: '.drag',
        onChange: () => {
            mostrarBotaoSalvar(blocoParametro.closest('.bloco_requisicao'));
        },
    });
};
const mudarTipoMetodo = e => {
    const metodo = e.target.value;
    const metodoTexto = metodo == 'DELETE' ? 'DEL' : metodo;
    const bloco = e.target.closest('.bloco_requisicao');
    const id = bloco.getAttribute('data-id');
    const aba = blocoAbaLista.querySelector('#' + id + '_aba .metodo');
    aba.className = 'metodo ' + metodo;
    aba.innerText = metodoTexto;

    const menu = blocoMenuLista.querySelector('#' + id + '_menu .metodo');
    if (menu) {
        menu.className = 'metodo ' + metodo;
        menu.innerText = metodoTexto;
    }
};
const mudarTipoResposta = e => {
    const botao = e.target.classList.contains('tipo_resposta') ? e.target : e.target.closest('.tipo_resposta');
    const id = botao.getAttribute('data-id') || '';
    if (id == '') {
        return;
    }
    const blocoLista = botao.closest('.bloco_resposta_lista');
    const blocoAtivo = blocoLista.querySelector('.bloco_resposta.ativo');
    const menuAtivo = blocoLista.querySelector('.tipo_resposta.ativo');
    const blocoNovo = blocoLista.querySelector('.' + id);
    if (!blocoNovo || botao == menuAtivo) {
        return;
    }
    if (menuAtivo) {
        menuAtivo.classList.remove('ativo');
    }
    if (blocoAtivo) {
        blocoAtivo.classList.remove('ativo');
    }
    botao.classList.add('ativo');
    blocoNovo.classList.add('ativo');
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
        adicionarNovaLinha(bloco, item[0], item[1], item[2], item[3], item[4]);
    });
    adicionarNovaLinha(bloco, 'texto', false, '', '', '');
};
const adicionarNovaLinha = (bloco, tipo, check, chave, valor, descricao) => {
    const clone = blocoLinhaModelo.cloneNode(true);
    const botaoDeletar = clone.querySelector('.deletar');
    const inputDescricao = clone.querySelector('.descricao');
    const inputTipo = clone.querySelector('.tipo');
    const inputChave = clone.querySelector('.chave');
    const inputValor = clone.querySelector('.valor');
    const inputCheck = clone.querySelector('.check');
    const blocoCheck = clone.querySelector('.bloco_checkbox span');
    const iconeDrag = clone.querySelector('.drag svg');
    inputCheck.checked = check;
    inputTipo.value = tipo;
    inputChave.value = chave;
    inputValor.value = valor;
    inputDescricao.value = descricao;
    bloco.appendChild(clone);
    botaoDeletar.addEventListener('click', mostrarBotaoSalvar);
    botaoDeletar.addEventListener('click', deletarLinha);
    if (chave == '') {
        blocoCheck.classList.add('display_none');
        iconeDrag.classList.add('display_none');
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
    const iconeDrag = linha.querySelector('.drag svg');
    const botaoDeletar = linha.querySelector('.deletar');
    iconeDrag.classList.remove('display_none');
    blocoCheck.classList.remove('display_none');
    inputCheck.disabled = false;
    inputCheck.checked = true;
    botaoDeletar.classList.remove('display_none');
    inputChave.removeEventListener('keyup', monitorarUltimaLinha);
    inputValor.removeEventListener('keyup', monitorarUltimaLinha);
    adicionarNovaLinha(bloco, 'texto', false, '', '', '');
};
const deletarLinha = e => {
    const linha = e.target.closest('li');
    const botaoDeletar = linha.querySelector('.deletar');
    botaoDeletar.removeEventListener('click', deletarLinha);
    linha.parentNode.removeChild(linha);
};

const mostrarBotaoSalvar = e => {
    let bloco;
    if (e && 'classList' in e && e.classList.contains('bloco_requisicao')) {
        bloco = e;
    } else if (e && 'target' in e && 'closest' in e.target) {
        bloco = e.target.closest('.bloco_requisicao');
    }
    if (bloco) {
        return;
    }
    const salvar = bloco.querySelector('.botao_salvar');
    salvar.classList.remove('display_none');
};
