window.addEventListener('load', () => {
    const lista = $$('.com_api_banner');
    if (lista.length === 0) {
        return;
    }

    const padrao = $('.com_banner_figure_padrao');
    padrao.classList.remove('com_banner_figure_padrao');
    for (const banner of lista) {
        const hash = banner.getAttribute('data-hash');
        Buscar.add(hash, 'banner', {
            banner,
            padrao,
        });
    }
});
