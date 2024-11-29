const montarBannerHome = (banner, lista, quantidade) => {
    banner.classe('banner_loading', false);
    banner.classe('banner_' + quantidade);

    let i = 0;
    let figure = '';
    for (; i < quantidade; ++i) {
        figure += `<figure data-bgimagem="${lista[i]}"></figure>`;
    }
    banner.inicio(figure);
    $$('figure', banner).bgImagem();
};
