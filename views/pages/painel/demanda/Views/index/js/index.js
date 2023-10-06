window.addEventListener('load', () => {
    const cloneErro = $('#clone_item_erro');
    cloneErro.removeAttribute('id');
    const cloneZero = $('#clone_item_zero');
    cloneZero.removeAttribute('id');
    const cloneItem = $('#clone_item');
    cloneItem.removeAttribute('id');

    const loading = $$('.bloco_loading');
    loading.forEach(item => {
        const EsqueletoItem = new Esqueleto(item, '.esqueleto');
        EsqueletoItem.show();
    });

    const area = $('#input_area').value;
    const listaColuna = $$('#bloco_demanda_index .bloco_coluna');
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
            adicionarItem(conteudo, resposta.dado);
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
    const adicionarItem = conteudo => {
        //
    };
});
