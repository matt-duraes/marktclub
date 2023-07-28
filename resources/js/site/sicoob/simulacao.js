// @template "login"
// @system "Loading"
// @system "Alerta"
// @system "Mascara"
// @system "Form"

const botaoSimularConsignado = $('#botao_fazer_simulacao');
const botaoContratarConsignado = $('#enviar_solicitacao');
const botaoPopupRegulamento = $$('.abrirModalRegulamento');
const botaoFechar = $$('.botao_fechar_popup');
const botaoVoltar = $('.bloco_credito_geral_simulacao .bloco_credito .bloco_botao .cinza');

function adicionarEventoSimularConsignado() {
    botaoSimularConsignado.addEventListener('click', async e => {
        e.preventDefault();
        const tipo = $('#formulario_emprestimo input[name=tipo_financiamento]').value;
        const valor = $('#formulario_emprestimo input[name=financiamento]').value;
        const prazo = $('#formulario_emprestimo input[name=parcela]').value.slice(0, 2);

        if (valor === '') {
            Alerta.mensagem('Campo obrigatório!', 'Digite o valor que deseja simular.');
            return false;
        } else if (prazo === '') {
            Alerta.mensagem('Campo obrigatório!', 'Escolha a quantidade de parcelas que deseja simular.');
            return false;
        }

        const query = `&tipo=${tipo}&valor=${valor}&prazo=${prazo}`;

        const resposta = await ajaxGet('/credito/simulacao?operadora=sicoob-judiciario' + query);
        if (false === resposta) {
            return;
        }

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        if (resposta.status === 200) {
            irParaProximoPasso(botaoSimularConsignado);
            $('.valor_emprestimo').innerHTML = json.dado.lista.valor;
            $('.valor_prazo').innerHTML = json.dado.lista.parcelas;
            $('#simulacao_valor').innerHTML = 'R$ ' + json.dado.lista.valor_parcelas;
            return;
        }

        Alerta.notificacao(
            json.erro.mensagem !== undefined
                ? json.erro.mensagem
                : 'Ocorreu um erro ao simular, por favor, tente novamente.',
            false
        );

        Loading.hide();
    });
}

function adicionarEventoContratarConsignado() {
    botaoContratarConsignado.addEventListener('click', async e => {
        e.preventDefault();
        const tipo = formulario.querySelector('input[name=tipo_financiamento]').value;
        const valor = formulario.querySelector('input[name=financiamento]').value;
        const prazo = formulario.querySelector('input[name=parcela]').value;

        const body = new FormData();
        body.append('tipo', tipo);
        body.append('valor', valor);
        body.append('prazo', prazo);
        body.append('operadora', 'sicoob-judiciario');

        const resposta = await fetch('/credito/salvar', {
            method: 'POST',
            body,
        });

        if (resposta.status !== 201) {
            Alerta.notificacao(
                json.erro.mensagem !== undefined
                    ? json.erro.mensagem
                    : 'Ocorreu um erro ao salvar a contratação, por favor, tente novamente.',
                false
            );
            return;
        }
        const botaoVoltar = document.querySelector('.bloco_credito_geral_simulacao .bloco_credito .bloco_botao .cinza');
        irParaPassoAnterior(botaoVoltar);
        limparFormulario(formulario);
        Alerta.notificacao('Envio do formulário com sucesso!', true);
    });
}

function limparFormulario(formulario) {
    const inputs = formulario.querySelectorAll('input');

    inputs.forEach(input => {
        if (input.type !== 'submit' && input.type !== 'button') {
            input.value = '';
        }
    });
}

function adicionarEventoPopupRegulamento() {
    botaoPopupRegulamento.forEach(modelo => {
        const tipo = modelo.getAttribute('data-tipo');
        const PaginaDetalhe = new Pagina(tipo, '/sicoob-regulamento/' + tipo, {}, true, true, carregarFuncoesBusca);
        modelo.addEventListener('click', () => {
            PaginaDetalhe.abrir();
        });
    });
}

function adicionarEventoFechar() {
    botaoFechar.forEach(fecha => {
        fecha.addEventListener('click', () => {
            Pagina.staticFechar();
        });
    });
}

function adicionarEventoVoltar() {
    botaoVoltar.addEventListener('click', () => {
        irParaPassoAnterior(botaoVoltar);
    });
}

function carregarFuncoesBusca() {
    adicionarEventoFechar();
}

window.addEventListener('load', () => {
    adicionarEventoSimularConsignado();
    adicionarEventoContratarConsignado();
    adicionarEventoPopupRegulamento();
});
