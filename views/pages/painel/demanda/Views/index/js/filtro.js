window.addEventListener('load', () => {
    const botaoFiltrarClone = $('#botao_filtrar');
    const blocoApp = $('#header_template .bloco_app');
    const inputFiltroTipoTarefa = $('#input_demanda_filtro_tipo_tarefa');
    const inputFiltroTipoDemanda = $('#input_demanda_filtro_tipo_demanda');
    const inputFiltroEmpresa = $('#input_demanda_filtro_empresa');
    const botaoBuscarFiltro = $('#botao_buscar_filtro');
    const PopupFiltro = new Popup('filtro', 'bloco_filtro', true, true);

    const botaoFiltrar = botaoFiltrarClone.cloneNode(true);
    botaoFiltrar.addEventListener('click', () => {
        PopupFiltro.abrir();
    });
    blocoApp.appendChild(botaoFiltrar);

    botaoBuscarFiltro.addEventListener('click', () => {
        const tarefa_tipo = inputFiltroTipoTarefa.value;
        const empresa = inputFiltroEmpresa.value;
        const tipo = inputFiltroTipoDemanda.value;

        buscarDados({tarefa_tipo, empresa, tipo});
        PopupFiltro.fechar();
    });
});
