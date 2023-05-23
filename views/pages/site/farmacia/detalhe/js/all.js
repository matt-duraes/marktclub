// @template "site"
// @system "Pagina"

window.addEventListener('load', () => {
    const botaoCarteirinha = document.querySelector('.botao_abrir_carteirinha');
    const PaginaCarteirinha = new Pagina('carteirinha', LINK + '/farmacia/carteirinha');
    botaoCarteirinha.addEventListener('click', () => {
        PaginaCarteirinha.abrir();
    });
});
