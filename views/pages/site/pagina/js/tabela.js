window.addEventListener('load', () => {
    const lista = $$('.com_api_tabela');
    if (lista.length === 0) {
        return;
    }

    const criarModeloTr = bloco => {
        const modelo = $('.tr', bloco).clonar();
        modelo.classe('header', false);
        const lista = $$('.td', modelo).texto('');
        modelo.html('');
        for (const item of lista) {
            modelo.appendChild(item);
        }
        return modelo;
    };

    for (const item of lista) {
        const modelo = criarModeloTr(item);
        const hash = item.getAttribute('data-hash');
        const tabela = $('.com_tabela_conteudo', item);

        const cloneEsqueleto = modelo.clonar();
        $$('.td', cloneEsqueleto).html(`<div class="esqueleto"></div>`);
        const Loading = new Esqueleto(cloneEsqueleto, '.esqueleto');
        tabela.final(cloneEsqueleto);

        Loading.show();
        Buscar.add(hash, 'tabela', {
            esqueleto: Loading,
            modelo: modelo,
            tabela: tabela,
            removerBloco: [cloneEsqueleto],
        });
    }
});
