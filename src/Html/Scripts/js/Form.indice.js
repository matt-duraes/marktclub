const fwFormIndiceValorPadrao = $('.fw_form_indice_valor_linha_padrao');
if (fwFormIndiceValorPadrao) {
    fwFormIndiceValorPadrao.classe('fwFormIndiceValorPadrao', false);
}
fwFormIndiceValorPegarValor = input => {
    const lista = $$('.fw_form_indice_valor_lista .fw_form_indice_valor_linha', input);
    let retorno = {};
    lista.forEach((item, i) => {
        retorno[i] = {
            indice: $('.fw_form_indice_valor_indice', item).texto(),
            valor: $('.fw_form_indice_valor_valor', item).texto(),
        };
    });
    return retorno;
};
fwFormIndiceValorSetarValor = (input, valor) => {
    if (typeof valor !== 'object' || !input) {
        return;
    }
    const quantidade = Object.keys(valor).length;
    if (quantidade == 0) {
        return;
    }
    const conteudo = $('.fw_form_indice_valor_lista', input);
    let i = 0;
    for (; i < quantidade; ++i) {
        const item = valor[i];
        const clone = fwFormIndiceValorPadrao.clonar();
        $('.fw_form_indice_valor_indice', clone).texto(item.indice);
        $('.fw_form_indice_valor_valor', clone).texto(item.valor);
        conteudo.final(clone);
    }
};

window.addEventListener('load', () => {
    const listaIndiceValor = document.querySelectorAll('.fw_form_indice_valor');
    listaIndiceValor.forEach(item => {
        const bloco = item.querySelector('.fw_form_indice_valor_lista');
        const padrao = item.querySelector('.fw_form_indice_valor_linha_padrao');
        const blocoLista = item.querySelector('.fw_form_indice_valor_lista');
        const listaInput = item.querySelectorAll('.input_separador_1, .input_separador_3');
        const inputIndice = item.querySelector('.input_separador_1');
        const inputValor = item.querySelector('.input_separador_3');
        const botao = item.querySelector('.fw_form_indice_valor_botao');

        new DragDrop().bloco(bloco).item('.fw_form_indice_valor_linha').botao('.fw_form_indice_valor_ordem').iniciar();
        bloco.addEventListener('click', e => {
            const target = e.target;
            if (
                target.classList.contains('fw_form_indice_valor_remover') ||
                target.closest('.fw_form_indice_valor_remover')
            ) {
                target.closest('.fw_form_indice_valor_linha').remove();
            }
        });
        const salvarNovaLinha = () => {
            const indice = inputIndice.value;
            const valor = inputValor.value;
            if (indice == '') {
                Alerta.notificacao('Você deve escrever um indice para continuar.', false);
                return;
            } else if (valor == '') {
                Alerta.notificacao('Você deve escrever um valor para continuar.', false);
                return;
            }
            inputIndice.value = '';
            inputValor.value = '';
            inputIndice.focus();
            const clone = padrao.clonar();
            clone.querySelector('.fw_form_indice_valor_indice').innerText = indice;
            clone.querySelector('.fw_form_indice_valor_valor').innerText = valor;
            blocoLista.final(clone);
        };
        botao.addEventListener('click', () => {
            salvarNovaLinha();
        });
        listaInput.forEach(input => {
            input.addEventListener('keydown', e => {
                if (e.key == 'Enter') {
                    salvarNovaLinha();
                }
            });
        });
    });
});
