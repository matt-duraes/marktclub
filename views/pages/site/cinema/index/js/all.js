// @template "site"
// @resource "site/passo_passo"
// @resource "site/scrollBotao"

window.addEventListener('load', () => {
    const botaoScroll = document.querySelector('.botao_scroll');
    animaScroll(botaoScroll);
    window.addEventListener('scroll', function () {
        animaScroll(botaoScroll);
    });
});
