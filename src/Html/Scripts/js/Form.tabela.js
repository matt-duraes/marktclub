const fwTabelaLinhaPadrao = $('.fw_form_tabela_linha_padrao');
if (fwTabelaLinhaPadrao) {
    fwTabelaLinhaPadrao.classe('fw_form_tabela_linha_padrao', false);
}
const fwTabelaColunaPadrao = $('.fw_form_tabela_coluna_padrao');
if (fwTabelaColunaPadrao) {
    fwTabelaColunaPadrao.classe('fw_form_tabela_coluna_padrao', false);
}
const fwTabelaColunaHeaderPadrao = $('.fw_form_tabela_coluna_header_padrao');
if (fwTabelaColunaHeaderPadrao) {
    fwTabelaColunaHeaderPadrao.classe('fw_form_tabela_coluna_header_padrao', false);
}

const fwTabelaTamanhoColuna = tabela => {
    const colunaLista = $$('.fw_form_tabela_header .fw_form_tabela_coluna', tabela);
    if (colunaLista.length == 0) {
        return;
    }
    const tamanho = colunaLista.length * 350;
    tabela.attr('data-tamanho', tamanho);

    const linhaLista = $$('.fw_form_tabela_linha', tabela);
    for (const linha of linhaLista) {
        linha.css('width', tamanho + 'px');
    }
};

const fwTabelaColocaNumeroColuna = tabela => {
    const lista = $$('.fw_form_tabela_linha', tabela);
    for (const item of lista) {
        const coluna = $$('.fw_form_tabela_coluna', item);
        const quantidade = coluna.length;
        let i = 0;
        for (; i < quantidade; ++i) {
            coluna[i].attr('data-posicao', i);
        }
    }
};
const fwTabelaReordenarColuna = tabela => {
    const header = $$('.fw_form_tabela_header .fw_form_tabela_coluna', tabela);
    const quantidade = header.length;
    const posicao = {};
    let i = 0;
    for (; i < quantidade; ++i) {
        posicao[i] = header[i].attr('data-posicao');
    }
    const linhaLista = $$('.fw_form_tabela_conteudo .fw_form_tabela_linha', tabela);
    for (const linha of linhaLista) {
        const colunaLista = $$('.fw_form_tabela_coluna', linha);
        i = 0;
        let novoHtml = [];
        for (; i < quantidade; ++i) {
            const posicaoNova = posicao[i];
            novoHtml[i] = colunaLista[posicaoNova];
        }
        const conteudo = $('.fw_form_tabela_linha_conteudo', linha);
        conteudo.html('');
        i = 0;
        for (; i < quantidade; ++i) {
            conteudo.final(novoHtml[i]);
        }
    }
    fwTabelaColocaNumeroColuna(tabela);
};
const fwTabelaMarcarGrow = (linha, input) => {
    const lista = $$('.fw_form_tabela_grow input', linha);
    for (const item of lista) {
        item.checked = false;
    }
    input.checked = true;
};
const fwTabelaRemoverColuna = async (tabela, linha, coluna) => {
    if (
        !(await Alerta.confirmar(
            'Deletar coluna',
            'Tem certeza que deseja remover essa coluna? Essa ação não poderá ser desfeita.',
            '!'
        ))
    ) {
        return;
    }

    const colunaLista = $$('.fw_form_tabela_coluna', linha);
    const quantidade = colunaLista.length;
    let colunaRemover = 0;
    let i = 0;
    for (; i < quantidade; ++i) {
        if (colunaLista[i] === coluna) {
            colunaRemover = i;
            break;
        }
    }
    const linhaLista = $$('.fw_form_tabela_linha', tabela);
    for (const item of linhaLista) {
        const remover = $$('.fw_form_tabela_coluna', item)[colunaRemover];
        remover.remove();
    }
    fwTabelaTamanhoColuna(tabela);
    fwTabelaColocaNumeroColuna(tabela);
};
const fwTabelaRemoverLinha = async linha => {
    if (
        !(await Alerta.confirmar(
            'Deletar linha',
            'Tem certeza que deseja remover essa linha? Essa ação não poderá ser desfeita.',
            '!'
        ))
    ) {
        return;
    }
    linha.remove();
};
const fwTabelaItem = tabela => {
    const botaoAddLinha = $('.fw_form_tabela_add_linha', tabela);
    const botaoAddColuna = $('.fw_form_tabela_add_coluna', tabela);

    const conteudoHeader = $('.fw_form_tabela_header .fw_form_tabela_linha_conteudo', tabela);
    const conteudoLista = $('.fw_form_tabela_conteudo', tabela);
    fwTabelaTamanhoColuna(tabela);

    new DragDrop().bloco(conteudoLista).botao('.fw_form_tabela_drag').item('.fw_form_tabela_linha').iniciar();
    new DragDrop()
        .bloco(conteudoHeader)
        .botao('.fw_form_tabela_drag')
        .item('.fw_form_tabela_coluna')
        .eventoFim(() => {
            fwTabelaReordenarColuna(tabela);
        })
        .iniciar();

    botaoAddLinha.evento('click', () => {
        const tamanho = parseInt(tabela.attr('data-tamanho'));
        const linha = fwTabelaLinhaPadrao.clonar();
        linha.css('width', tamanho + 'px');
        const conteudo = $('.fw_form_tabela_linha_conteudo', linha);
        const quantidade = $$('.fw_form_tabela_header .fw_form_tabela_coluna').length;
        let i = 0;
        for (; i < quantidade; ++i) {
            const coluna = fwTabelaColunaPadrao.clonar();
            conteudo.final(coluna);
        }
        conteudoLista.inicio(linha);
    });
    botaoAddColuna.evento('click', () => {
        const linhaLista = $$('.fw_form_tabela_conteudo .fw_form_tabela_linha_conteudo', tabela);
        for (const linha of linhaLista) {
            const coluna = fwTabelaColunaPadrao.clonar();
            linha.inicio(coluna);
        }
        conteudoHeader.inicio(fwTabelaColunaHeaderPadrao.clonar());
        fwTabelaTamanhoColuna(tabela);
        fwTabelaColocaNumeroColuna(tabela);
    });
    tabela.evento('click', e => {
        const target = e.target;
        if (target.classe('fw_form_tabela_coluna_remover', '?') || target.closest('.fw_form_tabela_coluna_remover')) {
            fwTabelaRemoverColuna(
                tabela,
                target.closest('.fw_form_tabela_linha'),
                target.closest('.fw_form_tabela_coluna')
            );
        } else if (
            target.classe('fw_form_tabela_linha_remover', '?') ||
            target.closest('.fw_form_tabela_linha_remover')
        ) {
            fwTabelaRemoverLinha(target.closest('.fw_form_tabela_linha'));
        } else if (target.classe('fw_form_tabela_grow', '?') || target.closest('.fw_form_tabela_grow')) {
            const bloco = target.classe('fw_form_tabela_grow', '?') ? target : target.closest('.fw_form_tabela_grow');
            fwTabelaMarcarGrow(target.closest('.fw_form_tabela_linha'), $('input', bloco));
        }
    });
};
const fwFormTabelaLoading = bloco => {
    const lista = $$('.fw_form_tabela', bloco);
    if (lista.length == 0) {
        return;
    }
    for (const tabela of lista) {
        fwTabelaItem(tabela);
    }
};
fwFormTabelaLoading(document);

