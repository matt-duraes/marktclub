// @template "site"
// @resource "site/loja/favorito"
// @resource "site/pesquisa_satisfacao/pesquisa_satisfacao"
// @resource "site/indicar_parceiro/indicar_parceiro"

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
});

window.addEventListener('load', () => {
    const blocoFavorito = $('#bloco_favorito');
    const blocoFavoritoLista = blocoFavorito.querySelector('.bloco_parceiro');
    const blocoFavoritoFaq = $('#bloco_favorito_faq');
    const botaoFavorito = $$('.botao_favorito');
    botaoFavorito.forEach(botao => {
        botao.addEventListener('click', () => {
            executarFavorito(botao);
        });
    });

    const executarFavorito = botao => {
        if (botao.classList.contains('loading')) {
            return;
        }
        botao.classList.add('loading');
        const bloco = botao.closest('.parceiro');
        const acao = botao.classList.contains('favorito_marcado') ? 'desmarcar' : 'marcar';
        const id = bloco.getAttribute('data-url');

        if (acao == 'marcar') {
            salvarFavorito(botao, id);
            return;
        }
        deletaFavorito(botao, id);
    };
    const salvarFavorito = async (botao, id) => {
        botao.classList.add('favorito_marcado');
        const resposta = await ajaxPost(LINK + '/convenios/favorito', { id }, '');
        botao.classList.remove('loading');
        if (false === resposta) {
            botao.classList.remove('favorito_marcado');
            Alerta.notificacao('Erro ao salvar favorito, por favor, tente novamente.', false);
            return;
        }
        adicionarBlocoFavorito(botao.closest('.parceiro'), id);
    };
    const deletaFavorito = async (botao, id) => {
        botao.classList.remove('favorito_marcado');
        const resposta = await ajaxDelete(LINK + '/convenios/favorito/' + id, {}, '');
        botao.classList.remove('loading');
        if (false === resposta) {
            botao.classList.add('favorito_marcado');
            Alerta.notificacao('Erro ao deletar favorito, por favor, tente novamente.', false);
            return;
        }
        removerBlocoFavorito(id);
    };
    const adicionarBlocoFavorito = (bloco, id) => {
        if (
            !blocoFavorito ||
            blocoFavorito.querySelectorAll('.parceiro').length >= 3 ||
            blocoFavorito.querySelector('.parceiro[data-url="' + id + '"]')
        ) {
            return;
        }
        if (blocoFavorito.classList.contains('display_none')) {
            blocoFavorito.classList.remove('display_none');
            blocoFavoritoFaq.classList.add('display_none');
        }
        const clone = bloco.cloneNode(true);
        blocoFavoritoLista.prepend(clone);
        const botao = clone.querySelector('.botao_favorito');
        botao.addEventListener('click', () => {
            executarFavorito(botao);
        });
    };
    const removerBlocoFavorito = id => {
        if (!blocoFavorito) {
            return;
        }
        const bloco = blocoFavorito.querySelector('.parceiro[data-url="' + id + '"]');
        if (!bloco) {
            return;
        }
        bloco.parentNode.removeChild(bloco);
        removerFavoritoOutroLugar(id);
        if (blocoFavorito.querySelectorAll('.parceiro').length > 0) {
            return;
        }
        blocoFavorito.classList.add('display_none');
        blocoFavoritoFaq.classList.remove('display_none');
    };
    const removerFavoritoOutroLugar = id => {
        const lista = $$('.parceiro[data-url="' + id + '"]');
        ppe(lista);
        if (lista.length == 0) {
            return;
        }
        lista.forEach(loja => {
            const botao = loja.querySelector('.botao_favorito');
            if (botao) {
                botao.classList.remove('favorito_marcado');
            }
        });
    };
});
