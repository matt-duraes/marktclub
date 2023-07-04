window.addEventListener('load', () => {
    const botaoFavorito = document.querySelectorAll('.botao_favorito');
    const favoritar = async loja => {
        const url = loja.getAttribute('data-url');
        const acao = loja.classList.contains('favorito_marcado') ? 'desmarcar' : 'marcar';

        const resposta = await ajaxPost(LINK + '/convenios/favorito', { url, acao });
        if (false === resposta) {
            return;
        }
        loja.classList.toggle('favorito_marcado');
    };

    botaoFavorito.forEach(botao => {
        botao.addEventListener('click', () => {
            favoritar(botao);
        });
    });
});
