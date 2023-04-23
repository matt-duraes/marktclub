// @system "Form"
// @template "site"
// @system "Alerta"
// @system "Pagina"
// @resource "site/tab"

window.addEventListener('load', () => {
    const simulacaoConsignado = document.querySelectorAll('.botao_fazer_simulacao');
    simulacaoConsignado.forEach((btn, index) => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            let form = this.closest('form');

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

            form.querySelector('.resultado').classList.add('mostra');
            form.querySelector('input[name=parcela]').value = prazo;
            form.querySelector('.resultado_valor').innerHTML = 'R$ 12.3123';

            Loading.hide();
        });
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
            document.querySelector('#LINK').value + '/sicoob-regulamento/' + tipo,
            {},
            true,
            true,
            carregarFuncoesBusca
        );

        modelo.addEventListener('click', () => {
            PaginaDetalhe.abrir();
        });
    });
});
