window.addEventListener('load', () => {
    const botaoPesquisa = document.getElementById('abrePesquisaSatisfacao');
    const paginaPesquisa = new Pagina('Pesquisa de satisfação', LINK + '/pesquisa-de-satisfacao');

    botaoPesquisa.addEventListener('click', () => {
        paginaPesquisa.abrir();
    });
});
