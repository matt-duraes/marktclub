window.addEventListener('load', () => {
    const listaTab = document.querySelectorAll('.bloco_tab_geral');
    if (listaTab.length == 0) {
        return;
    }

    listaTab.forEach(bloco => {
        const listaBotao = bloco.querySelectorAll('.bloco_botao .botao');
        const listaConteudo = bloco.querySelectorAll('.conteudo');
        listaBotao.forEach(botao => {
            const id = botao.getAttribute('data-id');
            const conteudo = bloco.querySelector('#' + id.replace(/^\#/, ''));
            if (!conteudo) {
                return;
            }
            botao.addEventListener('click', () => {
                abrirConteudo(botao, conteudo, listaBotao, listaConteudo);
            });
        });
    });

    const abrirConteudo = (botao, conteudo, listaBotao, listaConteudo) => {
        listaBotao.forEach(item => {
            item.classList.remove('ativo');
        });
        listaConteudo.forEach(item => {
            item.classList.add('display_none');
        });
        botao.classList.add('ativo');
        conteudo.classList.remove('display_none');
    };
});
