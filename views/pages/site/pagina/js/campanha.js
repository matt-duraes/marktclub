const comCampanha = async bloco => {
    const padrao = $('.com_campanha_padrao', bloco);
    const conteudo = $('.conteudo', bloco);

    const EsqueletoItem = new Esqueleto(bloco, '.esqueleto');
    EsqueletoItem.show();

    const resposta = await ajaxPost(
        LINK + '/componente',
        {
            id: bloco.attr('data-id'),
            url: paginaUrl,
            campo: ['imagem_desktop', 'imagem_mobile', 'titulo', 'texto', 'link'],
        },
        ''
    );

    if (false == resposta || resposta.dado.lista.length == 0) {
        bloco.remove();
        return;
    }
    EsqueletoItem.hide();

    conteudo.innerHTML = '';
    bloco.classe('com_campanha_numero_' + resposta.dado.lista.length, true);
    for (const item of resposta.dado.lista) {
        const clone = padrao.clonar();

        const figureDesktop = $('figure.desktop', clone);
        figureDesktop.aparecer();
        figureDesktop.css('background-image', 'url(' + item.imagem_desktop + ')');

        const imagemDesktop = !vazio(item.imagem_mobile) ? item.imagem_mobile : item.imagem_desktop;
        const figureMobile = $('figure.mobile', clone);
        figureMobile.aparecer();
        figureMobile.css('background-image', 'url(' + imagemDesktop + ')');

        if (!vazio(item.titulo)) {
            const titulo = $('header h1', clone);
            titulo.aparecer();
            $('.lang_br', titulo).texto(item.titulo);
        }
        if (!vazio(item.texto)) {
            const texto = $('header p', clone);
            texto.aparecer();
            $('.lang_br', texto).texto(item.texto);
        }
        $('a', clone).attr('href', item.link);
        conteudo.final(clone);
    }
    conteudo.final(`<div class="article_fake"></div><div class="article_fake"></div>`);
};

window.addEventListener('load', () => {
    const lista = $$('.com_campanha');
    for (const bloco of lista) {
        comCampanha(bloco);
    }
});
