// @template "site"
// @system "Pagina"
cupomPagina = {};
window.addEventListener('load', () => {
    const botaoCarteirinha = document.querySelector('.botao_abrir_carteirinha');
    const url = '1';

    if (!botaoCarteirinha) {
        return;
    }

    cupomPagina[url] = new Pagina('cupom - ' + url, LINK + '/farmacia/carteirinha/' + url, {}, true, true);

    botaoCarteirinha.addEventListener('click', () => {
        cupomPagina[url].abrir();
    });
});
