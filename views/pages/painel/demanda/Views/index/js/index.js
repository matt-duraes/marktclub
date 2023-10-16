const primeiraColuna = listaColuna[0];

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
                coluna.querySelector('header h1 span').innerText = `(0)`;
                adicionarBlocoZero(conteudo);
                continue;
            }
            adicionarBlocoZero(conteudo, 'display_none');
            for (const item of resposta.dado) {
                await adicionarNovaDemanda(conteudo, item);
            }
            contarTarefaDemanda(coluna);
            // adicionarDragDrop();
        }
    };
    buscarDados();

    const adicionarBlocoErro = conteudo => {
        const clone = cloneErro.cloneNode(true);
        conteudo.prepend(clone);
    };
    const adicionarBlocoZero = (conteudo, classe) => {
        const clone = cloneZero.cloneNode(true);
        if (classe !== undefined && classe != '') {
            clone.classList.add(classe);
        }
        conteudo.prepend(clone);
    };
});
