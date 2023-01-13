const tarefaSalvar = () => {
    const idDemanda = document.getElementById('input_id_demanda').value;
    const inputTitulo = document.getElementById('input_titulo');
    const inputTexto = document.getElementById('input_texto');
    const inputTipo = document.getElementById('input_tipo');
    const inputHora = document.getElementById('input_hora');

    const botaoFechar = document.getElementById('botao_cancelar_edicao');
    const botaoSalvar = document.getElementById('botao_editar_tarefa');

    const PaginaDemanda = new Pagina(
        'demanda-' + idDemanda,
        LINK + '/demanda/demanda/' + idDemanda,
        {},
        true,
        false,
        demandaDetalhe
    );

    botaoFechar.addEventListener('click', () => {
        PaginaDemanda.abrir();
    });
    botaoSalvar.addEventListener('click', async () => {
        Loading.show();
        if (!validarCampos()) {
            Loading.hide();
            return;
        }
        const body = montarBody();

        const resposta = await fetch(LINK + '/demanda/tarefa-salvar/' + idTarefa, {
            method: 'POST',
            body,
        });

        Loading.hide();
        if (
            true !== (await respostaJson(resposta, 'Ocorre um erro ao salvar sua tarefa, por favor, tente novamente.'))
        ) {
            return;
        }

        Alerta.notificacao('Tarefa cadastrada com sucesso.', true);
        PaginaDemanda.abrir();
    });

    const validarCampos = () => {
        let mensagem;
        if (inputTitulo.value == '') {
            mensagem = 'Digite um título para continuar.';
        } else if (inputTexto.value == '') {
            mensagem = 'Digite um texto para continuar.';
        } else if (inputTipo.value == '') {
            mensagem = 'Escolha um tipo de tarefa para continuar.';
        } else if (inputHora.value != '' && !/^[1-9]{1}[0-9]{0,}$/.test(inputHora.value)) {
            mensagem = 'Digite um tempo de produção valido.';
        }
    };
    const montarBody = () => {
        const body = new FormData();
        body.append('titulo', inputTitulo.value);
        body.append('texto', inputTexto.value);
        body.append('tipo', inputTipo.value);
        body.append('hora', inputHora.value);
        return body;
    };
};
