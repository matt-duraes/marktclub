window.addEventListener('load', () => {
    const banner = $('#bloco_banner');
    const executarResultado = resposta => {
        if (resposta.dado.banner.quantidade > 0) {
            montarBannerHome(banner, resposta.dado.banner.lista, resposta.dado.banner.quantidade);
        } else {
            banner.remove();
        }
        montarContadorHome(resposta.dado.loja, resposta.dado.parceiro);
    };

    const buscarHome = async () => {
        const EsqueletoItem = new Esqueleto(banner, '.esqueleto');
        EsqueletoItem.show();

        const resposta = await ajaxGet(LINK + '/login/buscar', undefined, '');

        EsqueletoItem.hide();
        if (false === resposta) {
            banner.remove();
        }
        executarResultado(resposta);
    };

    if (banner.classe('banner_loading', '?')) {
        buscarHome();
    } else {
        $$('figure', banner).bgImagem();
    }
});
