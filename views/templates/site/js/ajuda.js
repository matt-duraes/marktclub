window.addEventListener('load', () => {
    const carregarFuncaoAjuda = () => {
        const botaoFechar = document.querySelectorAll('.botao_fechar_popup');
        botaoFechar.forEach(fecha => {
            fecha.addEventListener('click', () => {
                Pagina.staticFechar();
            });
        });
    };

    const botaoAbrirPesquisaSatisfacao = document.getElementById('botao_abrir_ajuda');
    const paginaPesquisaSatisfacao = new Pagina('Ajuda', LINK + '/ajuda', {}, true, true, carregarFuncaoAjuda);

    botaoAbrirPesquisaSatisfacao.addEventListener('click', () => {
        paginaPesquisaSatisfacao.abrir();
    });
});
