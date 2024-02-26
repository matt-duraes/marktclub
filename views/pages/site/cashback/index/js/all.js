// @template "site"
// @resource "site/busca"
// @system "Esqueleto"
// @resource "site/loja/parceiro"

const botaoCarregarMais = $('#botao_carregar_mais');

const blocoLoading = $('#bloco_parceiro_loading');
const tipo = 'cashback';
const blocoLista = $('#bloco_parceiro_lista');

let pagina = '';

const loading = $$('.parceiro_esqueleto');
loading.forEach(item => {
    const EsqueletoItem = new Esqueleto(item, '.esqueleto');
    EsqueletoItem.show();
});

const adicionarListaLoja = (bloco, parceiro) => {
    if (parceiro.lista.length == 0 && (pagina == 1 || pagina == '')) {
        blocoZero.classList.remove('display_none');
        return;
    }
    const listaFake = $$('.article_fake');
    listaFake.forEach(item => {
        item.remove();
    });

    parceiro.lista.forEach(item => {
        adicionarParceiro(bloco, item, tipo);
    });
    bloco.insertAdjacentHTML('beforeend', `<div class="article_fake"></div><div class="article_fake"></div>`);
};

const buscarCashback = async () => {
    if (botaoCarregarMais.classList.contains('loading')) {
        return;
    }

    botaoCarregarMais.classList.add('loading');
    blocoLoading.classList.remove('display_none');

    if (pagina != '') {
        pagina++;
    }

    const resposta = await ajaxPost(
        LINK + '/cashback/listar',
        {
            pagina: pagina,
        },
        ''
    );

    blocoLoading.classList.add('display_none');
    botaoCarregarMais.classList.remove('loading');

    if (false === resposta) {
        if (pagina == 1 || pagina == '') {
            blocoZero.classList.remove('display_none');
        }
        return;
    }

    if (tipo == 'cashback') {
        adicionarListaLoja(blocoLista, resposta.dado);
    }

    pagina = resposta.dado.paginacao.atual;
    if (resposta.dado.paginacao.total > resposta.dado.paginacao.atual) {
        botaoCarregarMais.classList.remove('display_none');
    } else {
        botaoCarregarMais.classList.add('display_none');
    }
};

botaoCarregarMais.addEventListener('click', () => {
    buscarCashback();
});
