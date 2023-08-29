window.addEventListener('load', () => {
    const botaoAbrirAjuda = document.getElementById('botao_abrir_ajuda');
    if (!botaoAbrirAjuda) {
        return;
    }
    const paginaAjuda = new Pagina('Ajuda', LINK + '/ajuda');

    botaoAbrirAjuda.addEventListener('click', () => {
        paginaAjuda.abrir();
    });
});
