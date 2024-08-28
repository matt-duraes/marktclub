// @template "site"
// @system "Esqueleto"

window.addEventListener('load', () => {
    const inserirBlocoSemEvento = () => {
        const containerParceiros = $('#container_parceiros_encontrados');
        const containerSemShows = $('#container_sem_shows');
        const containerBotaoEasylive = $('#bloco_easylive .bloco_botao');

        if (containerParceiros.classList.contains('display_none') == true) {
            containerSemShows.classList.remove('display_none');
            containerBotaoEasylive.classList.add('display_none');
        }
    };
    const dataBr = data => {
        const explode = data.split(' ');
        const hora = explode.length == 2 ? ' ' + explode[1] : '';
        data = explode[0];
        if (/^[0-9]{4}\-[0-9]{2}\-[0-9]{2}/.test(data)) {
            const dataExplode = data.split('-');
            data = dataExplode[2] + '/' + dataExplode[1] + '/' + dataExplode[0];
        }
        return data + hora;
    };

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

        if (resposta.dado.length == 0) {
            inserirBlocoSemEvento();
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
