window.addEventListener('load', () => {
    const inputHistorico = document.querySelector('#input_historico_mensagem textarea');
    if (!inputHistorico) {
        return;
    }

    const app = document.querySelector('#input_historico_app').value;
    const relacionado = document.querySelector('#input_historico_relacionado').value;

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
    inputHistorico.addEventListener('keyup', e => {
        if (!e.shiftKey && e.key == 'Enter') {
            salvarNovoHistorico();
        }
    });
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
            inputHistorico.style.height = '45px';
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
                <p class="mensagem">${mensagem}</p>
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
    | MONITORA SCROLL
    |--------------------------------------------------------------------------
    */
    const blocoAddFake = document.querySelector('#bloco_historico_add_fake');
    const blocoAdd = document.querySelector('#bloco_historico_add');
    const mainContent = document.querySelector('#main_template');

    mainContent.addEventListener('scroll', () => {
        const top = blocoAddFake.getBoundingClientRect().top;
        if (top <= 60 && !blocoAdd.classList.contains('fixo')) {
            blocoAdd.classList.add('fixo');
        } else if (top > 60 && blocoAdd.classList.contains('fixo')) {
            blocoAdd.classList.remove('fixo');
        }
    });
});
