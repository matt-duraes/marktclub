// @template "painel"
// @painel "historico"
// @painel "contato"

window.addEventListener('load', () => {
    const blocoProspeccao = document.getElementById('bloco_comercial_prospeccao');
    const listaItem = blocoProspeccao.querySelectorAll('.bloco_kambam_item');

    const blocoPesquisa = document.getElementById('bloco_pesquisa');
    const blocoApresentacao = document.getElementById('bloco_apresentacao');
    const blocoNegociacao = document.getElementById('bloco_negociacao');
    const blocoAvaliacao = document.getElementById('bloco_avaliacao');
    const blocoMinuta = document.getElementById('bloco_minuta');
    const blocoStandBy = document.getElementById('bloco_standby');

    const htmlZero = '<div class="tarefa_zero">Sem itens<br> no momento</div>';

    const setEvents = (item) => {
        const botaoAtendimento = item.querySelector('.botao_item_atendimento');
        const botaoHistorico = item.querySelector('.botao_item_historico');
        const botaoCancelar = item.querySelector('.botao_item_cancelar');
        const botaoConcluir = item.querySelector('.botao_item_concluir');
        const botaoStandBy = item.querySelector('.botao_item_standby');
        const botaoVoltarStandBy = item.querySelector('.botao_item_voltar_standby');
        const botaoAnterior = item.querySelector('.botao_item_anterior');
        const botaoProximo = item.querySelector('.botao_item_proximo');
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
        }

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

        adicionarEventoBotao(botaoAnterior, () => {
            moverParaBlocoAnterior(item, id);
        });

        adicionarEventoBotao(botaoProximo, () => {
            moverParaBlocoProximo(item, id);
        });

        adicionarEventoBotao(botaoVoltarStandBy, () => {
            voltarStandby(item, id);
        });
    }

    listaItem.forEach(item => {
        setEvents(item)
    });

    /*
    |--------------------------------------------------------------------------
    | CANCELAR/CONCLUIR/STAND BY
    |--------------------------------------------------------------------------
    */
    cancelarContrato = async (item, id) => {
        if (!(await Alerta.confirmar('Cancelar contrato!', 'Tem certeza que deseja finalizar esse contrato?', '!'))) {
            return;
        }
        atualizarStatusContrato(item, id, 'inativo');
    };
    concluirContrato = async id => {
        if (!(await Alerta.confirmar('Concluir contrato!', 'Tem certeza que deseja concluir esse contrato?', '!'))) {
            return;
        }
        window.location.assign(LINK + '/app/editar/comercial-empresa/' + id);
    };
    colocarStandby = async (item, id) => {
        if (!(await Alerta.confirmar('Colocar contrato em stand by!', 'Tem certeza que deseja colocar esse contrato em stand by?', '!'))) {
            return;
        }

        await atualizarStatusContrato(item, id, 'standby');
        const listaStadbyExemplo = document.getElementById('bloco_standby_exemplo');
        const newItem = listaStadbyExemplo.cloneNode(true);

        newItem.querySelector('.titulo').innerText = item.querySelector('.titulo').innerText;
        newItem.querySelector('.data').innerText = item.querySelector('.data').innerText;
        newItem.querySelector('.botao_link').setAttribute('href', item.querySelector('.botao_link').getAttribute('href'));
        newItem.setAttribute('class', 'bloco_kambam_item')
        newItem.dataset.id = id;
        newItem.dataset.status = item.dataset.status;
        setEvents(newItem);

        window.location.assign(LINK + '/app/editar/comercial-prospeccao/' + id);

        blocoStandBy.appendChild(newItem);
        removerBlocoZero(blocoStandBy);
        adicionarNumeroItem(blocoStandBy);
    }
    voltarStandby = async (item, id) => {
        if (!(await Alerta.confirmar('Voltar contrato para prospecção!', 'Tem certeza que deseja voltar esse contrato para prospecção?', '!'))) {
            return;
        }
        await atualizarStatusContrato(item, id, 'prospeccao');

        const newItem = listaItem[0].cloneNode(true);

        newItem.querySelector('.titulo').innerText = item.querySelector('.titulo').innerText;
        newItem.querySelector('.data').innerText = item.querySelector('.data').innerText;
        newItem.querySelector('.botao_link').setAttribute('href', item.querySelector('.botao_link').getAttribute('href'));
        newItem.dataset.id = id;
        newItem.dataset.status = item.dataset.status;
        setEvents(newItem);

        const status = item.getAttribute('data-status');
        const bloco = pegarBlocoPeloStatus(status);

        bloco.appendChild(newItem);
        removerBlocoZero(bloco);
        adicionarNumeroItem(blocoStandBy);
        adicionarNumeroItem(bloco);
    }

    const pegarBlocoPeloStatus = (status) => {
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
    }

    const atualizarStatusContrato = async (item, id, status) => {
        Loading.show();

        const body = new FormData();
        body.append('id', id);
        body.append('status', status);
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

    /*
    |--------------------------------------------------------------------------
    | MOVER ITEM
    |--------------------------------------------------------------------------
    */
    const moverParaBlocoAnterior = (item, id) => {
        const blocoAtual = item.closest('.conteudo');
        const blocoDestino = pegarBlocoAnterior(blocoAtual);
        moverItem(blocoAtual, blocoDestino, item, id);
    };
    const moverParaBlocoProximo = (item, id) => {
        const blocoAtual = item.closest('.conteudo');
        const blocoDestino = pegarBlocoProximo(blocoAtual);
        moverItem(blocoAtual, blocoDestino, item, id);
    };
    const pegarBlocoAnterior = bloco => {
        const atual = bloco.getAttribute('data-prospeccao');
        switch (atual) {
            case 'pesquisa':
                return false;
            case 'apresentacao':
                return blocoPesquisa;
            case 'negociacao':
                return blocoApresentacao;
            case 'avaliacao':
                return blocoNegociacao;
            case 'minuta':
                return blocoAvaliacao;
        }
    };
    const pegarBlocoProximo = bloco => {
        const atual = bloco.getAttribute('data-prospeccao');
        switch (atual) {
            case 'pesquisa':
                return blocoApresentacao;
            case 'apresentacao':
                return blocoNegociacao;
            case 'negociacao':
                return blocoAvaliacao;
            case 'avaliacao':
                return blocoMinuta;
            case 'minuta':
                return false;
        }
    };

    const moverItem = async (atual, destino, item, id) => {
        if (false === destino || false === atual) {
            return;
        } else if (!(await Alerta.confirmar('Mover contrato!', 'Tem certeza que deseja mover esse contrato?', '!'))) {
            return;
        }

        Loading.show();

        const status = destino.getAttribute('data-prospeccao');
        const body = new FormData();
        body.append('id', id);
        body.append('prospeccao', status);

        const resposta = await fetch(LINK + '/comercial-prospeccao/atualizar-prospeccao', {
            method: 'POST',
            body,
        });
        const json = await respostaJson(resposta, 'Erro ao mover o contrato, por favor, tente novamente.');

        Loading.hide();
        if (false === json) {
            return;
        }

        const blocoZero = destino.querySelector('.tarefa_zero');
        if (blocoZero) {
            blocoZero.parentNode.removeChild(blocoZero);
        }
        const botaoAnterior = item.querySelector('.botao_item_anterior');
        const botaoProximo = item.querySelector('.botao_item_proximo');
        botaoAnterior.classList.remove('display_none');
        botaoProximo.classList.remove('display_none');
        if (status == 'pesquisa') {
            botaoAnterior.classList.add('display_none');
        } else if (status == 'minuta') {
            botaoProximo.classList.add('display_none');
        }
        destino.appendChild(item);
        adicionarBlocoZero(atual);
        adicionarNumeroItem(atual);
        adicionarNumeroItem(destino);
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
    }
});
