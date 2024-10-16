const fwFormListaItemPadrao = $('.fw_form_lista_item_padrao');
if (fwFormListaItemPadrao) {
    fwFormListaItemPadrao.classe('fw_form_lista_item_padrao', false);
}

const fwFormListaSetarValor = (bloco, valor) => {
    const traducao = $('.form_input_traducao', bloco);
    if (traducao) {
        traducao.valor('');
    }

    const conteudo = $('.fw_form_lista_conteudo', bloco);
    if (!conteudo) {
        return;
    } else if (vazio(valor)) {
        conteudo.html('');
        return;
    }
    const quantidade = Object.keys(valor).length;
    let i = 0;
    for (; i < quantidade; ++i) {
        const clone = fwFormListaItemPadrao.clonar();
        const br = $('.fw_form_lista_br input', clone);
        const en = $('.fw_form_lista_en input', clone);
        const es = $('.fw_form_lista_es input', clone);
        if (br) {
            br.value = valor[i].br;
        }
        if (en) {
            en.value = valor[i].en;
        }
        if (es) {
            es.value = valor[i].es;
        }
        conteudo.final(clone);
        fwFormListaAdicionarEventoItem(clone);
    }
};
const fwFormListaPegarValor = bloco => {
    const lista = $$('.fw_form_lista_conteudo .fw_form_lista_item', bloco);
    const quantidade = lista.length;
    let i = 0;
    const valor = {};
    for (; i < quantidade; ++i) {
        const inputBr = $('.fw_form_lista_br input', lista[i]);
        const inputEn = $('.fw_form_lista_en input', lista[i]);
        const inputEs = $('.fw_form_lista_es input', lista[i]);
        valor[i] = {
            br: inputBr ? inputBr.value : '',
            en: inputEn ? inputEn.value : '',
            es: inputEs ? inputEs.value : '',
        };
    }
    return valor;
};

const fwFormListaAdicionar = (conteudo, input) => {
    const valor = input.valor();
    if (vazio(valor.br)) {
        Alerta.notificacao('Você precisa enviar pelo menos o texto em português.', false);
        return;
    }
    const clone = fwFormListaItemPadrao.clonar();
    $('.fw_form_lista_br input', clone).value = valor.br;
    $('.fw_form_lista_en input', clone).value = valor.en;
    $('.fw_form_lista_es input', clone).value = valor.es;
    conteudo.final(clone);
    input.valor('');

    $('.form_input_traducao_br', input).focus();
    fwFormListaAdicionarEventoItem(clone);
};

const fwFormListaEditar = (linha, botaoEditar, botaoSalvar) => {
    const inputLista = $$('.fw_form_lista_bloco_texto input', linha);
    for (const input of inputLista) {
        input.removeAttribute('readonly');
    }
    inputLista[0].focus();
    inputLista[0].select();
    botaoEditar.classe('fw_form_lista_hide', true);
    botaoSalvar.classe('fw_form_lista_hide', false);
};
const fwFormListaSalvar = (linha, botaoEditar, botaoSalvar) => {
    const inputLista = $$('.fw_form_lista_bloco_texto input', linha);
    inputLista.attr('readonly', 1);
    botaoEditar.classe('fw_form_lista_hide', false);
    botaoSalvar.classe('fw_form_lista_hide', true);
};

const fwFormListaAdicionarEventoItem = linha => {
    const botaoRemover = $('.fw_form_lista_remover', linha);
    const botaoEditar = $('.fw_form_lista_editar', linha);
    const botaoSalvar = $('.fw_form_lista_salvar', linha);
    const inputLista = $$('.fw_form_lista_bloco_texto input', linha);
    inputLista.evento('enter', (e, item) => {
        fwFormListaSalvar(linha, botaoEditar, botaoSalvar);
    });
    inputLista[0].evento('keydown', e => {
        if (e.shiftKey && e.key == 'Tab') {
            e.preventDefault();
            inputLista[0].blur();
        }
    });
    inputLista[2].evento('keydown', e => {
        if (e.key == 'Tab') {
            e.preventDefault();
            inputLista[2].blur();
        }
    });
    botaoEditar.evento('click', () => {
        fwFormListaEditar(linha, botaoEditar, botaoSalvar);
    });
    botaoSalvar.evento('click', () => {
        fwFormListaSalvar(linha, botaoEditar, botaoSalvar);
    });
    botaoRemover.evento('dblclick', () => {
        linha.remove();
    });
};

const fwFormListaEvento = lista => {
    if (!lista) {
        return;
    }
    const botao = $('.fw_form_lista_botao', lista);
    const conteudo = $('.fw_form_lista_conteudo', lista);
    const input = $('.form_input_traducao');
    const botaoInput = $$('input', input);
    const listaValor = $$('.fw_form_lista_item', conteudo);

    new DragDrop().bloco(conteudo).botao('.fw_form_lista_drag').item('.fw_form_lista_item').iniciar();

    for (const item of listaValor) {
        fwFormListaAdicionarEventoItem(item);
    }

    botao.evento('click', () => {
        fwFormListaAdicionar(conteudo, input);
    });
    botaoInput.evento('enter', () => {
        fwFormListaAdicionar(conteudo, input);
    });
};
const fwFormListaLoading = bloco => {
    const listaLista = $$('.fw_form_lista', bloco);

    for (const lista of listaLista) {
        fwFormListaEvento(lista);
    }
};
fwFormListaLoading(document);