fwFormTabelaPegarValor = tabela => {
    const headerLista = $$('.fw_form_tabela_header .fw_form_tabela_coluna', tabela);
    const linhaLista = $$('.fw_form_tabela_conteudo .fw_form_tabela_linha .fw_form_tabela_linha_conteudo', tabela);
    const quantidade = headerLista.length;
    if (quantidade == 0) {
        return {};
    }
    const retorno = {
        header: {},
        linha: {},
    };
    let i = 0;
    for (; i < quantidade; ++i) {
        const item = headerLista[i];
        const valor = $('.fw_form_tabela_texto', item);
        const auto = $('.fw_form_tabela_grow_input', item);
        const tamanho = $('.fw_form_tabela_tamanho', item);
        retorno.header[i] = {
            auto: auto.checked,
            tamanho: tamanho.valor(),
            valor: valor.valor(),
        };
    }

    i = 0;
    for (const linha of linhaLista) {
        i2 = 0;
        const item = $$('.fw_form_tabela_coluna .fw_form_tabela_texto', linha);
        retorno.linha[i] = {};
        for (; i2 < quantidade; ++i2) {
            retorno.linha[i][i2] = { valor: item[i2].valor() };
        }
        ++i;
    }
    return retorno;
};
fwFormTabelaSetarValor = (tabela, valor) => {
    const headerConteudo = $('.fw_form_tabela_header .fw_form_tabela_linha_conteudo', tabela);
    const linhaConteudo = $('.fw_form_tabela_conteudo', tabela);
    if (headerConteudo) {
        headerConteudo.html('');
    }
    if (linhaConteudo) {
        linhaConteudo.html('');
    }

    if (!(typeof valor === 'object') || !('header' in valor) || !('linha' in valor)) {
        return;
    }

    const header = valor.header;
    const linha = valor.linha;

    const colunaQuantidade = Object.keys(header).length;
    const linhaQuantidade = Object.keys(linha).length;

    let i = 0;
    for (; i < colunaQuantidade; ++i) {
        const clone = fwTabelaColunaHeaderPadrao.clonar();
        $('.fw_form_tabela_texto', clone).valor(header[i].valor);
        $('.fw_form_tabela_grow_input', clone).checked = header[i].auto;
        $('.fw_form_tabela_tamanho', clone).valor(header[i].tamanho);
        headerConteudo.final(clone);
    }
    i = 0;
    let i2 = 0;
    for (; i < linhaQuantidade; ++i) {
        const item = linha[i];
        const linhaClone = fwTabelaLinhaPadrao.clonar();
        const linhaCloneConteudo = $('.fw_form_tabela_linha_conteudo', linhaClone);
        i2 = 0;
        for (; i2 < colunaQuantidade; ++i2) {
            const colunaClone = fwTabelaColunaPadrao.clonar();
            $('.fw_form_tabela_texto', colunaClone).valor(item[i2].valor);
            linhaCloneConteudo.final(colunaClone);
        }
        linhaConteudo.final(linhaClone);
    }

    fwTabelaTamanhoColuna(tabela);
    fwTabelaColocaNumeroColuna(tabela);
};
