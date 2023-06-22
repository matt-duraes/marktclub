window.addEventListener('load', () => {
    const blocoMenuLista = $('#bloco_menu');
    const blocoGrupoMedelo = pegarElementoModelo('bloco_grupo_modelo');
    const botaoAdicionarGrupo = $('#botao_adicionar_grupo');

    const blocoOpcaoGrupo = $('#bloco_opcao_grupo');
    const botaoGrupoRenomear = $('#bloco_opcao_grupo .botao_grupo_renomear');

    const blocoOpcaoRequisicao = $('#bloco_opcao_requisicao');
    const blocoRequisicaoModelo = pegarElementoModelo('bloco_requisicao_modelo');
    const botaoRequisicaoAdicionar = $('#bloco_opcao_grupo .botao_requisicao_adicionar');

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

    const atualizarNomeGrupoEnter = e => {
        if (e.key == 'Enter' && e.target.value == '') {
            e.preventDefault();
            Alerta.notificacao('Você deve passar um nome para o grupo.', false);
        } else if (e.key == 'Enter' && e.target.value == '') {
            e.preventDefault();
            atualizarNomeGrupo(e.target);
        }
    };
    const atualizarNomeGrupoChange = e => {
        atualizarNomeGrupo(e.target);
    };
    const adicionarEventoInputGrupo = input => {
        input.addEventListener('change', atualizarNomeGrupoChange);
        input.addEventListener('keydown', atualizarNomeGrupoEnter);
    };

    /*
    |--------------------------------------------------------------------------
    | ACAO AO CLICAR NO MENU
    |--------------------------------------------------------------------------
    */
    blocoMenuLista.addEventListener('click', e => {
        const target = e.target;
        const blocoGrupo = target.closest('.grupo');

        const clickOpcao = target.closest('.botao_opcao_grupo') || target.classList.contains('botao_opcao_grupo');
        const clickNome = target.closest('.nome') || target.classList.contains('nome');
        if (clickOpcao) {
            abrirOpcaoGrupo(blocoGrupo);
        } else if (clickNome) {
            abrirFecharGrupo(blocoGrupo);
        }
    });
    const abrirFecharGrupo = grupo => {
        const input = grupo.querySelector('.nome input');
        if (input.classList.contains('display_none')) {
            grupo.classList.toggle('fechado');
        }
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR ROTA
    |--------------------------------------------------------------------------
    */
    const menuRotaLista = document.querySelectorAll('.bloco_menu .request');
    menuRotaLista.forEach(botao => {
        botao.addEventListener('click', () => {
            const id = botao.getAttribute('data-id');
            const metodo = botao.getAttribute('data-metodo');
            const uri = botao.getAttribute('data-uri');
            // adicionarNovaAba(id, metodo, uri);
        });
    });
    const menuGrupoLista = blocoMenuLista.querySelectorAll('.grupo');
    menuGrupoLista.forEach(grupo => {
        adicionarEventoInputGrupo(grupo);
    });

    /*
    |--------------------------------------------------------------------------
    | REQUISIÇÃO
    |--------------------------------------------------------------------------
    */
    const fecharOpcaoRequisicao = () => {
        const grupo = blocoMenuLista.querySelector('.grupo.ativo');
        blocoOpcaoGrupo.classList.add('display_none');
        if (!grupo) {
            return;
        }
        grupo.classList.remove('ativo');
    };
    const abrirOpcaoRequisicao = grupo => {
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
    | GRUPO
    |--------------------------------------------------------------------------
    */
    const renomearGrupo = grupo => {
        grupo.classList.add('ativo');
        setTimeout(() => {
            blocoOpcaoGrupo.classList.add('display_none');
        }, 40);
        const blocoTexto = grupo.querySelector('.nome p');
        const inputTexto = grupo.querySelector('.nome input');
        blocoTexto.classList.add('display_none');
        inputTexto.classList.remove('display_none');
        inputTexto.focus();
        inputTexto.select();
    };
    botaoGrupoRenomear.addEventListener('click', () => {
        renomearGrupo(blocoMenuLista.querySelector('.grupo.ativo'));
    });
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
    | ADICIONAR NOVO GRUPO
    |--------------------------------------------------------------------------
    */
    botaoAdicionarGrupo.addEventListener('click', () => {
        const clone = blocoGrupoMedelo.cloneNode(true);
        blocoMenuLista.prepend(clone);
        clone.scrollIntoView();

        const inputNome = clone.querySelector('.input_nome');
        inputNome.focus();
        adicionarEventoInputGrupo(inputNome);
        inputNome.addEventListener('blur', removerGrupoNovoVazio);
    });
    const atualizarNomeGrupo = inputNome => {
        const grupo = inputNome.closest('.grupo');
        if (inputNome.value == '' && grupo.classList.contains('novo')) {
            return;
        } else if (inputNome.value == '') {
            Alerta.notificacao('Você deve passar um nome para o grupo.', false);
            inputNome.value.focus();
            return;
        }
        const textoNome = grupo.querySelector('.nome p');
        textoNome.innerText = inputNome.value;
        inputNome.classList.add('display_none');
        textoNome.classList.remove('display_none');
    };
    const removerGrupoNovoVazio = e => {
        const inputNome = e.target;
        const grupo = inputNome.closest('.grupo');
        if (!grupo.classList.contains('novo')) {
            return;
        }
        const blocoNome = grupo.querySelector('.nome');
        blocoNome.classList.remove('hover');
        grupo.classList.remove('novo');

        inputNome.removeEventListener('blur', removerGrupoNovoVazio);
        if (inputNome.value == '') {
            inputNome.removeEventListener('change', atualizarNomeGrupoChange);
            inputNome.removeEventListener('keydown', atualizarNomeGrupoEnter);
            grupo.parentNode.removeChild(grupo);
        }
    };
});
