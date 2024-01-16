window.addEventListener('load', () => {
    const botaoFiltrarClone = $('#botao_filtrar');
    const blocoApp = $('#header_template .bloco_app');
    const botaoBuscarFiltro = $('#botao_buscar_filtro');
    const PopupFiltro = new Popup('filtro', 'bloco_filtro', true, true);

    const botaoFiltrar = botaoFiltrarClone.cloneNode(true);
    botaoFiltrar.addEventListener('click', () => {
        PopupFiltro.abrir();
    });
    blocoApp.appendChild(botaoFiltrar);

    botaoBuscarFiltro.addEventListener('click', () => {
        const tarefa_tipo = $('#input_demanda_filtro_tipo_tarefa').value;
        const empresa = $('#input_demanda_filtro_empresa').value;
        const tipo = $('#input_demanda_filtro_tipo_demanda').value;
        const data_inicio = $('#input_demanda_filtro_data_inicio').value;
        const data_fim = $('#input_demanda_filtro_data_fim').value;

        buscarDados({tarefa_tipo, empresa, tipo, data_inicio, data_fim});
        PopupFiltro.fechar();
    });
});
