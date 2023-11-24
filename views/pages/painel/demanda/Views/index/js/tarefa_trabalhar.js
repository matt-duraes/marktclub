const comecarTrabalhar = async (botaoTrabalhar, botaoFinalizar, tarefa) => {
    const bloco = $('#id_tarefa_' + tarefa);
    if (!bloco) {
        return;
    }

    const blocoEquipe = $('.item_equipe', bloco);
    const equipe = blocoEquipe.attr('data-equipe');
    if (
        equipe != '' &&
        !(await Alerta.confirmar('Mudar dono', 'Essa tarefa já tem um dono, gostaria de pegar ela mesmo assim?', '!'))
    ) {
        return;
    } else if (
        equipe == '' &&
        !(await Alerta.confirmar('Pegar tarefa', 'Tem certeza que deseja começar a trabalhar nesse tarefa?', '!'))
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
    bloco.attr('data-status', 'andamento');
    botaoTrabalhar.displayHide();
    botaoFinalizar.displayShow();
    blocoEquipe.attr({
        'data-ajuda': USUARIO_NOME,
        'data-id': USUARIO_ID,
    });
    blocoEquipe.css('backgroundImage', `url(${USUARIO_IMAGEM})`);

    const blocoDemanda = $('#id_demanda_' + idDemanda);
    const blocoStatus = blocoDemanda.closest('.bloco_coluna').attr('data-status');
    const blocoAndamento = $('.bloco_coluna[data-status="andamento"] .conteudo');
    const blocoAtual = blocoDemanda.closest('.bloco_coluna .conteudo');

    if (blocoStatus != 'andamento' && blocoAndamento) {
        mudarDemandaColuna(blocoAtual, blocoAndamento, blocoDemanda);
    }
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

    if (!(await Alerta.confirmar('Concluir tarefa', 'Tem certeza que deseja concluir essa tarefa?', '!'))) {
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
    bloco.attr('data-status', 'concluida');
    verificarPodePassarDemandaTeste();
};

const verificarPodePassarDemandaTeste = () => {
    const listaTarefa = $$('#bloco_tarefa_lista .tarefa');
    let concluido = true;
    for (const item of listaTarefa) {
        if (item.attr('data-status') != 'concluida') {
            concluido = false;
            break;
        }
    }
    if (false == concluido) {
        return;
    }
    const demanda = $('#id_demanda_' + idDemanda);
    const blocoAtual = demanda.closest('.conteudo');
    const blocoDestino = $('.bloco_coluna[data-status="teste"] .conteudo');
    mudarDemandaColuna(blocoAtual, blocoDestino, demanda);
};

const adicionarLike = async id => {
    const bloco = $('#id_tarefa_' + id);
    if (!bloco || $('.bloco_teste .bloco_imagem figure[data-id="' + USUARIO_ID + '"]', bloco)) {
        return;
    }

    adicionarImagemEquipe($('.bloco_teste .bloco_imagem', bloco), USUARIO_ID, USUARIO_NOME, USUARIO_IMAGEM);

    const resposta = await ajaxPost(
        LINK + `/demanda/tarefa-like/${id}`,
        undefined,
        'Ocorre um erro ao adicionar like.'
    );
    if (false === resposta) {
        figure.remove();
        return;
    }
    verificarPodePassarDemandaConcluido();
};
const verificarPodePassarDemandaConcluido = () => {
    const listaTarefa = $$('#bloco_tarefa_lista .tarefa');
    const dono = $('#input_demanda_equipe').value;
    for (const tarefa of listaTarefa) {
        if (
            $$('.bloco_teste .bloco_imagem figure', tarefa).length < 2 ||
            !$('.bloco_teste .bloco_imagem figure[data-id="' + dono + '"]', tarefa)
        ) {
            return;
        }
    }
    $$('.bloco_teste').classe('bloco_testado', true);
    const demanda = $('#id_demanda_' + idDemanda);
    const blocoAtual = demanda.closest('.conteudo');
    const blocoDestino = $('.bloco_coluna[data-status="concluida"] .conteudo');
    mudarDemandaColuna(blocoAtual, blocoDestino, demanda);
};
const cancelarTarefa = id => {
    //
};
