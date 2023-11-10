// @template "site"
// @system "Historico"
// @system "Banner"
// @system "Esqueleto"
// @system "Popup"
// @resource "site/loja/busca"
// @resource "site/busca"
// @resource "site/loja/favorito"
// @resource "site/loja/parceiro"

const bannerDesktop = $('#bloco_banner_desktop');
const bannerMobile = $('#bloco_banner_desktop');
if (bannerDesktop) {
    new Banner(bannerDesktop, 'figure', $('#botao_banner_proximo'), $('#botao_banner_anterior'));
}
if (bannerMobile) {
    new Banner(bannerMobile, 'figure');
}

const loadingFavoritoFaq = () => {
    const botaoFechar = $('#botao_faq_favorito_fechar');
    botaoFechar.addEventListener('click', () => {
        PaginaFavorito.fechar();
    });
};

const PaginaFavorito = new Pagina('faq-favorito', LINK + '/faq/favorito', {}, true, true, loadingFavoritoFaq);

window.addEventListener('load', () => {
    const botaoFavorito = $('#botao_favorito_tutorial');
    if (botaoFavorito) {
        botaoFavorito.addEventListener('click', () => {
            PaginaFavorito.abrir();
        });
    }

    new Historico($('#bloco_historico'), LINK + '/historico');

    const loading = $$('.parceiro_esqueleto');
    loading.forEach(item => {
        const EsqueletoItem = new Esqueleto(item, '.esqueleto');
        EsqueletoItem.show();
    });

    const blocoParceiroAcessado = $('#bloco_parceiro_acessado');
    const blocoParceiroNovo = $('#bloco_parceiro_novo');
    const blocoParceiroFavorito = $('#bloco_parceiro_favorito');
    const blocoFaq = $('#bloco_favorito_faq');
    const buscarParceiroHome = async () => {
        const resposta = await ajaxPost(LINK + '/home/buscar', undefined, '');
        if (false === resposta) {
            return;
        }
        if (resposta.dado.novo.length > 0) {
            adicionarListaParceiro(blocoParceiroNovo, resposta.dado.novo);
        } else {
            blocoParceiroNovo.closest('.bloco').remove();
        }
        if (resposta.dado.acessado.length > 0) {
            adicionarListaParceiro(blocoParceiroAcessado, resposta.dado.acessado);
        } else {
            blocoParceiroAcessado.closest('.bloco').remove();
        }
        if (resposta.dado.favorito.length > 0) {
            adicionarListaParceiro(blocoParceiroFavorito, resposta.dado.favorito);
        } else {
            blocoFaq.classList.remove('display_none');
            blocoParceiroFavorito.innerHTML = '';
            blocoParceiroFavorito.closest('.bloco').classList.add('display_none');
        }
    };
    buscarParceiroHome();

    const adicionarListaParceiro = (bloco, lista) => {
        bloco.innerHTML = '';
        lista.forEach(item => {
            adicionarParceiro(bloco, item);
        });
        bloco.insertAdjacentHTML('beforeend', `<div class="article_fake"></div><div class="article_fake"></div>`);
    };
});
