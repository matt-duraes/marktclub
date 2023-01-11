const historicoLoad = () => {
    const inputHistorico = document.querySelector('#input_historico_mensagem textarea');
    if (!inputHistorico) {
        return;
    }

    const app = document.querySelector('#input_historico_app').value;
    const relacionado = document.querySelector('#input_historico_relacionado').value;

    const inputPesquisa = document.querySelector('#input_historico_pesquisa');
    const inputDataDe = document.querySelector('#input_historico_data_de');
    const inputDataAte = document.querySelector('#input_historico_data_ate');

    const usuarioNome = document.querySelector('#USUARIO_NOME').value;
    const usuarioImagem = document.querySelector('#USUARIO_IMAGEM').value;

    const historicoLista = document.querySelector('#bloco_historico_lista');
    const botaoBuscar = document.querySelector('#botao_buscar_historico');
    const botaoCarregarMais = document.querySelector('#botao_historico_carregar_mais');

    Calendario.init({
        de: 'input_historico_data_de',
        ate: 'input_historico_data_ate',
    });

    /*
    |--------------------------------------------------------------------------
    | BUSCAR MENSAGEM
    |--------------------------------------------------------------------------
    */
    inputPesquisa.addEventListener('keyup', e => {
        if (e.key == 'Enter') {
            buscarMensagem(1);
        }
    });
    inputDataDe.addEventListener('keyup', e => {
        if (e.key == 'Enter') {
            buscarMensagem(1);
        }
    });
    inputDataAte.addEventListener('keyup', e => {
        if (e.key == 'Enter') {
            buscarMensagem(1);
        }
    });
    botaoBuscar.addEventListener('click', () => {
        buscarMensagem(1);
    });

    let paginaAtual;
    let existeBusca;
    const buscarMensagem = async pagina => {
        existeBusca = false;
        if (pagina == 1) {
            historicoLista.innerHTML = '<div class="zero">Carregando mensagens</div>';
        }
        paginaAtual = pagina;

        let query = `?pagina=${pagina}&app=${app}&relacionado=${relacionado}`;
        if (inputDataDe.value != '') {
            existeBusca = true;
            query += '&data_de=' + inputDataDe.value;
        }
        if (inputDataAte.value != '') {
            existeBusca = true;
            query += '&data_ate=' + inputDataAte.value;
        }
        if (inputPesquisa.value != '') {
            existeBusca = true;
            query += '&pesquisa=' + inputPesquisa.value;
        }

        const resposta = await fetch(LINK + '/historico' + query, {
            method: 'GET',
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        if (resposta.status == 200 && json.dado.lista != undefined) {
            carregarListaMensagem(pagina, json.dado.lista);
            if (json.dado.pagina > pagina) {
                botaoCarregarMais.classList.add('display_flex');
            }
            return;
        }
    };
    buscarMensagem(1);

    const carregarListaMensagem = (pagina, lista) => {
        if (pagina == 1 && Object.keys(lista).length == 0 && existeBusca) {
            historicoLista.innerHTML = '<div class="zero">Sem mensagens para a busca realizada</div>';
            return;
        } else if (pagina == 1 && Object.keys(lista).length == 0) {
            adicionarBlocoSemMensagem();
            return;
        } else if (pagina == 1) {
            historicoLista.innerHTML = '';
        }

        let classe;
        lista.forEach(item => {
            if (item.tipo == 'hoje' && !historicoLista.querySelector(`.bloco_data_${item.hash}`)) {
                historicoLista.insertAdjacentHTML(
                    'beforeend',
                    `<div id="bloco_historico_hoje" class="item_geral bloco_data bloco_data_${item.hash}">Hoje</div>`
                );
                adicionarTextoAjuda(historicoLista.querySelector(`.bloco_data_${item.hash}`), item.data);
            } else if (item.tipo == 'data' && !historicoLista.querySelector(`.bloco_data_${item.hash}`)) {
                historicoLista.insertAdjacentHTML(
                    'beforeend',
                    `<div class="item_geral bloco_data bloco_data_${item.hash}">${item.social}</div>`
                );
                adicionarTextoAjuda(historicoLista.querySelector(`.bloco_data_${item.hash}`), item.data);
            } else if (item.tipo == 'mensagem') {
                classe = item.minha_mensagem ? 'minha_mensagem' : 'outra_mensagem';
                historicoLista.insertAdjacentHTML(
                    'beforeend',
                    `
                    <div class="item item_geral ${classe}">
                        <figure style="background-image: url(${item.imagem});"></figure>
                        <div class="dado">
                            <h2>${item.nome}</h2>
                            <div class="data">${item.hora}</div>
                        </div>
                        <p class="mensagem">${item.mensagem}</p>
                    </div>
                    `
                );
            }
        });
    };

    /*
    |--------------------------------------------------------------------------
    | CARREGAR MAIS
    |--------------------------------------------------------------------------
    */
    botaoCarregarMais.addEventListener('click', () => {
        botaoCarregarMais.classList.remove('display_flex');
        buscarMensagem(paginaAtual + 1);
    });

    /*
    |--------------------------------------------------------------------------
    | SALVAR HISTÓRICO
    |--------------------------------------------------------------------------
    */
    const salvarNovoHistorico = async () => {
        const mensagem = inputHistorico.value.trim();
        if (mensagem == '') {
            Alerta.notificacao('Você deve enviar uma mensagem para salvar o histórico.', false);
            return;
        }

        Loading.show();

        const body = new FormData();
        body.append('app', app);
        body.append('relacionado', relacionado);
        body.append('mensagem', mensagem);

        const resposta = await fetch(LINK + '/historico', {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();
        if (resposta.status == 201) {
            inputHistorico.value = '';
            inputHistorico.style.height = '1.5em';
            adicionarNovaMensagem(json.dado.id, json.dado.mensagem);
            return;
        }

        Alerta.notificacao(
            json.erro != undefined && json.erro.mensagem != undefined
                ? json.erro.mensagem
                : 'Ocorreu um erro ao enviar seu histórico.',
            false
        );
    };

    let contadorNovaMensagem = 0;
    const adicionarNovaMensagem = (id, mensagem) => {
        const blocoSemMensagem = historicoLista.querySelector('.sem_mensagem');
        if (blocoSemMensagem) {
            blocoSemMensagem.parentNode.removeChild(blocoSemMensagem);
        }

        contadorNovaMensagem++;
        const idMensagem = 'bloco_nova_mensagem_' + contadorNovaMensagem;
        const html = `
            <div class="item minha_mensagem" id="${idMensagem}">
                <figure style="background-image: url(${usuarioImagem});"></figure>
                <div class="dado">
                    <h2>${usuarioNome}</h2>
                    <div class="data">Agora</div>
                </div>
                <div class="deletar">
                    <div class="loading"></div>
                    ${Icone.deletar()}
                </div>
                <p class="mensagem">${mensagem.replace(/\n/g, '<br>')}</p>
            </div>
        `;

        let blocoHoje = historicoLista.querySelector('#bloco_historico_hoje');
        if (!blocoHoje) {
            historicoLista.insertAdjacentHTML(
                'afterbegin',
                '<div class="item_geral bloco_data" id="bloco_historico_hoje">Hoje</div>'
            );
            blocoHoje = historicoLista.querySelector('#bloco_historico_hoje');
        }
        blocoHoje.insertAdjacentHTML('afterend', html);

        const blocoMensagem = historicoLista.querySelector('#' + idMensagem);
        const botaoDeletar = blocoMensagem.querySelector('.deletar');
        botaoDeletar.addEventListener('click', () => {
            deletarHistoricoEnviado(blocoMensagem, id);
        });
        setTimeout(() => {
            botaoDeletar.parentNode.removeChild(botaoDeletar);
        }, 20000);
    };

    /*
    |--------------------------------------------------------------------------
    | DELETAR HISTÓRICO
    |--------------------------------------------------------------------------
    */
    const deletarHistoricoEnviado = async (bloco, id) => {
        bloco.classList.add('display_none');
        const resposta = await fetch(LINK + '/historico/' + id, {
            method: 'DELETE',
        });
        if (resposta.status != 204) {
            Alerta.notificacao('Ocorreu um erro ao deletar a mensagem.', false);
            bloco.classList.remove('display_none');
            return;
        }

        bloco.parentNode.removeChild(bloco);
        if (historicoLista.querySelectorAll('.item').length == 0) {
            adicionarBlocoSemMensagem();
            return;
        }
        const itemGeral = historicoLista.querySelectorAll('.item_geral');
        console.log(itemGeral);
        if (itemGeral[0].classList.contains('bloco_data') && itemGeral[1].classList.contains('bloco_data')) {
            itemGeral[0].parentNode.removeChild(itemGeral[0]);
        }
    };

    /*
    |--------------------------------------------------------------------------
    | GERAL
    |--------------------------------------------------------------------------
    */
    const adicionarBlocoSemMensagem = () => {
        historicoLista.innerHTML = '<div class="zero sem_mensagem">Sem mensagens no momento</div>';
    };
    const adicionarTextoAjuda = (bloco, texto) => {
        bloco.addEventListener('mouseover', () => {
            Ajuda.show(bloco, texto);
        });
        bloco.addEventListener('mouseout', () => {
            Ajuda.hide();
        });
    };

    /*
|--------------------------------------------------------------------------
| MARCAR EQUIPE
|--------------------------------------------------------------------------
*/
    const blocoMarcar = document.querySelector('#bloco_historico_marcacao_equipe');

    const marcacaoEquipe = [];
    const listaUsuarioParaMarcar = blocoMarcar.querySelectorAll('li');
    listaUsuarioParaMarcar.forEach(usuario => {
        marcacaoEquipe.push(usuario.getAttribute('data-usuario'));
        usuario.addEventListener('click', () => {
            executarMarcacaoEquipe(usuario);
        });
        usuario.addEventListener('mouseover', () => {
            colocarHoverUsuario(usuario);
        });
    });
    const colocarHoverUsuario = usuario => {
        const hover = blocoMarcar.querySelector('li.hover');
        if (hover) {
            hover.classList.remove('hover');
        }
        usuario.classList.add('hover');
    };

    let marcacaoAtiva = false;
    let marcacaoNome = '';
    let marcacaoPosicaoInicial;
    let marcacaoPosicaoFinal;
    inputHistorico.addEventListener('focus', () => {
        fecharBlocoMarcar();
    });
    inputHistorico.addEventListener('blur', () => {
        setTimeout(() => {
            fecharBlocoMarcar();
        }, 200);
    });
    inputHistorico.addEventListener('click', () => {
        fecharBlocoMarcar();
    });
    inputHistorico.addEventListener('keydown', e => {
        const tecla = e.key;
        if (marcacaoAtiva && !e.shiftKey && e.key == 'Enter' && blocoMarcar.classList.contains('ativo')) {
            e.preventDefault();
            executarMarcacaoEquipe(blocoMarcar.querySelector('li.hover'));
            fecharBlocoMarcar();
        } else if (!e.shiftKey && e.key == 'Enter') {
            e.preventDefault();
            salvarNovoHistorico();
        } else if (tecla == '@') {
            marcacaoAtiva = true;
            marcacaoNome = '';
            blocoMarcar.classList.remove('ativo');
        } else if (!marcacaoAtiva) {
            return;
        } else if (tecla == 'ArrowDown' && blocoMarcar.classList.contains('ativo')) {
            e.preventDefault();
            selecionarProximoUsuario();
        } else if (tecla == 'ArrowUp' && blocoMarcar.classList.contains('ativo')) {
            e.preventDefault();
            selecionarUsuarioAnterior();
        } else if (
            (tecla == 'Backspace' && marcacaoNome.length == 0) ||
            (e.shiftKey && tecla == 'Enter') ||
            tecla == ' ' ||
            tecla == 'ArrowLeft' ||
            tecla == 'ArrowRight' ||
            tecla == 'ArrowDown' ||
            tecla == 'ArrowUp'
        ) {
            fecharBlocoMarcar();
        } else if (tecla == 'Enter') {
            e.preventDefault();
        } else if (/^[a-z0-9\.]{1}$/.test(tecla)) {
            marcacaoNome += tecla;
            buscarListaUsuario();
        } else if (tecla == 'Backspace') {
            marcacaoNome = marcacaoNome.slice(0, -1);
            buscarListaUsuario();
        }
    });

    const buscarListaUsuario = () => {
        const nomeExistente = [];
        const valor = inputHistorico.value;
        marcacaoEquipe.find(nome => {
            const regNome = new RegExp('@' + nome);
            if (regNome.test(valor)) {
                return;
            }

            const nomeComperacao = converterNome(marcacaoNome);
            const item = blocoMarcar.querySelector('li[data-usuario="' + nome + '"]');

            item.classList.remove('hover');
            item.classList.remove('ativo');
            if (nomeComperacao != '' && nome.startsWith(nomeComperacao)) {
                nomeExistente.push(nome);
                item.classList.add('ativo');
            }
        });
        if (nomeExistente.length > 0) {
            blocoMarcar.querySelector('li.ativo').classList.add('hover');
            blocoMarcar.classList.add('ativo');
            posicionarBlocoMarcar();
        } else {
            blocoMarcar.classList.remove('ativo');
        }
        marcacaoPosicaoInicial = inputHistorico.selectionStart;
        marcacaoPosicaoFinal = inputHistorico.selectionEnd;
    };
    const posicionarBlocoMarcar = () => {
        const posicaoHistorico = inputHistorico.getBoundingClientRect();
        const windowHeight = window.innerHeight;

        if (posicaoHistorico.top + posicaoHistorico.height > windowHeight / 2) {
            blocoMarcar.classList.remove('top');
            blocoMarcar.classList.add('bottom');
        } else {
            blocoMarcar.classList.add('top');
            blocoMarcar.classList.remove('bottom');
        }
    };
    const converterNome = nome => {
        return nome
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase();
    };

    const selecionarProximoUsuario = () => {
        processarAlteracaoUsuario('mais');
    };
    const selecionarUsuarioAnterior = () => {
        processarAlteracaoUsuario('menos');
    };
    const processarAlteracaoUsuario = acao => {
        const lista = blocoMarcar.querySelectorAll('li.ativo');
        const quantidade = lista.length - 1;
        const jaExisteAtivo = blocoMarcar.querySelector('li.hover');
        if (!jaExisteAtivo) {
            const adicionarPrimeiro = acao == 'mais' ? 0 : quantidade;
            lista[adicionarPrimeiro].classList.add('hover');
            return;
        }

        let i = acao == 'mais' ? 0 : quantidade;
        let proximo;
        for (;;) {
            if (!lista[i].classList.contains('hover')) {
                i = acao == 'mais' ? i + 1 : i - 1;
                continue;
            }

            lista[i].classList.remove('hover');
            if (acao == 'mais' && i == quantidade) {
                proximo = lista[0];
            } else if (acao == 'mais') {
                proximo = lista[i + 1];
            } else if (acao != 'mais' && i - 1 < 0) {
                proximo = lista[quantidade];
            } else if (acao != 'mais') {
                proximo = lista[i - 1];
            }
            proximo.classList.add('hover');
            break;
        }
    };

    const fecharBlocoMarcar = () => {
        marcacaoNome = '';
        marcacaoAtiva = false;
        blocoMarcar.classList.remove('ativo');

        const lista = blocoMarcar.querySelectorAll('li');
        lista.forEach(item => {
            item.classList.remove('ativo');
            item.classList.remove('hover');
        });
    };

    const executarMarcacaoEquipe = item => {
        if (!item) {
            item = blocoMarcar.querySelector('li.ativo');
        }
        const nomeParcial = marcacaoNome;
        const usuario = item.getAttribute('data-usuario');
        const nomeFinal = usuario.slice(nomeParcial.length, usuario.length);

        const valorTemporario = inputHistorico.value;
        const valorTemporarioTamanho = valorTemporario.length;

        const valor =
            valorTemporario.substr(0, marcacaoPosicaoInicial + 1) +
            nomeFinal +
            ' ' +
            valorTemporario.substr(marcacaoPosicaoInicial + 1, valorTemporarioTamanho);

        const posicaoFinal = marcacaoPosicaoFinal + nomeFinal.length + 2;

        inputHistorico.value = valor;
        inputHistorico.focus();
        inputHistorico.setSelectionRange(posicaoFinal, posicaoFinal);
    };
};
