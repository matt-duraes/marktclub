window.addEventListener('load', () => {
    const botaoContratarConsignado = document.querySelector('#enviar_solicitacao');
    botaoContratarConsignado.addEventListener('click', async function (e) {
        e.preventDefault();
        let form = document.querySelector('#formulario_emprestimo');

        let tipo = form.querySelector('input[name=tipo_financiamento]').value;
        let valor = form.querySelector('input[name=financiamento]').value;
        let prazo = form.querySelector('input[name=parcela]').value;

        const body = new FormData();
        body.append('tipo', tipo);
        body.append('valor', valor);
        body.append('prazo', prazo);
        body.append('operadora', 'sicoob-judiciario');

        const resposta = await fetch('/credito/salvar', {
            method: 'POST',
            body,
        });

        if (resposta.status != 201) {
            Alerta.notificacao(
                json.erro.mensagem != undefined
                    ? json.erro.mensagem
                    : 'Ocorreu um erro ao salvar a contratação, por favor, tente novamente.',
                false
            );
            return;
        }
        const botaoVoltar = document.querySelector('.bloco_credito_geral_simulacao .bloco_credito .bloco_botao .cinza');
        irParaPassoAnterior(botaoVoltar);
        limparFormulario(form);
        Alerta.notificacao('Envio do formulário com sucesso!', true);
    });

    function limparFormulario(formulario) {
        const inputs = formulario.querySelectorAll('input');

        inputs.forEach(input => {
            if (input.type !== 'submit' && input.type !== 'button') {
                input.value = '';
            }
        });
    }

    const carregarFuncoesBusca = () => {
        const botaoFechar = document.querySelectorAll('.botao_fechar_popup');

        botaoFechar.forEach(fecha => {
            fecha.addEventListener('click', () => {
                Pagina.staticFechar();
            });
        });
    };

    const botaoPopupRegulamento = document.querySelectorAll('.abrirModalRegulamento');
    botaoPopupRegulamento.forEach(modelo => {
        const tipo = modelo.getAttribute('data-tipo');
        const PaginaDetalhe = new Pagina(tipo, '/sicoob-regulamento/' + tipo, {}, true, true, carregarFuncoesBusca);

        modelo.addEventListener('click', () => {
            PaginaDetalhe.abrir();
        });
    });

    const botaoSimularConsignado = document.querySelector('#botao_fazer_simulacao');

    botaoSimularConsignado.addEventListener('click', async function (e) {
        e.preventDefault();

        let form = document.querySelector('#formulario_emprestimo');

        let tipo = form.querySelector('input[name=tipo_financiamento]').value;
        let valor = form.querySelector('input[name=financiamento]').value;
        let prazo = form.querySelector('input[name=parcela]').value;

        if (valor == '') {
            Alerta.mensagem('Campo obrigatório!', 'Digite o valor que deseja simular.');
            return false;
        } else if (prazo == '') {
            Alerta.mensagem('Campo obrigatório!', 'Escolha a quantidade de parcelas que deseja simular.');
            return false;
        }

        Loading.show();
        let query = `&tipo=${tipo}&valor=${valor}&prazo=${prazo}`;

        const resposta = await fetch('/credito/simulacao?operadora=sicoob-judiciario' + query, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        Loading.hide();

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        if (resposta.status == 200) {
            irParaProximoPasso(botaoSimularConsignado);
            document.querySelector('.valor_emprestimo').innerHTML = json.dado.lista.valor;
            document.querySelector('.valor_prazo').innerHTML = json.dado.lista.parcelas;
            document.querySelector('#simulacao_valor').innerHTML = 'R$ ' + json.dado.lista.valor_parcelas;
            return;
        }

        Alerta.notificacao(
            json.erro.mensagem != undefined
                ? json.erro.mensagem
                : 'Ocorreu um erro ao simular, por favor, tente novamente.',
            false
        );

        Loading.hide();
    });
});
