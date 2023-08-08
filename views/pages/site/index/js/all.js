// @template "site"
// @resource "site/loja/favorito"
// @resource "site/pesquisa_satisfacao/pesquisa_satisfacao"
// @resource "site/indicar_parceiro/indicar_parceiro"

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
