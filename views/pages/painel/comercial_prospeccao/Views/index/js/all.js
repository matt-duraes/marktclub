// @template "painel"
// @painel "historico"
// @painel "contato"

window.addEventListener('load', () => {
    const blocoProspeccao = document.getElementById('bloco_comercial_prospeccao');
    const listaItem = blocoProspeccao.querySelectorAll('.bloco_kambam_item');

    const blocoAbordagem = document.getElementById('bloco_abordagem');
    const blocoApresentacao = document.getElementById('bloco_apresentacao');
    const blocoNegociacao = document.getElementById('bloco_negociacao');
    const blocoAvaliacao = document.getElementById('bloco_avaliacao');
    const blocoMinuta = document.getElementById('bloco_minuta');

    const htmlZero = '<div class="tarefa_zero">Sem itens<br> no momento</div>';

    listaItem.forEach(item => {
        const botaoAtendimento = item.querySelector('.botao_item_atendimento');
        const botaoHistorico = item.querySelector('.botao_item_historico');
        const botaoCancelar = item.querySelector('.botao_item_cancelar');
        const botaoConcluir = item.querySelector('.botao_item_concluir');
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
        botaoAtendimento.addEventListener('click', () => {
            PaginaContato.abrir();
        });

        const PaginaHistorico = new Pagina(
            'historico-' + id,
            LINK + '/historico/comercial-empresa/' + id,
            {},
            true,
            true,
            historicoLoad
        );
        botaoHistorico.addEventListener('click', () => {
            PaginaHistorico.abrir();
        });
        botaoCancelar.addEventListener('click', () => {
            cancelarContrato(item, id);
        });
        botaoConcluir.addEventListener('click', () => {
            concluirContrato(item, id);
        });
        botaoAnterior.addEventListener('click', () => {
            moverParaBlocoAnterior(item, id);
        });
        botaoProximo.addEventListener('click', () => {
            moverParaBlocoProximo(item, id);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | CANCELAR/CONCLUIR
    |--------------------------------------------------------------------------
    */
    cancelarContrato = async (item, id) => {
        if (!(await Alerta.confirmar('Cancelar contrato!', 'Tem certeza que deseja finalizar esse contrato?', '!'))) {
            return;
        }
        atualizarStatusContrato(item, id, 'inativo');
    };
    concluirContrato = async (item, id) => {
        if (!(await Alerta.confirmar('Concluir contrato!', 'Tem certeza que deseja concluir esse contrato?', true))) {
            return;
        }
        atualizarStatusContrato(item, id, 'ativo');
    };
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
        if (atual == 'abordagem') {
            return false;
        } else if (atual == 'apresentacao') {
            return blocoAbordagem;
        } else if (atual == 'negociacao') {
            return blocoApresentacao;
        } else if (atual == 'avaliacao') {
            return blocoNegociacao;
        } else if (atual == 'minuta') {
            return blocoAvaliacao;
        }
    };
    const pegarBlocoProximo = bloco => {
        const atual = bloco.getAttribute('data-prospeccao');
        if (atual == 'abordagem') {
            return blocoApresentacao;
        } else if (atual == 'apresentacao') {
            return blocoNegociacao;
        } else if (atual == 'negociacao') {
            return blocoAvaliacao;
        } else if (atual == 'avaliacao') {
            return blocoMinuta;
        } else if (atual == 'minuta') {
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
        if (status == 'abordagem') {
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
});
