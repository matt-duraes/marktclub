window.addEventListener('load', () => {
    const banner = $('#bloco_banner');
    if (!banner) {
        return;
    }
    const id = banner.attr('data-id');
    const figurePadrao = $('#com_banner_figure_padrao');
    const conteudoDesktop = $('#bloco_banner_desktop');
    const conteudoMobile = $('#bloco_banner_mobile');
    const aparecer = $$('.seta, .imagem', banner);

    const EsqueletoItem = new Esqueleto(banner, '.conteudo');
    EsqueletoItem.show();

    const buscarBanner = async () => {
        const resposta = await ajaxPost(
            LINK + '/componente',
            {
                id,
                url: paginaUrl,
                campo: ['imagem_desktop', 'imagem_mobile', 'tipo', 'link'],
            },
            ''
        );
        EsqueletoItem.hide();
        banner.classe('banner_loading', false);
        if (false == resposta || resposta.dado.lista.length == 0) {
            banner.remove();
            return;
        }
        aparecer.aparecer();
        adicionarImagemBanner(resposta.dado.lista);
        adicionarPluginBanner();
    };
    buscarBanner();

    const adicionarImagemBanner = lista => {
        for (const item of lista) {
            const cloneDesktop = figurePadrao.clonar();
            const cloneMobile = figurePadrao.clonar();

            const blocoLinkDesktop = $('a', cloneDesktop);
            const blocoLinkMobile = $('a', cloneMobile);
            if (item.tipo == 'home') {
                blocoLinkDesktop.attr('href', item.link);
                blocoLinkMobile.attr('href', item.link);
                if ('target' in item) {
                    blocoLinkDesktop.attr('target', item.target);
                    blocoLinkMobile.attr('target', item.target);
                }
                if ('rel' in item) {
                    blocoLinkDesktop.attr('rel', item.rel);
                    blocoLinkMobile.attr('rel', item.rel);
                }
            } else {
                blocoLinkDesktop.remove();
                blocoLinkMobile.remove();
            }
            if (!vazio(item.imagem_desktop)) {
                $('img', cloneDesktop).attr('src', item.imagem_desktop);
                conteudoDesktop.final(cloneDesktop);
            }
            if (!vazio(item.imagem_mobile)) {
                $('img', cloneMobile).attr('src', item.imagem_mobile);
                conteudoMobile.final(cloneMobile);
            }
        }
    };
    const adicionarPluginBanner = () => {
        const quantidadeDesktop = $$('figure', conteudoDesktop).length;
        const quantidadeMobile = $$('figure', conteudoMobile).length;
        if (quantidadeDesktop > 0) {
            new Banner(conteudoDesktop, 'figure', $('#botao_banner_proximo'), $('#botao_banner_anterior'));
        }
        if (quantidadeMobile > 0) {
            new Banner(conteudoMobile, 'figure');
        }
    };
});
