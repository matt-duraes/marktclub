window.addEventListener('load', () => {
    const carregarFuncaoAjuda = () => {
        const botaoFechar = document.querySelectorAll('.botao_fechar_popup');
        botaoFechar.forEach(fecha => {
            fecha.addEventListener('click', () => {
                Pagina.staticFechar();
            });
        });
    };

    const botaoAbrirPesquisaSatisfacao = document.getElementById('abreAjuda');
    const paginaPesquisaSatisfacao = new Pagina(
        'Ajuda',
        document.querySelector('#LINK').value + '/ajuda',
        {},
        true,
        true,
        carregarFuncaoAjuda
    );

    botaoAbrirPesquisaSatisfacao.addEventListener('click', () => {
        paginaPesquisaSatisfacao.abrir();
    });
});
