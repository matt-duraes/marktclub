const fwFormCondicaoLinhaPadrao = $('.fw_form_condicao_linha_padrao');
if (fwFormCondicaoLinhaPadrao) {
    fwFormCondicaoLinhaPadrao.classe('fw_form_condicao_linha_padrao', false);
}
const fwFormCondicaoGrupoPadrao = $('.fw_form_condicao_grupo_padrao');
if (fwFormCondicaoGrupoPadrao) {
    fwFormCondicaoGrupoPadrao.classe('fw_form_condicao_grupo_padrao', false);
}

fwFormCondicaoPegarValor = input => {
    const lista = $$('.fw_form_condicao_lista .fw_form_condicao_grupo', input);
    let retorno = [];
    for (const item of lista) {
        const grupo = {
            separador: 'and',
            lista: [],
        };
        const lista = $$('.fw_form_condicao_linha', item);
        for (const linha of lista) {
            grupo.lista.push([
                $('.fw_form_condicao_valor_1', linha).texto(),
                $('.fw_form_condicao_condicao', linha).texto(),
                $('.fw_form_condicao_valor_2', linha).texto(),
                'and',
            ]);
        }
        retorno.push(grupo);
    }
    return retorno;
};
fwFormCondicaoSetarValor = (input, valor) => {
    if (typeof valor === 'string') {
        try {
            valor = JSON.parse(valor);
        } catch (error) {
            valor = null;
        }
    }
    if (valor === null || valor === undefined || typeof valor !== 'object' || !input) {
        return;
    }
    valor = valor[0];
    const quantidade = Object.keys(valor).length;
    if (quantidade == 0) {
        return;
    }
    const conteudo = $('.fw_form_condicao_lista', input);
    let i = 0;
    for (; i < quantidade; ++i) {
        const item = valor[i];
        const clone = fwFormCondicaoPadrao.clonar();
        $('.fw_form_condicao_valor_1', clone).texto(item[0]);
        $('.fw_form_condicao_condicao', clone).texto(item[1]);
        $('.fw_form_condicao_valor_2', clone).texto(item[2]);
        conteudo.final(clone);
    }
};

let fwFormCondicaoId = 0;
const fwFormCondicaoClonar = bloco => {
    const clone = bloco.clonar();
    const idLista = $$('.fw_form_condicao_add_id', clone);
    for (const id of idLista) {
        id.attr('id', 'fw_form_condicao_' + fwFormCondicaoId);
        fwFormCondicaoId++;
    }
    return clone;
};

const fwFormCondicaoMonitorarGrupo = e => {
    const pai = e.target.closest('.fw_form_condicao');
    const filho = e.target.closest('.fw_form_condicao_grupo');
    const select = e.target.closest('.fw_form_condicao_and_grupo');
    const valor = $('.input_select_value', select);
    const lista = $$('ul li', select);
    if (valor != '' && lista.length == 3) {
        lista[0].remove();
    }
    if (filho !== pai.lastElementChild) {
        return;
    }
    const grupo = fwFormCondicaoGrupoAdd(pai);
    fwFormCondicaoLinhaAdd(grupo);
};

const fwFormCondicaoMonitorarDrag = (blocoDe, blocoPara) => {
    //
};

const fwFormCondicaoGrupoAdd = bloco => {
    const grupo = fwFormCondicaoClonar(fwFormCondicaoGrupoPadrao);
    const select = $('.fw_form_condicao_and_grupo .input_select_texto', grupo);
    select.addEventListener('formChange', fwFormCondicaoMonitorarGrupo);
    bloco.final(grupo);
    fwFormLoadingSelect(grupo);

    const pai = bloco.closest('.fw_form_condicao');
    new DragDrop()
        .grupo('#' + pai.attr('id') + ' .fw_form_condicao_grupo_lista')
        .bloco($('.fw_form_condicao_grupo_lista', grupo))
        .botao('.fw_form_condicao_ordem')
        .eventoFim(e => {
            const blocoDe = e.from.closest('.fw_form_condicao_grupo');
            const blocoPara = e.to.closest('.fw_form_condicao_grupo');
            fwFormCondicaoMonitorarDrag(blocoDe, blocoPara);
        })
        .item('.fw_form_condicao_linha')
        .iniciar();

    return grupo;
};

const fwFormCondicaoMonitorarLinha = e => {
    const pai = e.target.closest('.fw_form_condicao_grupo_lista');
    const filho = e.target.closest('.fw_form_condicao_linha');
    const select = e.target.closest('.fw_form_condicao_and_linha');
    const valor = $('.input_select_value', select);
    const lista = $$('ul li', select);
    if (valor != '' && lista.length == 3) {
        lista[0].remove();
    }
    if (filho !== pai.lastElementChild) {
        return;
    }
    const grupo = e.target.closest('.fw_form_condicao_grupo');
    fwFormCondicaoLinhaAdd(grupo);
};

const fwFormCondicaoLinhaAdd = grupo => {
    const linha = fwFormCondicaoClonar(fwFormCondicaoLinhaPadrao);
    const bloco = $('.fw_form_condicao_grupo_lista', grupo);
    const select = $('.fw_form_condicao_and_linha .input_select_texto', linha);
    select.addEventListener('formChange', fwFormCondicaoMonitorarLinha);
    bloco.final(linha);
    fwFormLoadingSelect(linha);
    return linha;
};
const fwFormCondicaoInit = item => {
    const grupo = fwFormCondicaoGrupoAdd(item);
    fwFormCondicaoLinhaAdd(grupo);
};

window.addEventListener('load', () => {
    const listaCondicao = document.querySelectorAll('.fw_form_condicao');
    if (listaCondicao.length === 0) {
        return;
    }
    listaCondicao.forEach(item => {
        fwFormCondicaoInit(item);
    });
});
