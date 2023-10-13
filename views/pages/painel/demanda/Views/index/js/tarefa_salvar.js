const botaoSalvarTarefa = $('#botao_tarefa_salvar_salvar');
const inputTarefaId = $('#input_tarefa_id');
const inputTarefaTitulo = $('#input_tarefa_titulo');
const inputTarefaTexto = $('#input_tarefa_texto');
const inputTarefaTipo = $('#input_tarefa_tipo');
const limparPopupTarefa = () => {
    inputTarefaId.value = '';
    inputTarefaTitulo.value = '';
    formValue(inputTarefaTexto, '');
    formValue(inputTarefaTipo, '');
};

botaoSalvarTarefa.addEventListener('click', async () => {
    const id = inputTarefaId.value;
    const uri = id == '' ? '/demanda/tarefa-salvar' : '/demanda/tarefa-editar/' + id;

    if (inputTarefaTitulo.value == '') {
        inputTarefaTitulo.focus();
        Alerta.notificacao('Digite um título para a tarefa para continuar.', false);
        return;
    } else if (inputTarefaTexto.value == '') {
        Alerta.notificacao('Digite o texto da demanda para continuar.', false);
        return;
    } else if (inputTarefaTipo.value == '') {
        Alerta.notificacao('Escolha um tipo para a tarefa para continuar.', false);
        return;
    }
    const body = {
        titulo: inputTarefaTitulo.value,
        texto: inputTarefaTexto.value,
        tipo: inputTarefaTipo.value,
    };
    if (idDemanda != '') {
        body.demanda = idDemanda;
    }
    const resposta = await ajaxPost(LINK + uri, body, 'Erro ao salvar tarefa, por favor, tente novamente.');
    if (false === resposta) {
        return;
    }

    PopupTemp.fechar();
    setTimeout(() => {
        limparPopupTarefa();
    }, 300);

    if (id == '') {
        adicionarNovaTarefa(listaColuna[0].querySelector('.conteudo'), resposta);
        return;
    }
    atualizarTarefaExistente(resposta);
});
// window.addEventListener('load', () => {
// const inputTarefaTitulo = $('#input_titulo');
// const inputTarefaTexto = $('#input_texto');
// const inputTarefaTipo = $('#input_tipo');
// const botaoTarefaSalvar = $('#botao_tarefa_salvar_salvar');
// botaoTarefaSalvar.addEventListener('click', async () => {
//     Loading.show();
//     if (!validarCampos()) {
//         Loading.hide();
//         return;
//     }
//     const resposta = await ajaxPost(
//         LINK + '/demanda/tarefa-salvar',
//         {
//             demanda: idDemanda,
//             titulo: inputTarefaTitulo.value,
//             texto: inputTarefaTexto.value,
//             tipo: inputTarefaTipo.value,
//         },
//         'Ocorre um erro ao salvar sua tarefa, por favor, tente novamente.'
//     );
//     Loading.hide();
//     if (false === resposta) {
//         return;
//     }
//     Alerta.notificacao('Tarefa cadastrada com sucesso.', true);
//     PopupTemp.fechar();
// });
// const validarCampos = () => {
//     let mensagem = '';
//     if (inputTarefaTitulo.value == '') {
//         mensagem = 'Digite um título para continuar.';
//     } else if (inputTarefaTexto.value == '') {
//         mensagem = 'Digite um texto para continuar.';
//     } else if (inputTarefaTipo.value == '') {
//         mensagem = 'Escolha um tipo de tarefa para continuar.';
//     }
//     if (mensagem != '') {
//         Alerta.notificacao(mensagem, false);
//         return false;
//     }
//     return true;
// };
// });
