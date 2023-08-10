window.addEventListener('load', () => {
    const listaTeste = document.querySelectorAll('.input_teste');
    const botaoMarcar = document.querySelector('#id_marcar');
    botaoMarcar.addEventListener('change', () => {
        const valor = botaoMarcar.checked;
        listaTeste.forEach(item => {
            item.checked = valor;
        });
    });

    listaTeste.forEach(item => {
        item.addEventListener('change', () => {
            const quantidade = document.querySelectorAll('.input_teste:checked').length;
            if (quantidade == listaTeste.length) {
                botaoMarcar.checked = true;
            } else {
                botaoMarcar.checked = false;
            }
        });
    });

    const botaoBuscar = document.querySelector('#botao_download');
    botaoBuscar.addEventListener('click', () => {
        const marcado = document.querySelectorAll('.input_teste:checked');
        if (marcado.length == 0) {
            return;
        }

        document.querySelector('#bloco_loading').classList.add('mostrar');

        let teste = [];
        marcado.forEach(item => {
            teste.push(item.value);
        });

        window.location.assign(LINK + '/__tests/' + teste.join(','));
    });
});
