window.addEventListener('load', () => {
    const blocoLink = document.querySelector('#bloco_limpar_pesquisa');
    const linkLocation =
        blocoLink != null ? blocoLink.getAttribute('data-link') : '';

    const botaoFiltroRemover = document.querySelectorAll(
        '#bloco_limpar_pesquisa .item i'
    );
    const removerItemDoFiltro = botao => {
        botao.parentNode.removeChild(botao);
        if (
            document.querySelectorAll('#bloco_limpar_pesquisa .item').length ==
            0
        ) {
            const blocoFiltro = document.querySelector(
                '#bloco_limpar_pesquisa'
            );
            blocoFiltro.parentNode.removeChild(blocoFiltro);
            window.location.assign(linkLocation);
            return;
        }
        redirecionarPaginaAposRemoverFiltro();
    };
    const redirecionarPaginaAposRemoverFiltro = () => {
        const itens = document.querySelectorAll('#bloco_limpar_pesquisa .item');
        let url = [];
        itens.forEach(item => {
            const name = item.getAttribute('data-name');
            const value = item.getAttribute('data-value');
            if (name == 'tag') {
                const listaTag = value.split(',');
                listaTag.forEach(tag => {
                    url.push('tag[]=' + tag);
                });
                return;
            }
            url.push(name + '=' + value);
        });
        window.location.assign(linkLocation + '?' + url.join('&'));
    };

    if (botaoFiltroRemover.length > 0) {
        botaoFiltroRemover.forEach(botao => {
            botao.addEventListener('click', () => {
                const item = botao.closest('.item');
                removerItemDoFiltro(item);
            });
        });
    }
});
