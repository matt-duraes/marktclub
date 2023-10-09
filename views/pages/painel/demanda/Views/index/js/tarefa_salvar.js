window.addEventListener('load', () => {
    const inputTarefaTitulo = $('#input_titulo');
    const inputTarefaTexto = $('#input_texto');
    const inputTarefaTipo = $('#input_tipo');

    const botaoTarefaSalvar = $('#botao_tarefa_salvar_salvar');
    botaoTarefaSalvar.addEventListener('click', async () => {
        Loading.show();
        if (!validarCampos()) {
            Loading.hide();
            return;
        }

        const resposta = await ajaxPost(
            LINK + '/demanda/tarefa-salvar',
            {
                demanda: idDemanda,
                titulo: inputTarefaTitulo.value,
                texto: inputTarefaTexto.value,
                tipo: inputTarefaTipo.value,
            },
            'Ocorre um erro ao salvar sua tarefa, por favor, tente novamente.'
        );

        Loading.hide();
        if (false === resposta) {
            return;
        }
        Alerta.notificacao('Tarefa cadastrada com sucesso.', true);
        PopupTemp.fechar();
    });

    const validarCampos = () => {
        let mensagem = '';
        if (inputTarefaTitulo.value == '') {
            mensagem = 'Digite um título para continuar.';
        } else if (inputTarefaTexto.value == '') {
            mensagem = 'Digite um texto para continuar.';
        } else if (inputTarefaTipo.value == '') {
            mensagem = 'Escolha um tipo de tarefa para continuar.';
        }
        if (mensagem != '') {
            Alerta.notificacao(mensagem, false);
            return false;
        }
        return true;
    };
});
