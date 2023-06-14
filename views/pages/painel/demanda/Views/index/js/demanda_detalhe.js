let idDemanda, idDono, area, blocoItem;
const demandaDetalhe = () => {
    idDemanda = document.getElementById('input_demanda_id').value;
    idDono = document.getElementById('input_demanda_dono_id').value;
    area = document.querySelector('#input_area').value || '';
    blocoItem = document.querySelector('.bloco_tarefa_item[data-id="' + idDemanda + '"]');

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
            LINK + '/demanda/tarefa-editar/' + id + '/' + idDemanda,
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
        tarefa.addEventListener('click', e => {
            if (e.target.classList.contains('botao_like') || e.target.closest('.botao_like')) {
                marcarTarefaComLike(tarefa.getAttribute('data-id'), tarefa.querySelector('.bloco_teste .bloco_imagem'));
                return;
            } else if (e.target.classList.contains('botao_deslike') || e.target.closest('.botao_deslike')) {
                abrirBlocoRecusarTarefa(tarefa.getAttribute('data-id'));
                return;
            }
            tarefa.classList.toggle('ativo');
        });
    });

    const marcarTarefaComLike = async (id, bloco) => {
        const usuarioId = document.querySelector('#USUARIO_ID').value;
        if (bloco.querySelector('figure[data-id="' + usuarioId + '"]')) {
            return;
        }

        Loading.show();
        const resposta = await fetch(LINK + '/demanda/tarefa-like/' + id, {
            method: 'POST',
        });
        const json = await respostaJson(resposta, 'Erro ao dar like na tarefa, por favor, tente novamente.');

        if (false === json) {
            Loading.hide();
            return;
        }

        const imagem = document.querySelector('#USUARIO_IMAGEM').value;
        bloco.insertAdjacentHTML(
            'beforeend',
            `<figure data-id="${usuarioId}" style="background-image: url(${imagem})"></figure>`
        );

        let concluir = true;
        let quantidadeCurtida = listaTarefa.length;
        let i = 0;
        for (i; i < quantidadeCurtida; ++i) {
            const figure = listaTarefa[i].querySelectorAll('.bloco_teste figure');
            const dono = listaTarefa[i].querySelector('.bloco_teste figure[data-id="' + idDono + '"]');
            if (!dono || figure.length < 2) {
                concluir = false;
                i = quantidadeCurtida;
            }
        }

        if (concluir) {
            window.location.reload();
            return;
        }

        Loading.hide();
    };

    const blocoRecusarTarefa = document.getElementById('bloco_recusar_tarefa');
    const inputIdDeslike = document.getElementById('input_id_deslike');
    const inputTextoDeslike = document.getElementById('input_texto_deslike');

    const botaoRecusarCancelar = document.getElementById('botao_recusar_cancelar');
    const botaoRecusarSalvar = document.getElementById('botao_recusar_salvar');
    const abrirBlocoRecusarTarefa = id => {
        inputIdDeslike.value = id;

        blocoRecusarTarefa.classList.remove('display_none');
        setTimeout(() => {
            blocoRecusarTarefa.classList.add('ativo');
            inputTextoDeslike.focus();
        }, 40);
    };
    botaoRecusarCancelar.addEventListener('click', () => {
        blocoRecusarTarefa.classList.remove('ativo');
        setTimeout(() => {
            blocoRecusarTarefa.classList.add('display_none');
            inputIdDeslike.value = '';
            inputTextoDeslike.value = '';
            inputTextoDeslike.style.height = '25px';
        }, 300);
    });
    botaoRecusarSalvar.addEventListener('click', async () => {
        if (inputTextoDeslike.value == '') {
            Alerta.notificacao('Digite um motivo para o deslike na tarefa para continuar.', false);
            return;
        }

        Loading.show();
        const body = new FormData();
        body.append('motivo', inputTextoDeslike.value);

        const resposta = await fetch(LINK + '/demanda/tarefa-deslike/' + inputIdDeslike.value, {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Erro ao dar deslike na tarefa, por favor, tente novamente.');
        if (false === json) {
            Loading.hide();
            return;
        }

        window.location.reload();
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
        const resposta = await fetch(LINK + '/demanda/trabalho-comecar/' + id + '/' + idDemanda + '/' + area);
        const json = await respostaJson(resposta, 'Erro ao começar a trabalhar na demanda.');
        if (false === json) {
            Loading.hide();
            return;
        }
        window.location.assign(LINK + '/demanda/' + area);
    };

    /*
    |--------------------------------------------------------------------------
    | EDITAR DEMANDA
    |--------------------------------------------------------------------------
    */
    const botaoEditar = document.querySelector('#botao_editar_demanda');
    if (botaoEditar) {
        const PaginaEditar = new Pagina(
            'demanda-editar-' + idDemanda,
            LINK + '/demanda/demanda-editar/' + idDemanda,
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
    | CANCELAR DEMANDA
    |--------------------------------------------------------------------------
    */
    const botaoCancelar = document.querySelector('#botao_cancelar_demanda');
    if (botaoCancelar) {
        botaoCancelar.addEventListener('click', () => {
            abrirBlocoCancelarDemanda();
        });
    }

    const blocoCancelarDemanda = document.querySelector('#bloco_cancelar_demanda');
    const botaoCancelarCancelar = document.querySelector('#botao_cancelar_cancelar');
    const botaoCancelarSalvar = document.querySelector('#botao_cancelar_salvar');
    const inputTextoCancelar = document.querySelector('#input_texto_cancelar');
    const abrirBlocoCancelarDemanda = id => {
        blocoCancelarDemanda.classList.remove('display_none');
        setTimeout(() => {
            blocoCancelarDemanda.classList.add('ativo');
            inputTextoCancelar.focus();
        }, 40);
    };
    const fecharBlocoCancelarDemanda = () => {
        blocoCancelarDemanda.classList.remove('ativo');
        setTimeout(() => {
            blocoCancelarDemanda.classList.add('display_none');
            inputTextoCancelar.value = '';
            inputTextoCancelar.style.height = '25px';
        }, 300);
    };
    botaoCancelarCancelar.addEventListener('click', () => {
        fecharBlocoCancelarDemanda();
    });

    botaoCancelarSalvar.addEventListener('click', async () => {
        if (inputTextoCancelar.value == '') {
            Alerta.notificacao('Digite o motivo do cancelamento da demanda.');
            return;
        }
        const body = new FormData();
        body.append('motivo', inputTextoCancelar.value);
        const resposta = await fetch(LINK + '/demanda/demanda-cancelar/' + idDemanda, {
            method: 'POST',
            body,
        });
        const json = await respostaJson(resposta, 'Erro ao cancelar demanda, por favor, tente novamente.');
        if (false === json) {
            return;
        }
        fecharBlocoCancelarDemanda();
        const PaginaFechar = new Pagina();
        PaginaFechar.fechar();
        removerItemDemanda(blocoItem);
    });

    /*
    |--------------------------------------------------------------------------
    | ABRIR NOVA DEMANDA
    |--------------------------------------------------------------------------
    */
    const botaoSalvarTarefa = document.getElementById('botao_salvar_tarefa');
    if (botaoSalvarTarefa) {
        const PaginaSalvarTarefa = new Pagina(
            'tarefa-salvar',
            LINK + '/demanda/tarefa-salvar/' + idDemanda,
            {},
            true,
            false,
            tarefaSalvar
        );
        botaoSalvarTarefa.addEventListener('click', () => {
            PaginaSalvarTarefa.abrir();
        });
    }

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
        const resposta = await fetch(LINK + '/demanda/demanda-liberar/' + idDemanda, {
            method: 'POST',
        });
        const json = await respostaJson(resposta, 'Erro ao liberar demanda, por favor, tente novamente.');
        Loading.hide();
        if (false === json) {
            return;
        }
        Alerta.notificacao('Demanda liberada com sucesso!', true);
        moverItemDemanda(blocoListaSessao[1], blocoItem);
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

    const resposta = await fetch(LINK + '/demanda/tarefa-arquivo/' + idDemanda, {
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
