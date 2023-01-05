const editarTarefa = () => {
    const idDemanda = document.getElementById('input_id_demanda').value;
    const idTarefa = document.getElementById('input_id_tarefa').value;
    const inputTitulo = document.getElementById('input_titulo');
    const inputTexto = document.getElementById('input_texto');
    const inputTipo = document.getElementById('input_texto');

    const botaoFechar = document.getElementById('botao_cancelar_edicao');
    const botaoSalvar = document.getElementById('botao_editar_tarefa');

    const PaginaDemanda = new Pagina(
        'tarefa-' + idDemanda,
        LINK + '/demanda/tarefa/' + idDemanda,
        {},
        true,
        false,
        detalheDemanda
    );

    botaoFechar.addEventListener('click', () => {
        PaginaDemanda.abrir();
    });
    botaoSalvar.addEventListener('click', async () => {
        Loading.show();
        const body = new FormData();
        body.append('titulo', inputTitulo.value);
        body.append('texto', inputTexto.value);
        body.append('tipo', inputTipo.value);

        const resposta = await fetch(LINK + '/demanda/tarefa-editar/' + idTarefa, {
            method: 'POST',
            body,
        });

        Loading.hide();
        if (
            true !==
            (await respostaJson(resposta, 'Ocorre um erro ao atualizar sua tarefa, por favor, tente novamente.'))
        ) {
            return;
        }

        Alerta.notificacao('Tarefa atualizada com sucesso.', true);
        PaginaDemanda.abrir();
    });
};
