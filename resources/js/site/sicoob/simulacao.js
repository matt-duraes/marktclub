window.addEventListener('load', () => {
    const botaoContratarConsignado = document.querySelector('#enviar_solicitacao');
    botaoContratarConsignado.addEventListener('click', function (e) {
        e.preventDefault();

        setTimeout(function () {
            Alerta.mensagem('Sucesso!', 'Envio do formulário com sucesso!');
        }, 800);
    });

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
        const PaginaDetalhe = new Pagina(
            'automovel-' + tipo,
            LINK + '/sicoob-regulamento/' + tipo,
            {},
            true,
            true,
            carregarFuncoesBusca
        );

        modelo.addEventListener('click', () => {
            PaginaDetalhe.abrir();
        });
    });

    const botaoSimularConsignado = document.querySelector('#botao_fazer_simulacao');
    botaoSimularConsignado.addEventListener('click', function (e) {
        e.preventDefault();

        let form = document.querySelector('#formulario_emprestimo');

        let tipo = form.querySelector('input[data-name=tipo_financiamento]').value;
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

        document.querySelector('.valor_emprestimo').innerHTML = valor;
        document.querySelector('.valor_prazo').innerHTML = prazo;
        document.querySelector('#simulacao_valor').innerHTML = 'R$ 12.3123';

        Loading.hide();
    });
});
