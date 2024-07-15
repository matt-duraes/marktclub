const demandaDetalhe = () => {
    historicoLoad();

    idDemanda = $('#input_demanda_id').value;
    statusDemanda = $('#input_demanda_status').value;
    liberadoDemanda = $('#input_demanda_liberado').value == 'sim';

    const botaoSeguir = $('#botao_seguir_demanda');
    const botaoEditar = $('#botao_editar_demanda');
    const botaoCancelar = $('#botao_cancelar_demanda');

    const blocoEsqueleto = $('#bloco_tarefa_loading');

    const EsqueletoItem = new Esqueleto(blocoEsqueleto, '.esqueleto');
    EsqueletoItem.show();

    const listaAjuda = $('#bloco_demanda_tarefa').querySelectorAll('*[data-ajuda]');
    listaAjuda.forEach(bloco => {
        bloco.addEventListener('mouseover', () => {
            const texto = bloco.getAttribute('data-ajuda');
            Ajuda.show(bloco, texto);
        });
        bloco.addEventListener('mouseout', () => {
            Ajuda.hide();
        });
    });

    if (statusDemanda == 'concluida') {
        botaoSeguir.displayHide();
        botaoEditar.displayHide();
        botaoCancelar.displayHide();
    }

    if (botaoSeguir) {
        botaoSeguir.addEventListener('click', () => {
            const texto = botaoSeguir.innerText.trim();
            if (texto == 'seguir') {
                seguirDemanda();
                return;
            }
            paraSeguirDemanda();
        });
    }
    const seguirDemanda = async () => {
        botaoSeguir.innerText = 'seguindo';
        const resposta = await ajaxPost(LINK + '/demanda/demanda-seguir', { id: idDemanda }, 'Erro ao seguir demanda');
        if (false !== resposta) {
            return;
        }
        botaoSeguir.innerText = 'seguir';
    };
    const paraSeguirDemanda = async () => {
        botaoSeguir.innerText = 'seguir';
        const resposta = await ajaxPost(
            LINK + '/demanda/demanda-seguir-parar/',
            { id: idDemanda },
            'Erro ao deixar de seguir demanda'
        );
        if (false !== resposta) {
            return;
        }
        botaoSeguir.innerText = 'seguindo';
    };

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
    if (inArray(statusDemanda, ['teste', 'concluida'])) {
        botaoTarefaAbrir.displayHide();
    }
    const abrirPopupNovaTarefa = () => {
        PopupTarefa.abrir();
        if (area == 'tecnologia') {
            blocoTarefaTecnologia.aparecer();
        }
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

        for (const item of resposta.dado) {
            adicionarNovaTarefa(item);
        }
    };
    buscarListaTarefa();

    /*
    |--------------------------------------------------------------------------
    | EDITAR DEMANDA
    |--------------------------------------------------------------------------
    */
    if (botaoEditar) {
        botaoEditar.addEventListener('click', () => {
            PopupDemandaEditar.abrir();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CANCELAR DEMANDA
    |--------------------------------------------------------------------------
    */
    if (botaoCancelar) {
        botaoCancelar.addEventListener('click', async () => {
            PopupDemandaCancelar.abrir();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ADICIONAR NOVA DEMANDA
    |--------------------------------------------------------------------------
    */
    fwFormArquivoListaChange = () => {
        const lista = $$('.fw_form_arquivo_lista_arquivo input');
        if (lista.length == 0) {
            return;
        }
        const body = { arquivo: [] };
        lista.forEach((input, i) => {
            body.arquivo[i] = input.value;
        });
        ajaxPost(LINK + '/demanda/tarefa-arquivo/' + idDemanda, body, 'Erro ao fazer o upload dos arquivos.');
    };
};
