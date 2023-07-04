window.addEventListener('load', () => {
    const botaoFiltrar = document.querySelector('#botao_filtrar_abrir');
    if (botaoFiltrar) {
        botaoFiltrar.addEventListener('click', () => {
            botaoFiltrar.classList.add('animacao');
            Ajuda.hide();
            setTimeout(() => {
                botaoFiltrar.classList.remove('animacao');
            }, 500);
        });
    }
});