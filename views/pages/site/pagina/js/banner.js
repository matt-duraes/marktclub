const adicionarNovoBanner = banner => {
    banner.classe('banner_loading', true);
    const hash = banner.attr('data-hash');
    const setaAnterior = $('.seta.anterior', banner);
    const setaProximo = $('.seta.proximo', banner);
    const figurePadrao = $('.com_banner_figure_padrao', banner);
    const conteudoDesktop = $('.banner_desktop', banner);
    const conteudoMobile = $('.banner_mobile', banner);
    const aparecer = $$('.imagem, .seta');

    const EsqueletoItem = new Esqueleto(banner, '.conteudo');
    EsqueletoItem.show();
    Buscar.add(hash);

    // const body = new FormData();
    // body.append('hash', hash);
    // body.append('tipo', 'banner');

    // fetch(LINK + '/componente', {
    //     method: 'POST',
    //     body,
    // })
    //     .then(resposta => {
    //         resposta
    //             .json()
    //             .then(resposta => {
    //                 adicionarImagemBanner(resposta);
    //             })
    //             .catch(erro => {
    //                 //
    //             });
    //     })
    //     .catch(erro => {
    //         //
    //     });

    const adicionarImagemBanner = lista => {
        const quantidade = lista.length;
        if (quantidade === 0) {
            banner.remove();
            return;
        }
        aparecer.aparecer();

        EsqueletoItem.hide();
        banner.classe('banner_loading', false);

        for (const item of lista) {
            const cloneDesktop = figurePadrao.clonar();
            const cloneMobile = figurePadrao.clonar();

            const blocoLinkDesktop = $('a', cloneDesktop);
            const blocoLinkMobile = $('a', cloneMobile);
            if (!vazio(item.link)) {
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
        adicionarPluginBanner();
    };
    const adicionarPluginBanner = () => {
        const quantidadeDesktop = $$('figure', conteudoDesktop).length;
        const quantidadeMobile = $$('figure', conteudoMobile).length;
        if (quantidadeDesktop > 0) {
            new Banner(conteudoDesktop, 'figure', setaProximo, setaAnterior);
        }
        if (quantidadeMobile > 0) {
            new Banner(conteudoMobile, 'figure');
        }
    };
};

window.addEventListener('load', () => {
    const bannerLista = $$('.com_api_banner');
    if (!bannerLista) {
        return;
    }
    for (const banner of bannerLista) {
        adicionarNovoBanner(banner);
    }
});
