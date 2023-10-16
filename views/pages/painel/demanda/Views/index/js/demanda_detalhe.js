const demandaDetalhe = () => {
    historicoLoad();

    idDemanda = $('#input_demanda_id').value;
    statusDemanda = $('#input_demanda_status').value;
    liberadoDemanda = $('#input_demanda_liberado').value;

    const blocoEsqueleto = $('#bloco_tarefa_loading');
    const blocoZero = $('#bloco_tarefa_zero');

    const EsqueletoItem = new Esqueleto(blocoEsqueleto, '.esqueleto');
    EsqueletoItem.show();

    /*
    |--------------------------------------------------------------------------
    | ABRIR NOVA DEMANDA
    |--------------------------------------------------------------------------
    */
    const botaoTarefaAbrir = $('#botao_salvar_tarefa');
    botaoTarefaAbrir.addEventListener('click', () => {
        limparPopupTarefa();
        abrirPopupNovaTarefa();
    });
    const abrirPopupNovaTarefa = () => {
        PopupTarefa.abrir();
        inputTarefaTitulo.focus();
    };

    const buscarListaTarefa = async () => {
        const resposta = await ajaxPost(
            LINK + '/demanda/tarefa-listar/' + idDemanda,
            undefined,
            'Erro ao buscar a lista de tarefas, por favor, tente novamente.'
        );
        blocoEsqueleto.classList.add('display_none');
        if (false === resposta) {
            return;
        }
        if (resposta.dado.length == 0) {
            blocoZero.classList.remove('display_none');
            abrirPopupNovaTarefa();
            return;
        }

        for (const item of resposta.dado) {
            adicionarNovaTarefa(item);
        }
    };
    buscarListaTarefa();
};
