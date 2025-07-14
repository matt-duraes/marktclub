window.addEventListener('load', () => {
    const lista = $$('.com_api_loja');
    if (lista.length === 0) {
        return;
    }

    const loadingPadrao = $('.padrao_esqueleto');
    const adicionarLoading = bloco => {
        bloco.html('');
        for (i = 0; i < 3; i++) {
            const clone = loadingPadrao.clonar();
            bloco.final(clone);
            const EsqueletoItem = new Esqueleto(clone, '.esqueleto');
            EsqueletoItem.show();
        }
    };

    const modelo = $('.padrao_loja');
    for (const bloco of lista) {
        adicionarLoading(bloco);
        const remover = $$('.padrao_esqueleto');
        const hash = item.getAttribute('data-hash');
        Buscar.add(hash, 'loja', {
            bloco,
            modelo,
            removerBloco: remover,
        });
    }
});
