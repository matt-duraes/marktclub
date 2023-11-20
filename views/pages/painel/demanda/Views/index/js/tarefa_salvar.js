const botaoSalvarTarefa = $('#botao_tarefa_salvar_salvar');
const limparPopupTarefa = () => {
    inputTarefaId.value = '';
    inputTarefaTitulo.value = '';
    formValue(inputTarefaTexto, '');
    formValue(inputTarefaTipo, '');
};

botaoSalvarTarefa.addEventListener('click', async () => {
    const id = inputTarefaId.value;
    const uri = id == '' ? '/demanda/tarefa-salvar' : '/demanda/tarefa-editar/' + id;

    const titulo = inputTarefaTitulo.value;
    const texto = inputTarefaTexto.value;
    const tipo = inputTarefaTipo.value;

    if (titulo == '') {
        inputTarefaTitulo.focus();
        Alerta.notificacao('Digite um título para a tarefa para continuar.', false);
        return;
    } else if (texto == '') {
        Alerta.notificacao('Digite o texto da demanda para continuar.', false);
        return;
    } else if (tipo == '') {
        Alerta.notificacao('Escolha um tipo para a tarefa para continuar.', false);
        return;
    }

    const body = { titulo, texto, tipo };
    if (id == '') {
        body.demanda = idDemanda;
    }
    Loading.show();
    const resposta = await ajaxPost(LINK + uri, body, 'Erro ao salvar tarefa, por favor, tente novamente.');
    Loading.hide();
    if (false === resposta) {
        return;
    }

    PopupTemp.fechar();
    setTimeout(() => {
        limparPopupTarefa();
    }, 300);

    if (id == '') {
        const blocoZero = $('#bloco_tarefa_zero');
        if (blocoZero) {
            blocoZero.classList.add('display_none');
        }
        adicionarNovaTarefa(resposta.dado);
        return;
    }
    atualizarTarefaExistente(id, titulo, texto, tipo);
});
