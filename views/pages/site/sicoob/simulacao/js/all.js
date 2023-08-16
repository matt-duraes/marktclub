// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/passo_passo"

const botaoFazerSimulacao = $('#botao_fazer_simulacao');
const botaoEnviarSimulacao = $('#botao_enviar_simulacao');
const botaoRefazerSimulacao = $('#botao_refazer_simulacao');
const botaoAbrirRegulamento = $('#botao_abrir_regulamento');

const formEmprestimo = $('#form_empresatimo');
const inputValor = $('#input_valor');
const inputParcela = $('#input_parcela');

const respostaValorParcela = $('#resposta_valor_parcela');
const respostaValorTotal = $('#resposta_valor_total');
const respostaParcela = $('#resposta_parcela');

const tipo = $('#input_tipo').value;
/*
|--------------------------------------------------------------------------
| REGULAMENTO
|--------------------------------------------------------------------------
*/
const PaginaDetalhe = new Pagina(tipo, '/sicoob-regulamento/' + tipo);
botaoAbrirRegulamento.addEventListener('click', () => {
    PaginaDetalhe.abrir();
});

botaoFazerSimulacao.addEventListener('click', async () => {
    if (!(await validarInput(formEmprestimo))) {
        return;
    }
    const resposta = await ajaxPost(
        LINK + '/credito/simulacao',
        {
            tipo,
            // eslint-disable-next-line camelcase
            valor_total: inputValor.value.replace(/\./g, '').replace(',', '.'),
            parcela: inputParcela.value,
            operadora: 'sicoob',
        },
        'Erro ao fazer simulação, por favor, tente novamente.'
    );
    if (false === resposta) {
        return;
    }

    respostaValorTotal.innerText = 'R$ ' + resposta.dado.valor_total;
    respostaValorParcela.innerText = 'R$ ' + resposta.dado.valor_parcela;
    respostaParcela.innerText = resposta.dado.parcela + 'X';

    irParaProximoPasso(botaoFazerSimulacao);
});
botaoRefazerSimulacao.addEventListener('click', () => {
    irParaPassoAnterior(botaoRefazerSimulacao);
});
