window.addEventListener('load', () => {
    const carregarFuncaoPesquisaSatisfacao = () => {
        const botaoFechar = document.querySelectorAll('.botao_fechar_popup');

        botaoFechar.forEach(fecha => {
            fecha.addEventListener('click', () => {
                Pagina.staticFechar();
            });
        });
    };

    const botaoAbrirPesquisaSatisfacao = document.getElementById('abrePesquisaSatisfacao');
    const paginaPesquisaSatisfacao = new Pagina(
        'Pesquisa de satisfação',
        document.querySelector('#LINK').value + '/pesquisa-de-satisfacao',
        {},
        true,
        true,
        carregarFuncaoPesquisaSatisfacao
    );

    botaoAbrirPesquisaSatisfacao.addEventListener('click', () => {
        paginaPesquisaSatisfacao.abrir();
    });
});
