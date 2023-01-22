let demandaId;
const demandaDetalhe = () => {
    demandaId = document.getElementById('input_demanda_id').value;

    historicoLoad();

    const botaoFechar = document.querySelectorAll('.botao_fechar_demanda');
    botaoFechar.forEach(botao => {
        botao.addEventListener('click', () => {
            Pagina.staticFechar();
        });
    });

    /*
    |--------------------------------------------------------------------------
    | HELPER DE AJUDA
    |--------------------------------------------------------------------------
    */
    const listaAjuda = document.querySelectorAll('#bloco_demanda_tarefa *[data-ajuda]');
    listaAjuda.forEach(bloco => {
        bloco.addEventListener('mouseover', () => {
            const texto = bloco.getAttribute('data-ajuda');
            Ajuda.show(bloco, texto);
        });
        bloco.addEventListener('mouseout', () => {
            Ajuda.hide();
        });
    });

    const listaTarefa = document.querySelectorAll('#bloco_demanda_tarefa .bloco_tarefa article');
    listaTarefa.forEach(tarefa => {
        const id = tarefa.getAttribute('data-id');
        const idDev = tarefa.getAttribute('data-dev');
        const PaginaEditar = new Pagina(
            'tarefa-editar-' + id,
            LINK + '/demanda/tarefa-editar/' + id + '/' + demandaId,
            {},
            true,
            false,
            editarTarefa
        );
        const editar = tarefa.querySelector('.botao_editar');
        const deletar = tarefa.querySelector('.botao_deletar');
        const play = tarefa.querySelector('.botao_play');
        const pause = tarefa.querySelector('.botao_pause');
        if (editar) {
            editar.addEventListener('click', () => {
                PaginaEditar.abrir();
            });
        }
        if (deletar) {
            deletar.addEventListener('click', async () => {
                if (
                    await Alerta.confirmar(
                        'Deletar tarefa',
                        'Tem certeza que deseja deletar essa tarefa? Essa ação não poderá ser desfeita.',
                        false
                    )
                ) {
                    deletarTarefa(id, tarefa);
                }
            });
        }
        if (play) {
            play.addEventListener('click', async () => {
                if (idDev == '' || idDev == document.querySelector('#USUARIO_ID').value) {
                    comecarTrabalhoTarefa(id);
                    return;
                }
                if (
                    await Alerta.confirmar(
                        'Pegar tarefa',
                        'Você está preste a pegar uma tarefa de outro usuário, tem certeza que deseja continuar?',
                        '!'
                    )
                ) {
                    comecarTrabalhoTarefa(id);
                }
            });
        }
        if (pause) {
            pause.addEventListener('click', () => {
                pause.classList.add('display_none');
                play.classList.remove('display_none');
            });
        }
        tarefa.addEventListener('click', () => {
            tarefa.classList.toggle('ativo');
        });
    });

    const deletarTarefa = async (id, tarefa) => {
        const resposta = await fetch(LINK + '/demanda/tarefa/' + id, {
            method: 'DELETE',
        });

        if (!(await respostaJson(resposta, 'Erro ao deletar a tarefa, por favor, tente novamente.'))) {
            return;
        }

        tarefa.parentNode.removeChild(tarefa);
        Alerta.notificacao('Tarefa deletada com sucesso.', true);
    };

    const comecarTrabalhoTarefa = async id => {
        Loading.show();
        const resposta = await fetch(LINK + '/demanda/trabalho-comecar/' + id + '/' + demandaId);
        const json = await respostaJson(resposta, 'Erro ao começar a trabalhar na demanda.');
        if (false === json) {
            Loading.hide();
            return;
        }
        window.location.assign(LINK + '/demanda');
    };

    /*
    |--------------------------------------------------------------------------
    | EDITAR DEMANDA
    |--------------------------------------------------------------------------
    */
    const botaoEditar = document.querySelector('#botao_editar_demanda');
    if (botaoEditar) {
        const PaginaEditar = new Pagina(
            'demanda-editar-' + demandaId,
            LINK + '/demanda/demanda-editar/' + demandaId,
            {},
            true,
            false,
            demandaEditar
        );
        botaoEditar.addEventListener('click', () => {
            PaginaEditar.abrir();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | ABRIR NOVA DEMANDA
    |--------------------------------------------------------------------------
    */
    const botaoSalvarTarefa = document.getElementById('botao_salvar_tarefa');
    const PaginaSalvarTarefa = new Pagina(
        'tarefa-salvar',
        LINK + '/demanda/tarefa-salvar/' + demandaId,
        {},
        true,
        false,
        tarefaSalvar
    );
    botaoSalvarTarefa.addEventListener('click', () => {
        PaginaSalvarTarefa.abrir();
    });

    /*
    |--------------------------------------------------------------------------
    | LIBERAR DEMANDA
    |--------------------------------------------------------------------------
    */
    const botaoLiberarDemanda = document.getElementById('botao_liberar_demanda');
    if (botaoLiberarDemanda) {
        botaoLiberarDemanda.addEventListener('click', async () => {
            if (await Alerta.confirmar('Liberar demanda', 'Tem certeza que deseja liberar essa demanda?', '!')) {
                liberarDemandaParaDesenvolvimento();
            }
        });
    }
    const liberarDemandaParaDesenvolvimento = async () => {
        Loading.show();
        const resposta = await fetch(LINK + '/demanda/demanda-liberar/' + demandaId, {
            method: 'POST',
        });
        const json = await respostaJson(resposta, 'Erro ao liberar demanda, por favor, tente novamente.');
        if (false === json) {
            Loading.hide();
            return;
        }
        window.location.assign(LINK + '/demanda');
    };
};

fwFormArquivoListaChange = async () => {
    const bloco = document.querySelector('#bloco_tarefa_arquivo');
    const arquivo = bloco.querySelectorAll('input');
    if (arquivo.length == 0) {
        return;
    }

    const body = new FormData();
    arquivo.forEach(item => {
        body.append('arquivo[]', item.value);
    });

    const resposta = await fetch(LINK + '/demanda/tarefa-arquivo/' + demandaId, {
        method: 'POST',
        body,
    });

    if (resposta.status == 204) {
        return;
    }
    Alerta.notificacao(
        'Ocorreu um erro ao atualizar lista de arquivos, por favor, recarregue a página e tente novamente.',
        false
    );
};
