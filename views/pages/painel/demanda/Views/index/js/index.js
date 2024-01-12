const primeiraColuna = listaColuna[0];

window.addEventListener('load', () => {
    buscarDados();
});

async function buscarDados(filtros) {
    const loading = $$('.bloco_loading');
    const conteudos = $$('.bloco_coluna .conteudo article');

    conteudos.forEach(conteudo => {
        if(conteudo.id) {
            conteudo.remove();
        }
    });

    loading.forEach(item => {
        const EsqueletoItem = new Esqueleto(item, '.esqueleto');
        EsqueletoItem.show();
    });

    for (const coluna of listaColuna) {
        const status = coluna.getAttribute('data-status');

        if(status != 'nova' && status != 'bloqueada') {
            return
        }

        ajaxPost(
            LINK + '/demanda/listar',
            {
                area,
                status,
                ...filtros
            },
            ''
        ).then(resposta => {
            const conteudo = coluna.querySelector('.conteudo');
            const loading = coluna.querySelector('.bloco_loading');
            loading.classList.add('display_none');

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

const cloneErro = $('#clone_item_erro');
cloneErro.removeAttribute('id');
const cloneZero = $('#clone_item_zero');
cloneZero.removeAttribute('id');

const adicionarBlocoErro = conteudo => {
    const clone = cloneErro.cloneNode(true);
    conteudo.prepend(clone);
};
const adicionarBlocoZero = (conteudo, classe) => {
    const zero = conteudo.querySelector('.tarefa_zero');

    if (zero && classe == 'display_none') {
        return zero.classList.add(classe);
    }
    if (zero) {
        return zero.classList.remove('display_none');
    }

    const clone = cloneZero.cloneNode(true);
    if (classe !== undefined && classe != '') {
        clone.classList.add(classe);
    }
    conteudo.prepend(clone);
};
