window.addEventListener('load', () => {
    const banner = $('#bloco_banner');
    const id = banner.attr('data-id');
    if (!banner) {
        return;
    }

    const EsqueletoItem = new Esqueleto(banner, '.conteudo');
    EsqueletoItem.show();

    const buscarBanner = async () => {
        const resposta = await ajaxPost(
            LINK + '/componente',
            {
                id,
                url: paginaUrl,
            },
            ''
        );
        if (false === resposta) {
            EsqueletoItem.hide();
            banner.remove();
            return;
        }
    };
    buscarBanner();
});
