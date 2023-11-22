const comecarTrabalhar = async (botaoTrabalhar, botaoFinalizar, tarefa) => {
    const bloco = $('#id_tarefa_' + tarefa);
    if (!bloco) {
        return;
    }

    const blocoEquipe = $('.item_equipe', bloco);
    const equipe = blocoEquipe.attr('data-equipe');
    if (
        equipe != '' &&
        !(await Alerta.confirmar('Mudar dono', 'Essa tarefa já tem um dono, gostaria de pegar ela mesmo assim?'))
    ) {
        return;
    }
    Loading.show();
    const resposta = await ajaxPut(
        LINK + `/demanda/trabalho-comecar/${tarefa}`,
        undefined,
        'Erro ao começar a trabalhar, por favor, tente novamente.'
    );
    Loading.hide();
    if (false === resposta) {
        return;
    }
    botaoTrabalhar.displayHide();
    botaoFinalizar.displayShow();
    blocoEquipe.attr({
        'data-ajuda': USUARIO_NOME,
        'data-id': USUARIO_ID,
    });
    blocoEquipe.css('backgroundImage', `url(${USUARIO_IMAGEM})`);
};

const finalizarTarefa = async (botaoFinalizar, botaoConcluido, blocoTeste, tarefa) => {
    const bloco = $('#id_tarefa_' + tarefa);
    if (!bloco) {
        return;
    }

    const equipe = $('.item_equipe', bloco).attr('data-equipe');
    if (equipe != USUARIO_ID && USUARIO_GERENTE == 'nao') {
        Alerta.notificacao('Você não pode concluir uma tarefa que não é sua.');
        return;
    }

    if (!(await Alerta.confirmar('Concluir tarefa', 'Tem certeza que deseja concluir essa tarefa?', true))) {
        return;
    }

    Loading.show();
    const resposta = await ajaxPut(
        LINK + `/demanda/trabalho-concluir/${tarefa}`,
        undefined,
        'Erro ao finalizar tarefa, por favor, tente novamente.'
    );
    Loading.hide();
    if (false === resposta) {
        return;
    }
    botaoFinalizar.displayHide();
    botaoConcluido.displayShow();
    blocoTeste.displayShow();
};
