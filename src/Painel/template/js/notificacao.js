window.addEventListener('load', () => {
    const blocoNotificacao = document.getElementById('bloco_notificacao');
    const blocoNumeroNotificaoNovas = document.getElementById('bloco_numero_notificao_novas');
    const blocoNotificacaoScroll = document.getElementById('bloco_notificacao_scroll');
    const blocoNotificacaoNova = document.getElementById('bloco_notificacao_nova');
    const blocoNotificacaoAntiga = document.getElementById('bloco_notificacao_antiga');
    const blocoNotificacaoZero = document.getElementById('bloco_notificacao_zero');
    const blocoNotificacaoLoading = document.getElementById('bloco_notificacao_loading');
    const blocoNotificacaoNovaLista = document.querySelectorAll('#bloco_notificacao_nova .item');

    const idNovoItem = [];
    if (blocoNotificacaoNovaLista) {
        blocoNotificacaoNovaLista.forEach(item => {
            idNovoItem.push(item.getAttribute('data-id'));
        });
    }

    const botaoNotificacaoAbrir = document.getElementById('botao_notificacao_abrir');
    const botaoNotificacaoLimpar = document.getElementById('botao_notificacao_limpar');

    botaoNotificacaoAbrir.addEventListener('click', () => {
        notificacaoAbrir();
    });

    let paginaAtual, paginaTotal;
    const notificacaoAbrir = () => {
        blocoNotificacao.classList.remove('display_none');
        blocoNotificacaoScroll.scrollTo(0, 0);
        if (blocoNotificacao.classList.contains('notificacao_carregada')) {
            return;
        }
        buscarNotificacoes(1);
    };
    const notificacaoFechar = () => {
        blocoNotificacao.classList.add('display_none');
    };

    let carregarMaisAtivo = true;
    blocoNotificacaoScroll.addEventListener('scroll', () => {
        const blocoHeight = blocoNotificacaoScroll.getBoundingClientRect().height;
        const limite = blocoNotificacaoScroll.scrollHeight - blocoHeight - 20;
        if (blocoNotificacaoScroll.scrollTop >= limite && carregarMaisAtivo && paginaAtual < paginaTotal) {
            carregarMaisAtivo = false;
            buscarNotificacoes(paginaAtual + 1);
        }
    });

    const buscarNotificacoes = async pagina => {
        Loading.form('#bloco_notificacao .conteudo').show();

        const resposta = await fetch(LINK + '/notificacao?pagina=' + pagina);
        const json = await respostaJson(resposta, 'Erro ao buscar notificações, por favor, tente novamente.', false);

        Loading.form('#bloco_notificacao .conteudo').hide();
        blocoNotificacaoLoading.classList.add('display_none');
        carregarMaisAtivo = true;
        if (false === json) {
            notificacaoFechar();
            return;
        }

        blocoNumeroNotificaoNovas.classList.add('display_none');

        paginaAtual = pagina;
        paginaTotal = json.dado.pagina.total;

        await carregarNotificacoes(json.dado.lista);
        if (pagina == 1 && blocoNotificacaoNova) {
            atualizarStatusNotificacaoNova();
        }

        blocoNotificacao.classList.add('notificacao_carregada');

        if (!blocoNotificacao.querySelector('.item')) {
            blocoNotificacaoZero.classList.remove('display_none');
        }

        carregarDataAjuda();
    };

    const carregarNotificacoes = lista => {
        return new Promise(resolve => {
            if (lista.length == 0) {
                resolve(true);
                return;
            }

            blocoNotificacaoAntiga.classList.remove('display_none');
            lista.forEach(item => {
                blocoNotificacaoAntiga.insertAdjacentHTML(
                    'beforeend',
                    montarHtml(
                        item.id,
                        item.nome,
                        item.imagem,
                        item.mensagem,
                        item.data_social,
                        item.data_real,
                        item.target,
                        item.rel,
                        item.status
                    )
                );
            });
            resolve(true);
        });
    };

    const montarHtml = (id, nome, imagem, mensagem, dataSocial, dataReal, target, rel, status) => {
        return `
            <a class="item item_novo ${status}" target="${target}" ${rel} href="${LINK}/notificacao/${id}">
                <figure style="background-image: url(${imagem})"></figure>
                <div class="dado">
                    <strong>${nome}</strong>
                    <p>${mensagem}</p>
                    <div class="data" data-ajuda="${dataReal}">${dataSocial}</div>
                </div>
                <div class="bola"></div>
            </a>
        `;
    };

    const carregarDataAjuda = () => {
        const lista = blocoNotificacao.querySelectorAll('.item_novo .data');
        if (lista.length == 0) {
            return;
        }
        lista.forEach(bloco => {
            bloco.closest('.item_novo').classList.remove('item_novo');
            bloco.addEventListener('mouseover', () => {
                const texto = bloco.getAttribute('data-ajuda');
                Ajuda.show(bloco, texto);
            });
            bloco.addEventListener('mouseout', () => {
                Ajuda.hide();
            });
        });
    };
    const atualizarStatusNotificacaoNova = () => {
        if (idNovoItem.length == 0) {
            return;
        }
        const body = new FormData();
        idNovoItem.forEach(id => {
            body.append('id[]', id);
        });
        fetch(LINK + '/notificacao/atualizar-visualizadas', {
            method: 'POST',
            body,
        });
        blocoNotificacaoNova.classList.remove('display_none');
    };

    botaoNotificacaoLimpar.addEventListener('click', async () => {
        if (
            await Alerta.confirmar(
                'Visualizar Todas',
                'Tem certeza que deseja viazualizar todas as suas notificações? Essa ação não poderá ser desfeita.',
                '!'
            )
        ) {
            visualizarTodasNotificacao();
        }
    });
    const visualizarTodasNotificacao = async () => {
        Loading.form('#bloco_notificacao .conteudo').show();
        const resposta = await fetch(LINK + '/notificacao/visualizar-todas');
        const json = respostaJson(resposta, 'Erro ao limpar as notificações, por favor, tente novamente.');
        Loading.form('#bloco_notificacao .conteudo').hide();
        if (false === json) {
            return;
        }
        botaoNotificacaoLimpar.classList.add('display_none');
        const lista = blocoNotificacao.querySelectorAll('.item.novo, .item.visualizado');
        lista.forEach(item => {
            item.classList.remove('novo');
            item.classList.remove('visualizado');
            item.classList.add('clicado');
        });
    };
});
