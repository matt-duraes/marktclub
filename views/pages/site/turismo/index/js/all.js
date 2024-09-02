// @template "site"
// @system "Esqueleto"
// @system "Banner"
// @resource "site/loja/parceiro"
// @resource "site/loja/favorito"
// @import "hotel"
// @import "promocao"

window.addEventListener('load', () => {
    const bannerDesktop = $('#bloco_banner_desktop');
    const bannerMobile = $('#bloco_banner_mobile');
    if (bannerDesktop) {
        new Banner(bannerDesktop, 'figure', $('#botao_banner_proximo'), $('#botao_banner_anterior'));
    }
    if (bannerMobile) {
        new Banner(bannerMobile, 'figure');
    }

    const loading = $$('.parceiro_esqueleto');
    loading.forEach(item => {
        const EsqueletoItem = new Esqueleto(item, '.esqueleto');
        EsqueletoItem.show();
    });
});
