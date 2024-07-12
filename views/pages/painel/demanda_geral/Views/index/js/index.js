window.addEventListener('load', () => {
    buscarDados();
});

if (loadingLista.length > 0) {
    for (const item of loadingLista) {
        const EsqueletoLista = new Esqueleto(item, '.esqueleto');
        EsqueletoLista.show();
    }
}

async function buscarDados() {
    const loading = $$('.bloco_loading');
    const conteudos = $$('.bloco_coluna .conteudo article, .bloco_coluna .conteudo div');

    conteudos.forEach(conteudo => {
        if (!conteudo.classList.contains('bloco_loading')) {
            conteudo.remove();
        }
    });

    loading.forEach(item => {
        const EsqueletoItem = new Esqueleto(item, '.esqueleto');
        EsqueletoItem.show();
    });

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
            if (coluna.classe('bloco_visualizar_quadro', '?')) {
                carregarBuscarQuadro(coluna, resposta);
                return;
            }
            carregarBuscarListar(coluna, resposta);
        });
    }

    const carregarBuscarListar = (coluna, resposta) => {
        loadingLista.sumir();
        if (false === resposta) {
            adicionarBlocoErro(coluna);
            return;
        }
        if (resposta.dado.length == 0) {
            buscarSprintAtiva(false);
            adicionarBlocoZero(coluna);
            return;
        }
        buscarSprintAtiva(true);
        adicionarBlocoZero(coluna, 'display_none');
        for (const item of resposta.dado) {
            adicionarNovaDemanda(coluna, item);
        }
    };

    const carregarBuscarQuadro = (coluna, resposta) => {
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
    };
}

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
    conteudo.final(clone);
};
