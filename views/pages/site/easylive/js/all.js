// @template "site"
// @system "Esqueleto"
// @system "Data"

window.addEventListener('load', () => {
    const blocoMais = $('#bloco_botao_mais');
    const blocoClone = $('#parceiro_padrao_loja');
    if (blocoClone) {
        blocoClone.removeAttribute('id');
        blocoClone.querySelector('.bloco_favorito').remove();
        const cloneLink = blocoClone.querySelector('.item_link');
        cloneLink.setAttribute('target', '_blank');
        cloneLink.setAttribute('rel', 'noopener noreferrer');
    }

    const loading = $$('.parceiro_esqueleto');
    loading.forEach(item => {
        const EsqueletoItem = new Esqueleto(item, '.esqueleto');
        EsqueletoItem.show();
    });

    const blocoLoading = $('#bloco_parceiro_loading');
    const blocoLista = $('#bloco_parceiro_lista');
    const tipo = $('#bloco_easylive_tipo').value;
    const buscarParceiro = async () => {
        blocoLoading.classList.remove('display_none');
        const resposta = await ajaxPost(
            LINK + '/easylive/listar',
            {
                tipo,
            },
            ''
        );

        blocoLoading.classList.add('display_none');
        if (false === resposta) {
            return;
        }
        if (resposta.dado.length > 0) {
            blocoMais.classList.remove('display_none');
            blocoMais.setAttribute('href', resposta.dado[0].link);
        }
        resposta.dado.forEach(item => {
            adicionarParceiro(blocoLista, item);
        });
        blocoLista.insertAdjacentHTML('beforeend', `<div class="article_fake"></div><div class="article_fake"></div>`);
    };
    buscarParceiro();

    const adicionarParceiro = (bloco, item) => {
        if (!blocoClone) {
            return;
        }
        const clone = blocoClone.cloneNode(true);
        clone.querySelector('.item_link').setAttribute('href', item.link);
        clone.querySelector('.item_logo').innerHTML = `<img src="${item.imagem}">`;
        clone.querySelector('.item_titulo').innerText = item.titulo;
        clone.querySelector('.item_desconto').innerText = 'Validade: ' + dataBr(item.data_validade);

        bloco.appendChild(clone);
    };
});
