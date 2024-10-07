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
    const demandas = $('#total_demandas');

    conteudos.forEach(conteudo => {
        if (!conteudo.classList.contains('bloco_loading')) {
            conteudo.remove();
        }
    });

    loading.forEach(item => {
        const EsqueletoItem = new Esqueleto(item, '.esqueleto');
        EsqueletoItem.show();
    });

    const eQuadro = listaColuna[0].classe('bloco_visualizar_quadro', '?');
    if (eQuadro && area == 'tecnologia') {
        const resposta = await ajaxPost(LINK + '/demanda-sprint/ativa', {}, '');
        if (false !== resposta) {
            idSprint = resposta.dado.id;
        }
    }

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
            adicionarTotalDemandas(resposta.dado.length);
            if (eQuadro) {
                carregarBuscarQuadro(coluna, resposta);
                return;
            }
            carregarBuscarListar(coluna, resposta);
        });
    }

    const adicionarTotalDemandas = totalDemandas => {
        Loading.show();
        demandas.innerText = `Total de demandas: ${totalDemandas}`;
        demandas.classList.remove('display_none');
        Loading.hide();
    };

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

    const pegarDemandaForaSprint = () => {
        let containerDemandas = document.querySelector('#bloco_app_lista_detalhe');

        let demandasForaSprint = Array.from(containerDemandas.querySelectorAll('.linha.bloco_kambam_item')).filter(
            elemento => {
                return elemento.classList.length === 2;
            }
        );
        adicionarTotalDemandas(demandasForaSprint.length);
    };

    const pegarDemandaNaSprint = () => {
        let containerDemandas = document.querySelector('#bloco_app_lista_detalhe');
        let demandasNaSprint = containerDemandas.querySelectorAll('.bloco_kambam_item.na_sprint');
        adicionarTotalDemandas(demandasNaSprint.length);
    };

    demandas.addEventListener('click', () => {
        ajaxPost(
            LINK + '/demanda/listar',
            {
                area: 'tecnologia',
                status: 'geral',
            },
            ''
        ).then(resposta => {
            let respostaDemandas = resposta.dado.length;
            // classe que os elementos possuem 'na_sprint'
            const botaoNaSprint = $('#botao_na_sprint');
            const botaoForaSprint = $('#botao_fora_sprint');
            if (botaoForaSprint.classList.contains('hover') == true) {
                pegarDemandaForaSprint();
                return;
            }
            if (botaoNaSprint.classList.contains('hover') == true) {
                pegarDemandaNaSprint();
                return;
            }
            adicionarTotalDemandas(respostaDemandas);
        });
    });
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
