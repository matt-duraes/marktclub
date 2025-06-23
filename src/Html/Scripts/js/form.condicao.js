let fwFormCondicaoLinhaPadrao = $('.fw_form_condicao_linha_padrao');
if (fwFormCondicaoLinhaPadrao) {
    fwFormCondicaoLinhaPadrao = fwFormCondicaoLinhaPadrao.clonar();
    fwFormCondicaoLinhaPadrao.classe('fw_form_condicao_linha', true);
    fwFormCondicaoLinhaPadrao.classe('fw_form_condicao_linha_padrao', false);
}
let fwFormCondicaoGrupoPadrao = $('.fw_form_condicao_grupo_padrao');
if (fwFormCondicaoGrupoPadrao) {
    fwFormCondicaoGrupoPadrao = fwFormCondicaoGrupoPadrao.clonar();
    fwFormCondicaoGrupoPadrao.classe('fw_form_condicao_grupo', true);
    fwFormCondicaoGrupoPadrao.classe('fw_form_condicao_grupo_padrao', false);
}

fwFormCondicaoPegarValor = input => {
    const grupoLista = $$('.fw_form_condicao_grupo', input);
    const grupoQuantidade = grupoLista.length;
    if (grupoQuantidade === 0) {
        fwFormCondicaoAddZero(input);
        return false;
    }

    let iGrupo = 0;
    const grupoUltimo = grupoQuantidade - 1;
    const retorno = [];
    for (; iGrupo < grupoQuantidade; ++iGrupo) {
        const grupo = grupoLista[iGrupo];
        const separador = $('.fw_form_condicao_and_grupo .input_select_value', grupo).value;
        if (iGrupo != grupoUltimo && !['and', 'or'].includes(separador)) {
            return false;
        }

        const linhaLista = $$('.fw_form_condicao_linha', grupo);
        const linhaQuantidade = linhaLista.length;
        const linhaUltimo = linhaQuantidade - 1;
        let iLinha = 0;
        const retornoLinha = [];
        for (; iLinha < linhaQuantidade; ++iLinha) {
            const linha = linhaLista[iLinha];
            const ultimo = iLinha === linhaUltimo;
            const separador = !ultimo ? $('.fw_form_condicao_and_linha .input_select_value', linha).value : '';
            if (!ultimo && !['and', 'or'].includes(separador)) {
                return false;
            }
            const valor1 = $('.fw_form_condicao_valor_1', linha).value.trim();
            const condicao = $('.fw_form_condicao_tipo .input_select_value', linha).value.trim();
            const valor2 = $('.fw_form_condicao_valor_2', linha).value.trim();
            if (vazio(valor1) && vazio(valor2) && vazio(condicao)) {
                continue;
            } else if (valor1 === valor2 || vazio(condicao)) {
                return false;
            }
            retornoLinha.push({
                condicao: [valor1, condicao, valor2],
                separador: separador,
            });
        }
        if (retornoLinha.length === 0) {
            continue;
        }
        const numeroUltimo = retornoLinha.length - 1;
        retornoLinha[numeroUltimo].separador = '';
        retorno.push({
            grupo: retornoLinha,
            separador: separador,
        });
    }
    if (retorno.length === 0) {
        return [];
    }
    const numeroUltimo = retorno.length - 1;
    retorno[numeroUltimo].separador = '';
    return retorno;
};
fwFormCondicaoSetarValor = (input, valor) => {
    if (!input) {
        fwFormCondicaoSetarValorZero(input);
        return false;
    } else if (typeof valor === 'string') {
        try {
            valor = JSON.parse(valor);
            if (!typeof valor === 'object') {
                fwFormCondicaoSetarValorZero(input);
                return false;
            }
        } catch (error) {
            fwFormCondicaoSetarValorZero(input);
            return false;
        }
    }
    const pai = input.closest('.fw_form_condicao');
    if (!pai) {
        return false;
    }

    const listaRemover = $$('.fw_form_condicao_grupo .fw_form_condicao_remover_grupo', pai);
    for (const remover of listaRemover) {
        fwFormCondicaoRemover(remover);
    }

    const quantidadeGrupo = valor.length;
    let iGrupo = 1;
    for (const grupo of valor) {
        const blocoGrupo = fwFormCondicaoGrupoAdd(pai);
        const quantidadeLinha = grupo.grupo.length;
        let iLinha = 1;
        for (const linha of grupo.grupo) {
            const blocoLinha = fwFormCondicaoLinhaAdd(blocoGrupo);
            const blocoValor1 = $('.fw_form_condicao_valor_1', blocoLinha);
            const blocoCondicao = $('.fw_form_condicao_tipo', blocoLinha);
            const blocoValor2 = $('.fw_form_condicao_valor_2', blocoLinha);
            blocoValor1.valor(linha.condicao[0] || '');
            blocoCondicao.valor(linha.condicao[1] || '');
            blocoValor2.valor(linha.condicao[2] || '');
            if (quantidadeLinha === iLinha) {
                continue;
            }
            iLinha++;
            const select = $('.fw_form_condicao_and_linha', blocoLinha);
            select.valor(linha.separador);
        }
        if (quantidadeGrupo === iGrupo) {
            continue;
        }
        iGrupo++;
        $('.fw_form_condicao_and_grupo', blocoGrupo).valor(grupo.separador);
    }

    const quantidade = $$('.fw_form_condicao_grupo', pai).length;
    if (quantidade === 0) {
        fwFormCondicaoSetarValorZero(input);
    }
};
const fwFormCondicaoSetarValorZero = input => {
    const pai = input.closest('.fw_form_condicao');
    const lista = $$('.fw_form_condicao_grupo .fw_form_condicao_remover_grupo', pai);
    for (const remover of lista) {
        fwFormCondicaoRemover(remover, false);
    }
    const grupo = fwFormCondicaoGrupoAdd(pai);
    fwFormCondicaoLinhaAdd(grupo);
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

const fwFormCondicaoMonitorarDragLinha = grupo => {
    const item = $$('.fw_form_condicao_grupo_lista .fw_form_condicao_linha', grupo);
    const quantidade = item.length;
    if (quantidade === 0) {
        fwFormCondicaoLinhaAdd(grupo);
    }
    const ultimo = item[quantidade - 1];
    const ultimoValor = $('.fw_form_condicao_and_linha .input_select_value', ultimo).value;
    if (ultimoValor != '') {
        fwFormCondicaoLinhaAdd(grupo);
    }
};
const fwFormCondicaoMonitorarDragGrupo = bloco => {
    const lista = $$('.fw_form_condicao_grupo', bloco);
    const select = $('.fw_form_condicao_and_grupo .input_select_value', lista[lista.length - 1]).value;
    if (select === '') {
        return;
    }
    const grupo = fwFormCondicaoGrupoAdd(bloco);
    fwFormCondicaoLinhaAdd(grupo);
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
        .botao('.fw_form_condicao_drag_linha')
        .eventoFim(e => {
            const blocoDe = e.from.closest('.fw_form_condicao_grupo');
            const blocoPara = e.to.closest('.fw_form_condicao_grupo');
            fwFormCondicaoMonitorarDragLinha(blocoDe);
            if (blocoDe !== blocoPara) {
                fwFormCondicaoMonitorarDragLinha(blocoPara);
            }
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
    if ($$('.fw_form_condicao_grupo', item).length > 0) {
        return;
    }

    const grupo = fwFormCondicaoGrupoAdd(item);
    fwFormCondicaoLinhaAdd(grupo);
};

const fwFormCondicaoRemover = (bloco, add) => {
    const removerGrupo =
        bloco.classe('fw_form_condicao_remover_grupo', '?') || bloco.closest('.fw_form_condicao_remover_grupo');
    const removerLinha =
        bloco.classe('fw_form_condicao_remover_linha', '?') || bloco.closest('.fw_form_condicao_remover_linha');
    if (removerGrupo) {
        const pai = bloco.closest('.fw_form_condicao');
        const grupo = bloco.closest('.fw_form_condicao_grupo');
        const lista = $$('.fw_form_condicao_linha .input_select_texto', grupo);
        for (const select of lista) {
            select.removeEventListener('formChange', fwFormCondicaoMonitorarLinha);
        }
        const select = $('.fw_form_condicao_and_grupo .input_select_texto', grupo);
        select.removeEventListener('formChange', fwFormCondicaoMonitorarGrupo);
        grupo.remove();

        const quantidade = $$('.fw_form_condicao_grupo', pai).length;
        if (quantidade === 0 && add) {
            const blocoGrupo = fwFormCondicaoGrupoAdd(pai);
            fwFormCondicaoLinhaAdd(blocoGrupo);
        }
    } else if (removerLinha) {
        const linha = bloco.closest('.fw_form_condicao_linha');
        linha.remove();
    }
};
window.addEventListener('load', () => {
    const listaCondicao = document.querySelectorAll('.fw_form_condicao');
    if (listaCondicao.length === 0) {
        return;
    }
    listaCondicao.forEach(item => {
        fwFormCondicaoInit(item);
        item.evento('dblclick', e => {
            fwFormCondicaoRemover(e.target, true);
        });

        new DragDrop()
            .bloco(item)
            .botao('.fw_form_condicao_drag_grupo')
            .item('.fw_form_condicao_grupo')
            .eventoFim(() => {
                fwFormCondicaoMonitorarDragGrupo(item);
            })
            .iniciar();
    });
});
