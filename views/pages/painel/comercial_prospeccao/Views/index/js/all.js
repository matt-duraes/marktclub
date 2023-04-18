// @template "painel"

window.addEventListener('load', () => {
    const blocoProspeccao = document.getElementById('bloco_comercial_prospeccao');
    const listaItem = blocoProspeccao.querySelectorAll('.bloco_kambam_item');

    const blocoAbordagem = document.getElementById('bloco_abordagem');
    const blocoApresentacao = document.getElementById('bloco_apresentacao');
    const blocoNegociacao = document.getElementById('bloco_negociacao');
    const blocoAvaliacao = document.getElementById('bloco_avaliacao');
    const blocoMinuta = document.getElementById('bloco_minuta');

    const htmlZero = '<div class="item_zero">Sem itens<br> no momento</div>';

    listaItem.forEach(item => {
        const botaoAtendimento = item.querySelector('.botao_item_atendimento');
        const botaoHistorico = item.querySelector('.botao_item_historico');
        const botaoCancelar = item.querySelector('.botao_item_cancelar');
        const botaoConcluir = item.querySelector('.botao_item_concluir');
        const botaoAnterior = item.querySelector('.botao_item_anterior');
        const botaoProximo = item.querySelector('.botao_item_proximo');
        const id = item.getAttribute('data-id');

        botaoAnterior.addEventListener('click', () => {
            moverParaBlocoAnterior(item);
        });
        botaoProximo.addEventListener('click', () => {
            moverParaBlocoProximo(item);
        });
    });

    /*
    |--------------------------------------------------------------------------
    | MOVER ITEM
    |--------------------------------------------------------------------------
    */
    const moverParaBlocoAnterior = item => {
        const blocoAtual = item.closest('.conteudo');
        const blocoDestino = pegarBlocoAnterior(blocoAtual);
        moverItem(blocoAtual, blocoDestino, item);
    };
    const moverParaBlocoProximo = item => {
        const blocoAtual = item.closest('.conteudo');
        const blocoDestino = pegarBlocoProximo(blocoAtual);
        moverItem(blocoAtual, blocoDestino, item);
    };
    const pegarBlocoAnterior = bloco => {
        const atual = bloco.getAttribute('data-bloco');
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
        const atual = bloco.getAttribute('data-bloco');
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

    const moverItem = async (atual, destino, item) => {
        if (false === destino || false === atual) {
            return;
        } else if (!(await Alerta.confirmar('Mover contrato!', 'Tem certeza que deseja mover esse contrato?', '!'))) {
            return;
        }
        const blocoZero = destino.querySelector('.item_zero');
        if (blocoZero) {
            blocoZero.parentNode.removeChild(blocoZero);
        }
        if (atual.querySelectorAll('.bloco_kambam_item').length <= 1) {
            atual.insertAdjacentHTML('afterbegin', htmlZero);
        }
        destino.appendChild(item);
    };
});
