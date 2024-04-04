window.addEventListener('load', () => {
    const APP = document.getElementById('APP').value;

    const hashDeletar = document.querySelector('#input_hash_deletar_hash').value;
    const hashOrdem = document.querySelector('#input_hash_ordem_hash').value;

    const blocoFwPagina = document.querySelector('#bloco_fw_pagina');

    const blocoGeral = document.getElementById('bloco_app_lista');
    const listaItem = document.querySelectorAll('#bloco_app_lista .linha');
    const botaoAdd = document.getElementById('botao_add_geral');
    const botaoDeletar = document.querySelector('#botao_deletar_geral');

    const botaoMarcaTodos = document.querySelector('#input_marcar_todos');
    const listaTodasLinhas = document.querySelectorAll('#bloco_app_lista .bloco_lista .linha');
    const listaBotaoCheckbox = document.querySelectorAll('#bloco_app_lista .linha .checkbox input');

    const buscaGeralOrdem = document.querySelector('#app_lista_form input[name=ordem]');
    const ordemAtual = buscaGeralOrdem.value;
    const pesquisaAtual = document.querySelector('#app_lista_form input[name=pesquisa]').value;
    const filtroAtual = document.querySelector('#app_lista_form input[name=filtro]').value;
    const paginaAtual = document.querySelector('#app_lista_form input[name=pagina]').value;

    /*
    |--------------------------------------------------------------------------
    | DOWNLOAD
    |--------------------------------------------------------------------------
    */
    let paginaDownload;
    const carregarFuncoesDownload = () => {
        const botaoMarcarTodos = document.querySelector('#bloco_app_download .botao_marcar_desmarcar_download input');
        botaoMarcarTodos.addEventListener('change', () => {
            marcarDesmarcarTodosDownload(botaoMarcarTodos);
        });

        const botaoFechar = document.querySelectorAll('#bloco_app_download .botao_fechar_download');
        botaoFechar.forEach(botaoFechar => {
            botaoFechar.addEventListener('click', () => {
                paginaDownload.fechar();
            });
        });

        const listaCheckbox = document.querySelectorAll('#bloco_app_download .input_download input');
        listaCheckbox.forEach(checkbox => {
            checkbox.addEventListener('change', () => {
                verificarSeMarcouTodosDownload(botaoMarcarTodos);
            });
        });

        const botaoDownload = document.querySelector('#bloco_app_download .botao_download_geral');
        botaoDownload.addEventListener('click', async e => {
            e.preventDefault();

            const blocoTermo = document.querySelector('#bloco_app_download input[name=termo]');
            const termo = blocoTermo.checked ? 'sim' : 'nao';
            const blocoSenha = document.querySelector('#bloco_app_download input[name=senha]');
            const senha = blocoSenha.value;
            const pesquisa = document.querySelector('#bloco_app_download input[name=pesquisa]').value;
            const filtro = document.querySelector('#bloco_app_download input[name=filtro]').value;
            const ordem = document.querySelector('#bloco_app_download input[name=ordem]').value;

            if (pesquisa != pesquisaAtual || filtro != filtroAtual || ordem != ordemAtual) {
                Alerta.notificacao(
                    'Ocorreu um erro ao tentar fazer o download, por favor, recarregue a página e tente novamente.',
                    false
                );
                return;
            }

            const lista = document.querySelectorAll('#bloco_app_download .input_download input:checked');
            if (lista.length == 0) {
                Alerta.notificacao('Você tem que escolher pelo menos 1 item para continuar.', false);
                return;
            } else if (termo != 'sim') {
                Alerta.notificacao(
                    'Você tem que marcar o box para confirmar que você tem permissão para fazer o download.',
                    false
                );
                return;
            } else if (senha == '') {
                Alerta.notificacao('Digite sua senha para fazer o download.', false);
                return;
            }

            Loading.show();

            const body = new FormData();
            body.append('senha', senha);
            body.append('termo', termo);
            body.append('pesquisa', pesquisa);
            body.append('filtro', filtro);
            body.append('ordem', ordem);
            lista.forEach(item => {
                body.append('campo[]', item.value);
            });

            const resposta = await fetch(LINK + '/app/download/' + APP, {
                method: 'POST',
                body,
            });

            const json = await respostaJson(
                resposta,
                'Ocorreu um erro ao salvar o pedido de download, por favor, tente novamente.'
            );

            Loading.hide();
            if (false === json) {
                return;
            }

            Alerta.notificacao(
                `
                    Pedido de download realizado com sucesso, assim que o arquivo estiver
                    pronto para download, iremos notificá-lo.
                `,
                true
            );
            blocoSenha.value = '';
            blocoTermo.checked = false;
            botaoMarcarTodos.checked = false;
            lista.forEach(item => {
                item.checked = false;
            });
        });
    };

    const botaoDownload = document.querySelector('#botao_download_geral');
    if (botaoDownload) {
        const bodyDownload = new FormData();
        bodyDownload.append('pesquisa', pesquisaAtual);
        bodyDownload.append('filtro', filtroAtual);
        bodyDownload.append('ordem', ordemAtual);
        paginaDownload = new Pagina(
            'Download dados',
            `${LINK}/app/download/${APP}?pesquisa=${pesquisaAtual}&filtro=${filtroAtual}&ordem=${ordemAtual}`,
            null,
            true,
            true,
            carregarFuncoesDownload
        );
        botaoDownload.addEventListener('click', () => {
            paginaDownload.abrir();
        });
    }

    const marcarDesmarcarTodosDownload = botaoTodos => {
        let valor = false;
        if (botaoTodos.checked) {
            valor = true;
        }
        const lista = document.querySelectorAll('#bloco_app_download .input_download input');
        lista.forEach(item => {
            item.checked = valor;
        });
    };
    const verificarSeMarcouTodosDownload = botaoTodos => {
        const todos = document.querySelectorAll('#bloco_app_download .input_download input').length;
        const checked = document.querySelectorAll('#bloco_app_download .input_download input:checked').length;
        if (todos == checked) {
            botaoTodos.checked = true;
            return;
        }
        botaoTodos.checked = false;
    };

    /*
    |--------------------------------------------------------------------------
    | ABRIR FILTRO
    |--------------------------------------------------------------------------
    */
    const botaoFiltrar = document.querySelector('#botao_filtrar_abrir');
    if (botaoFiltrar) {
        const filtroOption = {
            method: 'GET',
        };
        let filtroOrdem = '';
        if (buscaGeralOrdem && buscaGeralOrdem.value != '') {
            filtroOrdem = '?ordem=' + buscaGeralOrdem.value;
        }
        const paginaFiltro = new Pagina(
            'Filtrar dados',
            LINK + '/app/filtrar/' + APP + filtroOrdem,
            filtroOption,
            true,
            true,
            loadingPaginaFiltrar
        );
        botaoFiltrar.addEventListener('click', () => {
            paginaFiltro.abrir();
        });
        blocoFwPagina.addEventListener('click', e => {
            if (e.target.classList.contains('botao_fechar_filtro') || e.target.closest('.botao_fechar_filtro')) {
                paginaFiltro.fechar();
            } else if (e.target.classList.contains('botao_filtrar_geral') || e.target.closest('.botao_filtrar_geral')) {
                blocoFwPagina.querySelector('.botao_filtrar_geral').classList.add('aguarde');
            }
        });
    }
    const botaoFiltroLimpar = document.querySelectorAll('.botao_filtro_limpar');
    if (botaoFiltroLimpar.length > 0) {
        botaoFiltroLimpar.forEach(botao => {
            botao.addEventListener('click', () => {
                limparFiltro(botao);
            });
        });
    }
    const limparFiltro = botao => {
        Loading.show();
        const indice = botao.getAttribute('data-indice');
        let request = uri().split('?')[1];
        if (request == undefined) {
            window.location.assign(LINK + '/app/' + APP);
            return;
        }
        let filtro = '';
        let ordem = '';
        request = request.split('&');
        request.forEach(chave => {
            if (/^ordem\=/.test(chave)) {
                ordem = chave.replace('ordem=', '');
            } else if (/^filtro\=/.test(chave)) {
                filtro = chave.replace('filtro=', '');
            }
        });
        window.location.assign(
            LINK + '/app/remover-filtro/' + APP + '?filtro=' + filtro + '&ordem=' + ordem + '&indice=' + indice
        );
    };

    /*
    |--------------------------------------------------------------------------
    | COPIAR
    |--------------------------------------------------------------------------
    */
    const listaCopiar = document.querySelectorAll('.botao_copiar');
    if (listaCopiar.length > 0) {
        listaCopiar.forEach(botao => {
            botao.addEventListener('click', e => {
                e.preventDefault();
                const blocoCopiar = botao.closest('.bloco_copiar');
                if (!blocoCopiar) {
                    return;
                }

                let range, copiar;
                if (document.selection) {
                    range = document.body.createTextRange();
                    range.moveToElementText(blocoCopiar);
                    range.select();
                    copiar = document.execCommand('copy');
                    range = document.body.createTextRange();
                    range.moveToElementText();
                } else if (window.getSelection) {
                    range = document.createRange();
                    range.selectNode(blocoCopiar);
                    window.getSelection().removeAllRanges();
                    window.getSelection().addRange(range);
                    copiar = document.execCommand('copy');
                    window.getSelection().removeAllRanges();
                }

                if (copiar) {
                    Alerta.notificacao('Copiado com sucesso.', true);
                } else {
                    Alerta.notificacao('Erro ao copiar, seu navegador pode não ter suporte a essa função.', false);
                }
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CONTROLE DE BOTÕES
    |--------------------------------------------------------------------------
    */
    if (botaoAdd) {
        botaoAdd.addEventListener('mouseover', () => {
            botaoAdd.classList.add('hover');
            if (botaoDeletar) {
                botaoDeletar.classList.add('hover_add');
            }
        });
        botaoAdd.addEventListener('mouseout', () => {
            botaoAdd.classList.remove('hover');
            if (botaoDeletar) {
                botaoDeletar.classList.remove('hover_add');
            }
        });
    }
    if (botaoDeletar) {
        botaoDeletar.addEventListener('mouseover', () => {
            botaoDeletar.classList.add('hover');
        });
        botaoDeletar.addEventListener('mouseout', () => {
            botaoDeletar.classList.remove('hover');
        });
    }

    /*
    |--------------------------------------------------------------------------
    | COLOCA OPACIDADE NOS ITENS
    |--------------------------------------------------------------------------
    */
    listaItem.forEach(item => {
        item.addEventListener('mouseover', () => {
            blocoGeral.classList.add('opaco');
        });
        item.addEventListener('mouseout', () => {
            blocoGeral.classList.remove('opaco');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | MARCAR OU DESMARCAR TODOS
    |--------------------------------------------------------------------------
    */
    if (botaoMarcaTodos) {
        botaoMarcaTodos.addEventListener('click', () => {
            if (botaoMarcaTodos.checked) {
                marcarTodosItens();
            } else {
                desmarcarTodosItens();
            }
        });
    }
    const marcarTodosItens = () => {
        botaoDeletar.style.display = 'flex';
        listaBotaoCheckbox.forEach(item => {
            item.checked = true;
        });
        listaTodasLinhas.forEach(item => {
            item.classList.add('hover');
        });
    };
    const desmarcarTodosItens = () => {
        botaoDeletar.style.display = 'none';
        listaBotaoCheckbox.forEach(item => {
            item.checked = false;
        });
        listaTodasLinhas.forEach(item => {
            item.classList.remove('hover');
        });
    };

    listaBotaoCheckbox.forEach(item => {
        item.addEventListener('change', () => {
            const marcado = document.querySelectorAll('#bloco_app_lista .linha .checkbox input:checked').length;
            const total = listaBotaoCheckbox.length;
            if (marcado == total) {
                botaoMarcaTodos.checked = true;
            } else {
                botaoMarcaTodos.checked = false;
            }
            if (marcado > 0) {
                botaoDeletar.style.display = 'flex';
            } else {
                botaoDeletar.style.display = 'none';
            }
        });
    });

    /*
    |--------------------------------------------------------------------------
    | DELETAR REGISTROS
    |--------------------------------------------------------------------------
    */
    if (botaoDeletar) {
        botaoDeletar.addEventListener('click', async () => {
            const lista = document.querySelectorAll('.lista.geral .input_checkbox input:checked');
            if (lista.length == 0) {
                Alerta.notificacao('Sem informações para serem deletadas.', false);
                return;
            } else if (
                await Alerta.confirmar(
                    'Deletar itens!',
                    'Tem certeza que deseja deletar os itens selecionados? Essa ação não poderá ser desfeita.',
                    false
                )
            ) {
                deletarItensSelecionados(lista);
            }
        });
    }
    const deletarItensSelecionados = async lista => {
        Loading.show();

        let body = new FormData();
        lista.forEach(item => {
            body.append('id[]', item.value);
        });
        body.append('form_system_hash', hashDeletar);
        body.append('form_system_validacao', '');
        const response = await fetch(LINK + '/app/deletar/' + APP, {
            method: 'POST',
            body,
        });

        if (response.status == 204) {
            Alerta.notificacao('Dados deletados com sucesso!', true);
            setTimeout(() => {
                window.location.reload();
            }, 2000);
            return;
        }

        Loading.hide();
        fetchNotificacaoErro(response, 'Ocorreu um erro ao deletar os registros.');
    };

    /*
    |--------------------------------------------------------------------------
    | ORDENAR LISTA
    |--------------------------------------------------------------------------
    */
    const ordenarItens = async () => {
        const lista = document.querySelectorAll('#bloco_app_lista .bloco_lista .linha input[name=id]');

        const body = {
            id: [],
            pagina: paginaAtual,
            /* eslint-disable */
            form_system_hash: hashOrdem,
            form_system_validacao: '',
            /* eslint-enable */
        };
        lista.forEach(item => {
            body.id.push(item.value);
        });

        const resposta = await ajaxPost(
            LINK + '/app/ordem/' + APP,
            body,
            'Erro ao ordenar, por favor, tente novamente.'
        );
        if (false === resposta) {
            return;
        }
    };

    const blocoOrdemExiste = document.querySelector('#bloco_app_lista .bloco_lista .linha .drag');
    if (blocoOrdemExiste) {
        const blocoOrdemLista = document.querySelector('#bloco_app_lista .bloco_lista');
        new DragDrop()
            .bloco(blocoOrdemLista)
            .item('.linha')
            .botao('.drag')
            .eventoFim(e => {
                ordenarItens();
            })
            .iniciar();
    }

    /*
    |--------------------------------------------------------------------------
    | BOTÂO DE STATUS
    |--------------------------------------------------------------------------
    */
    const listaBotaoStatus = document.querySelectorAll('.botao_status');
    if (listaBotaoStatus.length > 0) {
        listaBotaoStatus.forEach(botao => {
            const input = botao.querySelector('input');

            if (!input) {
                return;
            }

            input.addEventListener('change', async e => {
                e.preventDefault();
                const id = botao.getAttribute('data-id');
                const sim = botao.getAttribute('data-sim');
                const nao = botao.getAttribute('data-nao');

                await atualizarBotaoStatus(e, {
                    id,
                    sim,
                    nao,
                });
            });
        });
    }

    const atualizarBotaoStatus = async (e, { id, sim, nao }) => {
        const status = e.target.checked ? sim : nao;

        Loading.show();

        const resposta = await ajaxPost(LINK + '/app/ajax/' + APP, {
            indice: 'status-atualizar',
            id,
            status,
        });

        Loading.hide();

        if (resposta == false) {
            Alerta.notificacao('Ocorreu um erro ao atualizar o status, por favor, tente novamente.', false);
            return;
        }

        Alerta.notificacao('Status atualizado com sucesso.', true);
        e.target.parentElement.parentElement.setAttribute('data-ajuda', status);
        e.target.parentElement.parentElement.setAttribute('data-status', status);
    };
});

const loadingPaginaFiltrar = () => {
    // Abrir/fechar bloco
    const blocoListaMais = $$('.bloco_row_mais');
    const adicionarAcaoBlocoMais = bloco => {
        const botao = $('.botao_mais', bloco);
        botao.evento('click', () => {
            bloco.classe('bloco_row_mais_aberto');
        });
    };

    if (blocoListaMais.length > 0) {
        for (const bloco of blocoListaMais) {
            adicionarAcaoBlocoMais(bloco);
        }
    }

    // Marcar todos
    const blocoListaMarcarTodos = $$('.botao_filtrar_marcar_todos input');
    const adicionarAcaoBlocoTodos = input => {
        const bloco = input.closest('.bloco_row');
        if (!bloco) {
            return;
        }

        const lista = $$('.bloco_row_lista .input_checkbox input', bloco);
        const quantidade = lista.length;
        input.evento('change', () => {
            lista.marcar(input.marcar());
        });
        lista.evento('change', () => {
            if ($$('.bloco_row_lista .input_checkbox input:checked', bloco).length == quantidade) {
                input.marcar(true);
                return;
            }
            input.marcar(false);
        });
    };

    if (blocoListaMarcarTodos.length > 0) {
        for (const input of blocoListaMarcarTodos) {
            adicionarAcaoBlocoTodos(input);
        }
    }
};
