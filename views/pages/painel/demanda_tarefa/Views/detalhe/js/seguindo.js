const loadingSeguindo = () => {
    const idDemanda = document.getElementById('input_id_demanda').value;
    const blocoSeguindoLista = document.getElementById('bloco_seguindo_lista');

    /*
    |--------------------------------------------------------------------------
    | BLOCO LISTA DE SEGUIDOR
    |--------------------------------------------------------------------------
    */
    const botaoBlocoAbrir = document.getElementById('botao_seguindo_bloco_abrir');
    const blocoSeguindo = document.getElementById('bloco_demanda_detalhe_seguindo');
    const botaoSeguindoCancelar = document.getElementById('botao_seguindo_cancelar');
    const botaoSeguindoConfirmar = document.getElementById('botao_seguindo_confirmar');

    let listaSeguidores = [];
    const pegarListaSeguidores = () => {
        listaSeguidores = blocoSeguindo.querySelectorAll('.bloco.liberado .check input:checked');
    };

    if (botaoBlocoAbrir) {
        botaoBlocoAbrir.addEventListener('click', () => {
            abrirBlocoDeSeguidores();
        });
        botaoSeguindoCancelar.addEventListener('click', () => {
            fecharBlocoDeSeguidores();
        });
        const hashSalvarSeguidor = blocoSeguindo.querySelector('input[name=form_system_hash]').value;
        botaoSeguindoConfirmar.addEventListener('click', () => {
            gerenciarSeguidoresDaTarefa(hashSalvarSeguidor);
        });
        pegarListaSeguidores();
    }

    const abrirBlocoDeSeguidores = () => {
        blocoSeguindo.style.display = 'flex';
        setTimeout(() => {
            blocoSeguindo.classList.add('animar');
        }, 20);
    };
    const fecharBlocoDeSeguidores = () => {
        blocoSeguindo.classList.remove('animar');
        setTimeout(() => {
            blocoSeguindo.style.display = 'none';
            const listarTodosSeguidores = blocoSeguindo.querySelectorAll('.bloco.liberado .check input');
            listarTodosSeguidores.forEach(item => {
                item.checked = false;
            });
            listaSeguidores.forEach(item => {
                item.checked = true;
            });
        }, 300);
    };
    const gerenciarSeguidoresDaTarefa = async hash => {
        if (botaoSeguindoConfirmar.classList.contains('aguarde')) {
            return;
        }
        botaoSeguindoConfirmar.classList.add('aguarde');

        const novosSeguidores = blocoSeguindo.querySelectorAll('.bloco.liberado .check input:checked');
        const body = new FormData();
        body.append('id', idDemanda);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');
        novosSeguidores.forEach(input => {
            body.append('seguidor[]', input.value);
        });

        const response = await fetch(LINK + '/demanda/seguidores', {
            method: 'POST',
            body,
        });

        botaoSeguindoConfirmar.classList.remove('aguarde');
        const json = await response.json();

        if (response.status != 201) {
            const mensagem = json.mensagem != undefined ? json.mensagem : 'Erro ao gerenciar os seguidores.';
            Alerta.notificacao(mensagem, false);
            return;
        }

        const todosSeguidores = blocoSeguindoLista.querySelectorAll('figure.item');
        todosSeguidores.forEach(item => {
            item.parentNode.removeChild(item);
        });

        let id, bloco, nome, figure;
        novosSeguidores.forEach(item => {
            id = item.value;
            bloco = item.closest('.bloco');
            nome = bloco.querySelector('label').innerText.trim();
            figure = bloco.querySelector('figure').cloneNode(true);
            figure.setAttribute('id', 'seguindo_' + id);
            figure.setAttribute('data-ajuda', nome);
            figure.classList.add('item');
            blocoSeguindoLista.appendChild(figure);
        });

        const blocoDono = document.getElementById('bloco_seguindo_dono');
        const blocoDev = document.getElementById('bloco_seguindo_dev');
        if (blocoDev) {
            adicionarFigureSeguidorPrincipal(blocoDev);
        }
        adicionarFigureSeguidorPrincipal(blocoDono);

        pegarListaSeguidores();
        fecharBlocoDeSeguidores();
        adicionarNovaMensagem(json.mensagem);
    };
    const adicionarFigureSeguidorPrincipal = bloco => {
        const figure = bloco.querySelector('figure').cloneNode(true);
        const nome = bloco.querySelector('p').innerText.trim();
        figure.setAttribute('data-ajuda', nome);
        figure.classList.add('item');
        blocoSeguindoLista.appendChild(figure);
    };

    /*
    |--------------------------------------------------------------------------
    | SEGUIR TAREFA
    |--------------------------------------------------------------------------
    */
    const botaoSeguirTarefa = document.getElementById('botao_seguir_tarefa');
    if (botaoSeguirTarefa) {
        const hashSeguirTarefa = document.querySelector('#form_seguir_tarefa input[name=form_system_hash]').value;
        botaoSeguirTarefa.addEventListener('click', () => {
            seguirOuSairDaTarefa(hashSeguirTarefa);
        });
    }

    const seguirOuSairDaTarefa = async hash => {
        Loading.show();
        let acao = 'seguir';
        if (botaoSeguirTarefa.classList.contains('sair')) {
            acao = 'sair';
        }

        const body = new FormData();
        body.append('id', idDemanda);
        body.append('acao', acao);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');

        const request = await fetch(LINK + '/demanda/seguir', {
            method: 'POST',
            body,
        });
        const json = await request.json();

        Loading.hide();

        if (request.status != 201) {
            const erro = json.mensagem != undefined ? json.mensagem : 'Ocorreu um erro ao seguir a tarefa.';
            Alerta.notificacao(erro, false);
            return;
        }

        const mensagem = json.mensagem != undefined ? json.mensagem : '';
        if (mensagem != '') {
            adicionarNovaMensagem(mensagem);
        }

        if (acao == 'sair') {
            botaoSeguirTarefa.classList.remove('sair');
            botaoSeguirTarefa.innerText = 'Seguir';
            const blocoFigureRemover = blocoSeguindoLista.querySelector('#seguindo_' + json.id);
            if (blocoFigureRemover) {
                blocoFigureRemover.parentNode.removeChild(blocoFigureRemover);
            }
            return;
        }
        botaoSeguirTarefa.innerText = 'Sair';
        botaoSeguirTarefa.classList.add('sair');
        const html =
            `<figure id="seguindo_` +
            json.id +
            `" class="item" style="background-image: url(` +
            json.imagem +
            `)"></figure>`;
        blocoSeguindoLista.insertAdjacentHTML('beforeend', html);
    };
};
