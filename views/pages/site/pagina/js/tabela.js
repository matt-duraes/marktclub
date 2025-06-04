window.addEventListener('load', () => {
    const lista = $$('.com_api_tabela');
    if (lista.length === 0) {
        return;
    }

    const criarModeloTr = bloco => {
        const modelo = $('.tr', bloco).clonar();
        modelo.classe('header', false);
        const lista = $$('.td', modelo).texto('');
        modelo.html('');
        for (const item of lista) {
            modelo.appendChild(item);
        }
        return modelo;
    };

    for (const item of lista) {
        const modelo = criarModeloTr(item);
        const hash = item.getAttribute('data-hash');
        const tabela = $('.com_tabela_conteudo', item);

        const cloneEsqueleto = modelo.clonar();
        $$('.td', cloneEsqueleto).html(`<div class="esqueleto"></div>`);
        const Loading = new Esqueleto(cloneEsqueleto, '.esqueleto');
        tabela.final(cloneEsqueleto);

        Loading.show();
        const body = new FormData();
        body.append('hash', hash);
        fetch(LINK + '/componente', {
            method: 'POST',
            body,
        })
            .then(resposta => {
                const json = resposta.json();
                json.then(resposta => {
                    cloneEsqueleto.remove();
                    adicionarListaTabela(resposta, modelo, tabela);
                }).catch(erro => {
                    cloneEsqueleto.remove();
                    erroBuscarTabela(erro);
                });
            })
            .catch(erro => {
                cloneEsqueleto.remove();
                erroBuscarTabela(erro);
            });
    }

    const adicionarListaTabela = (lista, modelo, tabela) => {
        for (const linha of lista) {
            const tr = modelo.clonar();
            const td = $$('.td', tr);
            linha.forEach((valor, i) => {
                td[i].texto(valor);
            });
            tabela.final(tr);
        }
    };

    const erroBuscarTabela = bloco => {
        bloco.final(`<div class="com_pai_tabela_erro">Ocorreu um erro ao buscar lista da tabela</div>`);
    };
});
