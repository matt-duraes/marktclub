// @template "site"
// @system "Historico"
// @system "Banner"
// @resource "site/loja/favorito"

const loadingFavoritoFaq = () => {
    const botaoFechar = $('#botao_faq_favorito_fechar');
    botaoFechar.addEventListener('click', () => {
        PaginaFavorito.fechar();
    });
};

const PaginaFavorito = new Pagina('faq-favorito', LINK + '/faq/favorito', {}, true, true, loadingFavoritoFaq);

window.addEventListener('load', () => {
    const botaoFavorito = $('#botao_favorito_tutorial');
    if (botaoFavorito) {
        botaoFavorito.addEventListener('click', () => {
            PaginaFavorito.abrir();
        });
    }
});

window.addEventListener('load', () => {
    new Historico($('#bloco_historico'), LINK + '/historico');
});

const BannerHome = new Banner({
    bloco: '#bloco_home_principal',
    elemento: 'figure',
});
