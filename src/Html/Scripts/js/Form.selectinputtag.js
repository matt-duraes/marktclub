const fwFormSelectInputTagLoading = bloco => {
    const lista = bloco.querySelectorAll('.fw_form_select_input_tag');
    for (const item of lista) {
        const input = $('.input_input input', item);
        const select = $('.input_select .input_select_texto', item);
        const botao = $('.fw_form_select_input_tag_botao', item);
        item.evento('click', e => {
            if (
                e.target.classList.contains('.fw_form_select_input_tag_remover') ||
                e.target.closest('.fw_form_select_input_tag_remover')
            ) {
                e.target.closest('.fw_form_select_input_tag_linha').remove();
            }
        });
        botao.evento('click', () => {
            fwFormSelectInputTagAdicionar(item);
        });
        select.evento('enter', () => {
            fwFormSelectInputTagAdicionar(item);
        });
        select.evento('formChange', () => {
            input.focus();
        });
        input.evento('enter', () => {
            fwFormSelectInputTagAdicionar(item);
            select.focus();
        });
    }
};
const fwFormSelectInputTagAdicionar = item => {
    const blocoInput = $('.input_input input', item);
    const blocoSelect = $('.input_select .input_select_value', item);
    const selectValor = $('.input_select .input_select_value', item).value;
    const selectTexto = $('.input_select .input_select_texto', item).value;
    const input = blocoInput.valor();
    const conteudo = $('.fw_form_select_input_tag_lista', item);
    if (vazio(selectValor) || vazio(input)) {
        return;
    }

    fwFormSelectInputTagAdicionarHtml(conteudo, selectValor, selectTexto, input);
    blocoSelect.valor('');
    blocoInput.value = '';
};
const fwFormSelectInputTagAdicionarHtml = (conteudo, indice, texto, valor) => {
    const html = `
        <div class="fw_form_select_input_tag_linha">
            <div class="fw_form_select_input_tag_indice" data-id="${indice}">${texto}</div>
            <div class="fw_form_select_input_tag_valor">${valor}</div>
            <i class="fw_form_select_input_tag_remover"><svg height="8" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"  xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg></i>
        </div>
    `;
    conteudo.inicio(html);
};
fwFormSelectInputTagLoading(document);

const fwFormValueSelectInputTagValor = (bloco, valor) => {
    const blocoSelectOption = $('.input_select .option', bloco);
    const blocoSelectValor = $('.input_select .input_select_value', bloco);
    const blocoInput = $('.input_input input', bloco);
    const conteudo = $('.fw_form_select_input_tag_lista', bloco);
    if (valor === '') {
        conteudo.html('');
        blocoSelectValor.valor('');
        blocoInput.valor('');
        return;
    }
    blocoSelectValor.valor('');
    blocoInput.valor('');
    for (const indice in valor) {
        let texto = '';
        try {
            texto = blocoSelectOption.querySelector('li[data-value="' + indice + '"]').texto();
        } catch (error) {}
        fwFormSelectInputTagAdicionarHtml(conteudo, indice, texto, valor[indice] || '');
    }
};
