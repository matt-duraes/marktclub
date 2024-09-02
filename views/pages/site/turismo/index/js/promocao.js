window.addEventListener('load', async () => {
    const bloco = $('#bloco_campanha');
    const conteudo = $('#bloco_campanha .conteudo');
    const resposta = await ajaxPost(LINK + '/turismo/promocao', undefined, '');

    if (false == resposta || resposta.dado.length == 0) {
        bloco.remove();
        return;
    }
    conteudo.innerHTML = '';
    bloco.classe('parceiro_numero_' + resposta.dado.length, true);
    for (const item of resposta.dado) {
        conteudo.final(`
            <article class="campanha">
                <a href="${item.link}" target="_blank" rel="noopener noreferrer"></a>
                <figure class="desktop" style="background-image: url(${item.imagem_desktop})"></figure>
                <figure class="mobile" style="background-image: url(${item.imagem_mobile})"></figure>
                <header>
                    <h1>${item.titulo}</h1>
                    <p>${item.texto}</p>
                </header>
            </article>
        `);
    }
    conteudo.final(`<div class="article_fake"></div><div class="article_fake"></div>`);
});
