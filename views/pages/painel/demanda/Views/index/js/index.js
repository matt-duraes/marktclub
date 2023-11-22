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

    const buscarDados = async () => {
        for (const coluna of listaColuna) {
            const status = coluna.getAttribute('data-status');
            ajaxPost(
                LINK + '/demanda/listar',
                {
                    area,
                    status,
                },
                ''
            ).then(resposta => {
                const conteudo = coluna.querySelector('.conteudo');
                const loading = coluna.querySelector('.bloco_loading');
                loading.remove();
                if (false === resposta) {
                    adicionarBlocoErro(conteudo);
                    return;
                }

                if (resposta.dado.length == 0) {
                    coluna.querySelector('header h1 span').innerText = `(0)`;
                    adicionarBlocoZero(conteudo);
                    return;
                }
                adicionarBlocoZero(conteudo, 'display_none');
                for (const item of resposta.dado) {
                    adicionarNovaDemanda(conteudo, item);
                }
                contarTarefaDemanda(coluna);
            });
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
