window.addEventListener('load', () => {
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
        const id = bloco.getAttribute('data-id');

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
        botao.classList.remove('favorito_marcado');
    };
});
