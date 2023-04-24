// @template "site"
// @system "Pagina"

window.addEventListener('load', () => {
    const PaginaCarteirinha = new Pagina('carteirinha', `${LINK}/medicamento/carteirinha`);
    const botaoCarteirinha = document.querySelector('.botao_abrir_carteirinha');
    botaoCarteirinha.addEventListener('click', () => {
        PaginaCarteirinha.abrir();
    });
});
