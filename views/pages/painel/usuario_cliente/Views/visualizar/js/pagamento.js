window.addEventListener('load', () => {
    const botaoGerenciarPagamento = document.querySelector('#botao_gerenciar_pagamento');
    if (!botaoGerenciarPagamento) {
        return;
    }

    const LINK = document.querySelector('#LINK').value;

    const blocoGerenciarPagamento = document.querySelector('#bloco_pagamento_visualizar');
    const blocoPagamento = document.querySelector('#bloco_pagamento_lista');
    const botaoFecharPagamento = document.querySelector('#botao_pagamento_visualizar_fechar');
    const botaoSalvar = document.querySelector('#botao_pagamento_salvar');

    const usuario = document.querySelector('#input_pagamento_usuario').value;
    const hash = document.querySelector('#pagamento_hash').value;
    const inputData = document.querySelector('#input_pagamento_data');
    const inputValor = document.querySelector('#input_pagamento_valor');

    /*
    |--------------------------------------------------------------------------
    | ABRIR/FECHAR
    |--------------------------------------------------------------------------
    */
    botaoGerenciarPagamento.addEventListener('click', () => {
        abrirBlocoPagamento();
    });

    blocoGerenciarPagamento.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_pagamento_visualizar') {
            fecharBlocoPagamento();
        }
    });
    document.querySelector('body').addEventListener('keydown', e => {
        if (e.key == 'Escape') {
            fecharBlocoPagamento();
        }
    });
    botaoFecharPagamento.addEventListener('click', () => {
        fecharBlocoPagamento();
    });

    const abrirBlocoPagamento = () => {
        blocoGerenciarPagamento.classList.add('display_flex');
        setTimeout(() => {
            blocoGerenciarPagamento.classList.add('abrir');
        }, 20);
    };
    const fecharBlocoPagamento = () => {
        blocoGerenciarPagamento.classList.remove('abrir');
        setTimeout(() => {
            blocoGerenciarPagamento.classList.remove('display_flex');
            inputData.value = '';
            inputValor.value = '';
        }, 300);
    };

    /*
    |--------------------------------------------------------------------------
    | DAR BAIXA
    |--------------------------------------------------------------------------
    */
    blocoPagamento.addEventListener('click', async e => {
        const baixa =
            e.target.classList.contains('botao_pagamento_baixar') || e.target.closest('.botao_pagamento_baixar');

        if (!baixa) {
            return;
        }

        const bloco = e.target.closest('.pagamento');
        const id = bloco.getAttribute('data-id');

        if (
            await Alerta.confirmar(
                'Dar baixa',
                'Tem certeza que deseja dar baixa nesse cobrança? Essa ação não poderá ser desfeita.',
                false
            )
        ) {
            darBaixaNoPagamento(bloco, id);
        }
    });

    const darBaixaNoPagamento = async (bloco, id) => {
        Loading.show();

        const resposta = await fetch(LINK + `/usuario-pagamento/${id}`, {
            method: 'DELETE',
        });

        Loading.hide();
        if (resposta.status != 204) {
            Alerta.notificacao('Erro ao dar baixa no débito, por favor, tente novamente.', false);
            return;
        }
        Alerta.notificacao('Baixa realizada com sucesso!', true);
        bloco.parentNode.removeChild(bloco);

        if (blocoPagamento.querySelectorAll('.pagamento').length == 0) {
            blocoPagamento.insertAdjacentHTML(
                'beforeend',
                `<div class="zero">Sem pagamentos pendentes para esse usuário</div>`
            );
        }
    };

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    botaoSalvar.addEventListener('click', () => {
        salvarNovaCobranca();
    });
    const salvarNovaCobranca = async () => {
        Loading.show();

        const body = new FormData();
        body.append('data', inputData.value);
        body.append('valor', inputValor.value);
        body.append('usuario', usuario);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');

        const resposta = await fetch(LINK + `/usuario-pagamento`, {
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

        if (resposta.status != 201) {
            Alerta.notificacao(
                json.erro.mensagem != undefined
                    ? json.erro.mensagem
                    : 'Ocorreu um erro ao salvar o débito, por favor, tente novamente.',
                false
            );
            return;
        }

        inputData.value = '';
        inputValor.value = '';

        const blocoZero = blocoPagamento.querySelector('.zero');
        if (blocoZero) {
            blocoZero.parentNode.removeChild(blocoZero);
        }

        blocoPagamento.insertAdjacentHTML(
            'beforeend',
            `
                <div class="pagamento" data-id="${json.dado.id}">
                    <div class="data">${json.dado.data}</div>
                    <div class="linha"></div>
                    <div class="valor">${json.dado.valor}</div>
                    <div class="botao botao_pagamento_baixar">DAR BAIXA</div>
                </div>
            `
        );
    };
});
