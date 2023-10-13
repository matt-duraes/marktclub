window.addEventListener('load', async () => {
    const loading = $$('.parceiro_esqueleto');
    loading.forEach(item => {
        const EsqueletoItem = new Esqueleto(item, '.esqueleto');
        EsqueletoItem.show();
    });

    const bloco = $('#bloco_parceiro_relacionado');
    const id = $('#input_loja_id').value;
    const resposta = await ajaxPost(LINK + '/convenios/relacionado', { id }, '');
    if (false == resposta) {
        $('.bloco_relacionado').remove();
        return;
    }
    bloco.innerHTML = '';
    resposta.dado.forEach(item => {
        adicionarParceiro(bloco, item);
    });
    bloco.insertAdjacentHTML('beforeend', `<div class="article_fake"></div><div class="article_fake"></div>`);
});
