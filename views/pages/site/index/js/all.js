// @template "site"
// @resource "site/loja/favorito"
// @resource "site/pesquisa_satisfacao/pesquisa_satisfacao"
// @resource "site/indicar_parceiro/indicar_parceiro"

window.addEventListener('load', () => {
    const botaoFavorito = $('#botao_favorito_tutorial');
    if (botaoFavorito) {
        const PaginaFavorito = new Pagina('faq-favorito', LINK + '/faq/favorito');
        botaoFavorito.addEventListener('click', () => {
            PaginaFavorito.abrir();
        });
    }
});
