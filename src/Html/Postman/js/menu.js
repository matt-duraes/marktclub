window.addEventListener('load', () => {
    const blocoMenuGrupoMedelo = pegarElementoModelo('bloco_menu_grupo_modelo');
    const botaoAdicionarGrupo = $('#botao_adicionar_grupo');

    const blocoOpcaoGrupo = $('#bloco_opcao_grupo');
    const botaoGrupoRenomear = $('#bloco_opcao_grupo .botao_grupo_renomear');
    const botaoGrupoDeletar = $('#bloco_opcao_grupo .botao_grupo_deletar');

    const blocoOpcaoRequisicao = $('#bloco_opcao_requisicao');
    const blocoMenuRequisicaoModelo = pegarElementoModelo('bloco_menu_requisicao_modelo');
    const botaoRequisicaoAdicionar = $('#bloco_opcao_grupo .botao_requisicao_adicionar');
    const botaoRequisicaoRenomear = $('#bloco_opcao_requisicao .botao_requisicao_renomear');
    const botaoRequisicaoDeletar = $('#bloco_opcao_requisicao .botao_requisicao_deletar');

    const blocoMenuSemRequisicao = pegarElementoModelo('bloco_menu_sem_requisicao');

    body.addEventListener('click', e => {
        // Fechar bloco Grupo
        const clickBlocoGrupo =
            (e.target.closest('#bloco_opcao_grupo') ||
                e.target.getAttribute('id') == 'bloco_opcao_grupo' ||
                e.target.closest('.botao_opcao_grupo') ||
                e.target.classList.contains('botao_opcao_grupo')) &&
            !blocoOpcaoGrupo.classList.contains('display_none');

        if (!clickBlocoGrupo) {
            fecharOpcaoGrupo();
        }

        // Fechar bloco Requisicao
        const clickBlocoRequisicao =
            (e.target.closest('#bloco_opcao_requisicao') ||
                e.target.getAttribute('id') == 'bloco_opcao_requisicao' ||
                e.target.closest('.botao_opcao_requisicao') ||
                e.target.classList.contains('botao_opcao_requisicao')) &&
            !blocoOpcaoRequisicao.classList.contains('display_none');

        if (!clickBlocoRequisicao) {
            fecharOpcaoRequisicao();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | ACAO AO CLICAR NO MENU
    |--------------------------------------------------------------------------
    */
    blocoMenuLista.addEventListener('click', e => {
        const target = e.target;
        const blocoGrupo = target.closest('.grupo');

        const clickOpcaoRequisicao =
            target.closest('.botao_opcao_requisicao') || target.classList.contains('botao_opcao_requisicao');
        const clickRequisicao = target.closest('.requisicao') || target.classList.contains('requisicao');
        const clickOpcaoGrupo = target.closest('.botao_opcao_grupo') || target.classList.contains('botao_opcao_grupo');
        const clickGrupo = target.closest('.nome') || target.classList.contains('nome');

        if (clickOpcaoRequisicao) {
            abrirOpcaoRequisicao(target.closest('.requisicao'));
        } else if (clickRequisicao) {
            abrirRequisicao(
                target.classList.contains('.requisicao')
                    ? target.classList.contains('.requisicao')
                    : target.closest('.requisicao')
            );
        } else if (clickOpcaoGrupo) {
            abrirOpcaoGrupo(blocoGrupo);
        } else if (clickGrupo) {
            abrirFecharGrupo(blocoGrupo);
        }
    });
    const abrirRequisicao = requisicao => {
        const requisicaoAberto = blocoMenuLista.querySelector('.requisicao.aberto');
        if (requisicaoAberto) {
            requisicaoAberto.classList.remove('aberto');
        }
        requisicao.classList.add('aberto');
        const grupo = requisicao.closest('.grupo');
        abrirNovaAba(requisicao, grupo);
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR/FECHAR MENU
    |--------------------------------------------------------------------------
    */
    const abrirFecharGrupo = grupo => {
        if (!grupo.classList.contains('ativo') && !grupo.classList.contains('update')) {
            grupo.classList.toggle('fechado');
        }
    };

    /*
    |--------------------------------------------------------------------------
    | RENOMEAR GRUPO/MENU
    |--------------------------------------------------------------------------
    */
    const renomearNomeMenu = e => {
        if (e.type == 'keyup' && e.key == 'Enter') {
            e.target.blur();
            e.preventDefault();
            return;
        } else if (e.type == 'keyup') {
            return;
        }

        const input = e.target;
        const acao = input.classList.contains('nome_requisicao') ? 'requisicao' : 'grupo';
        const bloco = acao == 'grupo' ? input.closest('.grupo') : input.closest('.requisicao');
        const texto = bloco.querySelector('.bloco_nome');
        const valor = input.value.trim();

        if (bloco.classList.contains('novo') && valor == '') {
            removerMenuItem(bloco, acao != 'grupo' ? input.closest('.grupo') : '');
            return;
        } else if (valor == '' || valor == bloco.getAttribute('data-nome')) {
            resetarNomeMenu(bloco, texto, input);
            return;
        }

        atualizarNomeMenu(bloco, texto, input, acao);
    };
    const resetarNomeMenu = (bloco, texto, input) => {
        const valor = bloco.getAttribute('data-nome');
        input.value = valor;
        finalizarMudancaNomeMenu(bloco, texto, valor);
    };
    const atualizarNomeMenu = async (bloco, texto, input, acao) => {
        const valor = input.value.trim();
        const novo = bloco.classList.contains('novo') ? 'sim' : 'nao';
        const eGrupo = acao == 'grupo';
        const pai = bloco.closest('.grupo');
        const resposta = await post(
            '__postman',
            {
                id: bloco.getAttribute(eGrupo ? 'data-nome' : 'data-id'),
                acao: 'atualizar-nome-' + acao,
                pai: pai ? pai.getAttribute('data-nome') : '',
                nome: valor,
                novo: novo,
            },
            'Ocorreu um erro a atualizar o nome, por favor, tente novamente.'
        );
        if (false === resposta && novo == 'sim') {
            removerMenuItem(bloco);
            return;
        } else if (false === resposta) {
            resetarNomeMenu(bloco, texto, input);
            return;
        }
        bloco.setAttribute('data-nome', valor);
        if (acao == 'requisicao') {
            bloco.setAttribute('data-id', resposta.dado.id);
            bloco.setAttribute('data-metodo', resposta.dado.metodo);
        }
        if (acao == 'requisicao' && bloco.classList.contains('novo')) {
            bloco.setAttribute('id', resposta.dado.id + '_menu');
            bloco.classList.remove('novo');
        } else if (eGrupo && bloco.classList.contains('novo')) {
            bloco.classList.remove('novo');
        }
        finalizarMudancaNomeMenu(bloco, texto, valor);
    };
    const finalizarMudancaNomeMenu = (bloco, texto, valor) => {
        bloco.classList.remove('ativo');
        bloco.classList.remove('update');
        texto.innerText = valor;
    };

    /*
    |--------------------------------------------------------------------------
    | REMOVER ITEM MENU
    |--------------------------------------------------------------------------
    */
    botaoRequisicaoDeletar.addEventListener('click', () => {
        const blocoRequisicao = blocoMenuLista.querySelector('.requisicao.ativo');
        blocoOpcaoRequisicao.classList.add('display_none');
        deletarNomeMenu(blocoRequisicao, 'requisicao');
    });

    botaoGrupoDeletar.addEventListener('click', () => {
        const blocoGrupo = blocoMenuLista.querySelector('.grupo.ativo');
        blocoOpcaoGrupo.classList.add('display_none');
        deletarNomeMenu(blocoGrupo, 'grupo');
    });

    const deletarNomeMenu = async (bloco, acao) => {
        if (!(await Alerta.confirmar('Deletar item', 'Essa ação não poderá ser desfeita', false))) {
            return;
        }
        const pai = bloco.closest('.grupo');
        const resposta = await post(
            '__postman',
            {
                id: bloco.getAttribute(acao == 'grupo' ? 'data-nome' : 'data-id'),
                pai: pai ? pai.getAttribute('data-nome') : '',
                acao: 'deletar-' + acao,
            },
            'Ocorreu um erro a atualizar o nome, por favor, tente novamente.'
        );
        if (false === resposta) {
            return;
        }
        removerMenuItem(bloco, pai);
    };

    const removerMenuItem = (bloco, grupo) => {
        const lista = bloco.querySelectorAll('.bloco_input');
        lista.forEach(input => {
            input.removeEventListener('blur', renomearNomeMenu);
            input.removeEventListener('keyup', renomearNomeMenu);
        });
        bloco.parentNode.removeChild(bloco);
        if (!grupo) {
            return;
        }
        if (grupo.querySelectorAll('.requisicao').length > 0) {
            return;
        }
        const clone = blocoMenuSemRequisicao.cloneNode(true);
        grupo.appendChild(clone);
    };

    /*
    |--------------------------------------------------------------------------
    | ADICIONAR EVENTOS NO MENU
    |--------------------------------------------------------------------------
    */
    const adicionarEventoMenuInput = input => {
        input.addEventListener('blur', renomearNomeMenu);
        input.addEventListener('keyup', renomearNomeMenu);
    };
    const menuNomeLista = blocoMenuLista.querySelectorAll('.bloco_input');
    menuNomeLista.forEach(input => {
        adicionarEventoMenuInput(input);
    });

    /*
    |--------------------------------------------------------------------------
    | BLOCO OPCAO REQUISICAO
    |--------------------------------------------------------------------------
    */
    const fecharOpcaoRequisicao = () => {
        const requisicao = blocoMenuLista.querySelector('.requisicao.ativo');
        blocoOpcaoRequisicao.classList.add('display_none');
        if (!requisicao) {
            return;
        }
        requisicao.classList.remove('ativo');
    };
    const abrirOpcaoRequisicao = requisicao => {
        blocoOpcaoRequisicao.classList.remove('display_none');
        const posicaoBloco = blocoOpcaoRequisicao.getBoundingClientRect();
        const posicaoRequisicao = requisicao.getBoundingClientRect();
        const posicaoMenu = blocoMenuLista.getBoundingClientRect();

        const blocoRequisicaoAtivo = blocoMenuLista.querySelector('.requisicao.ativo');
        if (blocoRequisicaoAtivo) {
            blocoRequisicaoAtivo.classList.remove('ativo');
        }

        let blocoTopo = posicaoRequisicao.top + posicaoRequisicao.height;
        if (window.innerHeight / 2 < blocoTopo) {
            blocoTopo = posicaoRequisicao.top - posicaoBloco.height;
        }
        blocoOpcaoRequisicao.style.top = blocoTopo + 'px';
        blocoOpcaoRequisicao.style.left = posicaoMenu.width - posicaoBloco.width - 5 + 'px';
        requisicao.classList.add('ativo');
    };

    /*
    |--------------------------------------------------------------------------
    | RENOMEAR
    |--------------------------------------------------------------------------
    */
    botaoRequisicaoRenomear.addEventListener('click', () => {
        const blocoRequisicao = blocoMenuLista.querySelector('.requisicao.ativo');
        const inputUri = blocoRequisicao.querySelector('input');
        abrirBlocoRenomear(blocoRequisicao, blocoOpcaoRequisicao, inputUri);
    });

    botaoGrupoRenomear.addEventListener('click', () => {
        const blocoGrupo = blocoMenuLista.querySelector('.grupo.ativo');
        const inputTexto = blocoGrupo.querySelector('.nome input');
        abrirBlocoRenomear(blocoGrupo, blocoOpcaoGrupo, inputTexto);
    });
    const abrirBlocoRenomear = (bloco, opcao, input) => {
        setTimeout(() => {
            opcao.classList.add('display_none');
        }, 40);

        if (!bloco) {
            return;
        }
        bloco.classList.add('ativo');
        bloco.classList.add('update');
        input.focus();
        input.select();
    };

    /*
    |--------------------------------------------------------------------------
    | BLOCO OPCAO GRUPO
    |--------------------------------------------------------------------------
    */
    const fecharOpcaoGrupo = () => {
        const grupo = blocoMenuLista.querySelector('.grupo.ativo');
        blocoOpcaoGrupo.classList.add('display_none');
        if (!grupo) {
            return;
        }
        grupo.classList.remove('ativo');
    };
    const abrirOpcaoGrupo = grupo => {
        blocoOpcaoGrupo.classList.remove('display_none');
        const blocoNome = grupo.querySelector('.nome');
        const posicaoBloco = blocoOpcaoGrupo.getBoundingClientRect();
        const posicaoNome = blocoNome.getBoundingClientRect();
        const posicaoMenu = blocoMenuLista.getBoundingClientRect();

        const blocoGrupoAtivo = blocoMenuLista.querySelector('.grupo.ativo');
        if (blocoGrupoAtivo) {
            blocoGrupoAtivo.classList.remove('ativo');
        }

        let blocoTopo = posicaoNome.top + posicaoNome.height - 5;
        if (window.innerHeight / 2 < blocoTopo) {
            blocoTopo = posicaoNome.top - posicaoBloco.height + 5;
        }
        blocoOpcaoGrupo.style.top = blocoTopo + 'px';
        blocoOpcaoGrupo.style.left = posicaoMenu.width - posicaoBloco.width - 5 + 'px';

        grupo.classList.add('ativo');
        grupo.classList.remove('fechado');
    };

    /*
    |--------------------------------------------------------------------------
    | ADICIONAR GRUPO
    |--------------------------------------------------------------------------
    */
    botaoAdicionarGrupo.addEventListener('click', () => {
        const clone = blocoMenuGrupoMedelo.cloneNode(true);
        blocoMenuLista.prepend(clone);
        clone.scrollIntoView();

        const input = clone.querySelector('.input_nome');
        input.focus();
        adicionarEventoMenuInput(input);
    });

    /*
    |--------------------------------------------------------------------------
    | ADICIONAR REQUEST
    |--------------------------------------------------------------------------
    */
    botaoRequisicaoAdicionar.addEventListener('click', () => {
        const grupo = blocoMenuLista.querySelector('.grupo.ativo');
        if (!grupo) {
            return;
        }
        const clone = blocoMenuRequisicaoModelo.cloneNode(true);
        const vazio = grupo.querySelector('.requisicao_vazio');
        if (vazio) {
            vazio.parentNode.removeChild(vazio);
        }
        grupo.appendChild(clone);
        clone.scrollIntoView();
        const input = clone.querySelector('input');
        input.focus();
        blocoOpcaoGrupo.classList.add('display_none');
        setTimeout(() => {
            clone.classList.add('ativo');
        }, 40);

        adicionarEventoMenuInput(input);
    });
});
