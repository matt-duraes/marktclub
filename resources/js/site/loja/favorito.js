window.addEventListener('load', () => {
    const blocoFavorito = $('#bloco_favorito');
    const blocoFavoritoLista = $('#bloco_parceiro_favorito');
    const blocoFavoritoFaq = $('#bloco_favorito_faq');
    const blocoParceiro = $$('.bloco_geral_article');
    blocoParceiro.forEach(botao => {
        botao.addEventListener('click', e => {
            const target = e.target;
            const botaoFavorito = target.classList.contains('botao_favorito')
                ? target
                : target.closest('.botao_favorito');
            if (botaoFavorito) {
                executarFavorito(botaoFavorito);
            }
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
        if (blocoFavorito) {
            adicionarBlocoFavorito(botao.closest('.parceiro'), id);
        }
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
        if (blocoFavorito) {
            removerBlocoFavorito(id);
        }
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
