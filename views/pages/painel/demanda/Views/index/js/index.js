const listaColuna = $$('#bloco_demanda_index .bloco_coluna');
const primeiraColuna = listaColuna[0];

const cloneItem = $('#clone_item');
cloneItem.removeAttribute('id');

const adicionarItem = (bloco, item, abrir) => {
    const clone = cloneItem.cloneNode(true);
    clone.querySelector('.titulo').innerText = item.titulo;
    clone.querySelector('.data_criacao').innerText = item.data_criacao;
    const dataEntrega = item.data_entrega;
    if (dataEntrega != '') {
        clone.querySelector('.bloco_entrega').classList.remove('display_none');
        clone.querySelector('.data_entrega').innerText = dataEntrega;
    }
    bloco.insertBefore(clone, bloco.firstChild);
    const id = item.id;
    const PaginaDemanda = new Pagina(
        'demanda-' + id,
        LINK + '/demanda/demanda/' + id,
        undefined,
        true,
        true,
        demandaDetalhe
    );
    if (abrir === true) {
        PaginaDemanda.abrir();
    }
    clone.addEventListener('click', () => {
        PaginaDemanda.abrir();
    });
};
window.addEventListener('load', () => {
    const cloneErro = $('#clone_item_erro');
    cloneErro.removeAttribute('id');
    const cloneZero = $('#clone_item_zero');
    cloneZero.removeAttribute('id');

    const loading = $$('.bloco_loading');
    loading.forEach(item => {
        const EsqueletoItem = new Esqueleto(item, '.esqueleto');
        EsqueletoItem.show();
    });

    const area = $('#input_area').value;
    const buscarDados = async () => {
        for (const coluna of listaColuna) {
            const status = coluna.getAttribute('data-status');
            const resposta = await ajaxPost(
                LINK + '/demanda/listar',
                {
                    area,
                    status,
                },
                ''
            );
            const conteudo = coluna.querySelector('.conteudo');
            const loading = coluna.querySelector('.bloco_loading');
            loading.remove();
            if (false === resposta) {
                adicionarBlocoErro(conteudo);
                continue;
            }

            if (resposta.dado.length == 0) {
                adicionarBlocoZero(conteudo);
                continue;
            }
            for (const item of resposta.dado) {
                adicionarItem(conteudo, item);
            }
        }
    };
    buscarDados();

    const adicionarBlocoErro = conteudo => {
        const clone = cloneErro.cloneNode(true);
        conteudo.prepend(clone);
    };
    const adicionarBlocoZero = conteudo => {
        const clone = cloneZero.cloneNode(true);
        conteudo.prepend(clone);
    };
});
