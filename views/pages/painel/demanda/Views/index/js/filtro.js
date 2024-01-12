window.addEventListener('load', () => {
    const botaoFiltrarClone = $('#botao_filtrar');
    const blocoApp = $('#header_template .bloco_app');
    const inputFiltroTipo = $('#input_demanda_filtro_tipo');
    const inputFiltroEmpresa = $('#input_demanda_filtro_empresa');
    const botaoBuscarFiltro = $('#botao_buscar_filtro');
    const PopupFiltro = new Popup('filtro', 'bloco_filtro', true, true);

    const botaoFiltrar = botaoFiltrarClone.cloneNode(true);
    botaoFiltrar.addEventListener('click', () => {
        PopupFiltro.abrir();
    });
    blocoApp.appendChild(botaoFiltrar);

    botaoBuscarFiltro.addEventListener('click', () => {
        const tarefa_tipo = inputFiltroTipo.value;
        const empresa = inputFiltroEmpresa.value;

        buscarDados({tarefa_tipo, empresa});
        PopupFiltro.fechar();
    });
});
