// @template "painel"
// @painel "historico"
// @painel "contato"
// @system "DragDrop"
// @system "Popup"

window.addEventListener('load', () => {
    const blocoProspeccao = document.getElementById('bloco_comercial_prospeccao');
    const listaItem = blocoProspeccao.querySelectorAll('.bloco_kambam_item');

    const blocoPesquisa = document.getElementById('bloco_pesquisa');
    const blocoApresentacao = document.getElementById('bloco_apresentacao');
    const blocoNegociacao = document.getElementById('bloco_negociacao');
    const blocoAvaliacao = document.getElementById('bloco_avaliacao');
    const blocoMinuta = document.getElementById('bloco_minuta');
    const blocoStandBy = document.getElementById('bloco_standby');

    const PopupAtualizar = new Popup('atualizar-dado', 'bloco_motivo', true, true);
    const h1Popup = document.getElementById('h1_motivo');
    const inputPopup = document.getElementById('input_motivo');
    const labelPopup = inputPopup.parentNode.querySelector('label');
    const botaoPopup = document.getElementById('botao_atualizar_motivo');

    const htmlZero = '<div class="tarefa_zero">Sem itens<br> no momento</div>';

    const setEvents = item => {
        const botaoAtendimento = item.querySelector('.botao_item_atendimento');
        const botaoHistorico = item.querySelector('.botao_item_historico');
        const botaoCancelar = item.querySelector('.botao_item_cancelar');
        const botaoConcluir = item.querySelector('.botao_item_concluir');
        const botaoStandBy = item.querySelector('.botao_item_standby');
        const botaoVoltarStandBy = item.querySelector('.botao_item_voltar_standby');
        const id = item.getAttribute('data-id');

        const PaginaContato = new Pagina(
            'contato-' + id,
            LINK + '/comercial-prospeccao/contato/' + id,
            {},
            true,
            true,
            contatoLoad
        );

        const PaginaHistorico = new Pagina(
            'historico-' + id,
            LINK + '/historico/comercial-empresa/' + id,
            {},
            true,
            true,
            historicoLoad
        );

        const adicionarEventoBotao = (botao, callback) => {
            if (botao) {
                botao.addEventListener('click', callback);
            }
        };

        adicionarEventoBotao(botaoAtendimento, () => {
            PaginaContato.abrir();
        });

        adicionarEventoBotao(botaoHistorico, () => {
            PaginaHistorico.abrir();
        });

        adicionarEventoBotao(botaoCancelar, () => {
            cancelarContrato(item, id);
        });

        adicionarEventoBotao(botaoConcluir, () => {
            concluirContrato(id);
        });

        adicionarEventoBotao(botaoStandBy, () => {
            colocarStandby(item, id);
        });

        adicionarEventoBotao(botaoVoltarStandBy, () => {
            voltarStandby(item, id);
        });
    };

    listaItem.forEach(item => {
        setEvents(item);
    });

    /*
    |--------------------------------------------------------------------------
    | CANCELAR/CONCLUIR/STAND BY
    |--------------------------------------------------------------------------
    */
    cancelarContrato = async (item, id) => {
        h1Popup.textContent = 'Cancelar contrato';
        inputPopup.setAttribute('placeholder', 'Digite o motivo para o cancelamento');
        labelPopup.textContent = 'Motivo para o cancelamento';
        botaoPopup.textContent = 'Cancelar';
        botaoPopup.setAttribute('class', 'botao_cancelar');

        PopupAtualizar.abrir();

        const formMotivo = document.querySelector('.form_motivo');
        formMotivo.addEventListener('submit', async e => {
            e.preventDefault();

            const motivo = document.querySelector('.input_motivo').value;

            await atualizarStatusContrato(item, id, 'inativo', motivo);

            PopupAtualizar.fechar();
        });
    };
    concluirContrato = async id => {
        if (!(await Alerta.confirmar('Concluir contrato!', 'Tem certeza que deseja concluir esse contrato?', '!'))) {
            return;
        }
        window.location.assign(LINK + '/app/editar/comercial-empresa/' + id);
    };

    colocarStandby = async (item, id) => {
        h1Popup.textContent = 'Motivo Stand BY';
        inputPopup.setAttribute('placeholder', "Digite o motivo do stand by'");
        labelPopup.textContent = 'Motivo Stand BY';
        botaoPopup.textContent = 'Atualizar';
        botaoPopup.setAttribute('class', 'botao_atualizar');

        PopupAtualizar.abrir();

        const formMotivo = document.querySelector('.form_motivo');
        formMotivo.addEventListener('submit', async e => {
            e.preventDefault();
            const motivo = document.querySelector('.input_motivo').value;

            await atualizarStatusContrato(item, id, 'standby', motivo);

            const listaStadbyExemplo = document.getElementById('bloco_standby_exemplo');
            const newItem = listaStadbyExemplo.cloneNode(true);

            newItem.querySelector('.titulo').innerText = item.querySelector('.titulo').innerText;
            newItem.querySelector('.data').innerText = item.querySelector('.data').innerText;
            newItem
                .querySelector('.botao_link')
                .setAttribute('href', item.querySelector('.botao_link').getAttribute('href'));
            newItem.setAttribute('class', 'bloco_kambam_item');
            newItem.dataset.id = id;
            newItem.dataset.status = item.dataset.status;
            setEvents(newItem);

            PopupAtualizar.fechar();

            blocoStandBy.appendChild(newItem);
            removerBlocoZero(blocoStandBy);
            adicionarNumeroItem(blocoStandBy);
        });
    };
    voltarStandby = async (item, id) => {
        if (
            !(await Alerta.confirmar(
                'Voltar contrato para prospecção!',
                'Tem certeza que deseja voltar esse contrato para prospecção?',
                '!'
            ))
        ) {
            return;
        }
        await atualizarStatusContrato(item, id, 'prospeccao');

        const newItem = listaItem[0].cloneNode(true);

        newItem.querySelector('.titulo').innerText = item.querySelector('.titulo').innerText;
        newItem.querySelector('.data').innerText = item.querySelector('.data').innerText;
        newItem
            .querySelector('.botao_link')
            .setAttribute('href', item.querySelector('.botao_link').getAttribute('href'));
        newItem.dataset.id = id;
        newItem.dataset.status = item.dataset.status;
        setEvents(newItem);

        const status = item.getAttribute('data-status');
        const bloco = pegarBlocoPeloStatus(status);

        bloco.appendChild(newItem);
        removerBlocoZero(bloco);
        adicionarNumeroItem(blocoStandBy);
        adicionarNumeroItem(bloco);
    };

    const pegarBlocoPeloStatus = status => {
        switch (status) {
            case 'pesquisa':
                return blocoPesquisa;
            case 'apresentacao':
                return blocoApresentacao;
            case 'negociacao':
                return blocoNegociacao;
            case 'avaliacao':
                return blocoAvaliacao;
            case 'minuta':
                return blocoMinuta;
            case 'standby':
                return blocoStandBy;
        }
    };

    /*
    |--------------------------------------------------------------------------
    | DRAG AND DROP
    |--------------------------------------------------------------------------
    */

    const listaColuna = blocoProspeccao.querySelectorAll('.bloco_kambam_index .coluna_drop');
    for (const item of listaColuna) {
        new DragDrop()
            .grupo('.bloco_kambam_index .coluna_drop')
            .bloco(item)
            .item('article')
            .eventoMover(async e => {
                manipularBlocoZero(e.to);
            })
            .eventoFim(e => {
                if (e.from == e.to) {
                    return;
                }
                atualizarStatusProspeccao(e.target, e.to, e.item);
            })
            .iniciar();
    }

    const manipularBlocoZero = atual => {
        let blocoZero, quantidade;
        for (const coluna of listaColuna) {
            blocoZero = coluna.querySelector('.tarefa_zero');
            quantidade = coluna.querySelectorAll('.bloco_kambam_item:not(.drag_drop_fantasma)').length;
            if (quantidade == 0) {
                blocoZero.classList.remove('display_none');
                continue;
            }
            blocoZero.classList.add('display_none');
        }
        const blocoZeroAtual = atual.querySelector('.tarefa_zero');
        blocoZeroAtual.classList.add('display_none');
    };

    /*
    |--------------------------------------------------------------------------
    | FUNÇÕES GERAIS
    |--------------------------------------------------------------------------
    */

    const atualizarStatusContrato = async (item, id, status, dados = false) => {
        Loading.show();

        const body = new FormData();
        body.append('id', id);
        body.append('status', status);
        if (dados && status == 'standby') {
            body.append('motivo_standby', dados);
        }
        if (dados && status == 'inativo') {
            body.append('motivo_perdido', dados);
        }
        const resposta = await fetch(LINK + '/comercial-prospeccao/atualizar-status', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Ocorreu um erro ao atualizar contrato, por favor, tente novamente.');
        Loading.hide();
        if (false === json) {
            return;
        }
        const blocoAtual = item.closest('.conteudo');
        item.parentNode.removeChild(item);
        adicionarBlocoZero(blocoAtual);
    };

    const atualizarStatusProspeccao = async (blocoAtual, blocoDestino, item) => {
        const id = item.getAttribute('data-id');
        const status = blocoDestino.getAttribute('data-prospeccao');

        Loading.show();

        const resposta = await ajaxPost(
            LINK + '/comercial-prospeccao/atualizar-prospeccao',
            {
                id,
                prospeccao: status,
            },
            'Mensagem de erro padrão'
        );

        Loading.hide();

        if (false === resposta) {
            return;
        }
        adicionarNumeroItem(blocoAtual);
        adicionarNumeroItem(blocoDestino);
    };

    const adicionarNumeroItem = bloco => {
        const blocoColuna = bloco.closest('.bloco_coluna');
        const quantidade = blocoColuna.querySelectorAll('.bloco_kambam_item').length;
        const blocoNumero = blocoColuna.querySelector('header h1 span');
        blocoNumero.innerText = '(' + quantidade + ')';
    };
    const adicionarBlocoZero = bloco => {
        if (bloco.querySelectorAll('.bloco_kambam_item').length == 0) {
            bloco.insertAdjacentHTML('afterbegin', htmlZero);
        }
    };

    const removerBlocoZero = bloco => {
        const blocoZero = bloco.querySelector('.tarefa_zero');
        if (blocoZero) {
            blocoZero.parentNode.removeChild(blocoZero);
        }
    };
});
