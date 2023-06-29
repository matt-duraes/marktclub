window.addEventListener('load', () => {
    const carregarFuncaoAjuda = () => {
        const botaoFechar = document.querySelectorAll('.botao_fechar_popup');
        botaoFechar.forEach(fecha => {
            fecha.addEventListener('click', () => {
                Pagina.staticFechar();
            });
        });
    };

    const botaoAbrirIndiqueParceiro = document.getElementById('abreIndiqueParceiro');
    const paginaIndiqueParceiro = new Pagina(
        'Indicar um Parceiro',
        document.querySelector('#LINK').value + '/indique-um-parceiro',
        {},
        true,
        true,
        carregarFuncaoAjuda
    );

    botaoAbrirIndiqueParceiro.addEventListener('click', () => {
        paginaIndiqueParceiro.abrir();
    });
});
