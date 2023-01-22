const tarefaSalvar = () => {
    const idDemanda = document.getElementById('input_id_demanda').value;
    const inputTitulo = document.getElementById('input_titulo');
    const inputTexto = document.getElementById('input_texto');
    const inputTipo = document.getElementById('input_tipo');
    const inputMinuto = document.getElementById('input_minuto');

    const botaoFechar = document.getElementById('botao_tarefa_salvar_cancelar');
    const botaoSalvar = document.getElementById('botao_tarefa_salvar_salvar');

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

        const resposta = await fetch(LINK + '/demanda/tarefa-salvar', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Ocorre um erro ao salvar sua tarefa, por favor, tente novamente.');
        Loading.hide();
        if (false === json) {
            return;
        }

        Alerta.notificacao('Tarefa cadastrada com sucesso.', true);
        PaginaDemanda.abrir();
    });

    const validarCampos = () => {
        let mensagem = '';
        if (inputTitulo.value == '') {
            mensagem = 'Digite um título para continuar.';
        } else if (inputTexto.value == '') {
            mensagem = 'Digite um texto para continuar.';
        } else if (inputTipo.value == '') {
            mensagem = 'Escolha um tipo de tarefa para continuar.';
        } else if (inputMinuto.value != '' && !/^[1-9]{1}[0-9]{0,}$/.test(inputMinuto.value)) {
            mensagem = 'Digite um tempo de produção valido.';
        }
        if (mensagem != '') {
            Alerta.notificacao(mensagem, false);
            return false;
        }
        return true;
    };
    const montarBody = () => {
        const body = new FormData();
        body.append('demanda', idDemanda);
        body.append('titulo', inputTitulo.value);
        body.append('texto', inputTexto.value);
        body.append('tipo', inputTipo.value);
        body.append('minuto', inputMinuto.value);
        return body;
    };
};
