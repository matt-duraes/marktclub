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
let valorTotal = 0;
let numeroParcela = 0;
/*
|--------------------------------------------------------------------------
| REGULAMENTO
|--------------------------------------------------------------------------
*/
const PaginaDetalhe = new Pagina(tipo, '/sicoob-regulamento/' + tipo);
botaoAbrirRegulamento.addEventListener('click', () => {
    PaginaDetalhe.abrir();
});

/*
|--------------------------------------------------------------------------
| SIMULAÇÃO
|--------------------------------------------------------------------------
*/
botaoFazerSimulacao.addEventListener('click', async () => {
    if (!(await validarInput(formEmprestimo))) {
        return;
    }
    const valorTemp = inputValor.value.replace(/\./g, '').replace(',', '.');
    const parcelaTemp = inputParcela.value;
    Loading.show();
    const resposta = await ajaxPost(
        LINK + '/credito/simulacao',
        {
            tipo,
            // eslint-disable-next-line camelcase
            valor_total: valorTemp,
            parcela: parcelaTemp,
            operadora: 'sicoob',
        },
        'Erro ao fazer simulação, por favor, tente novamente.'
    );
    Loading.hide();
    if (false === resposta) {
        return;
    }

    valorTotal = valorTemp;
    numeroParcela = parcelaTemp;

    respostaValorTotal.innerText = 'R$ ' + resposta.dado.valor_total;
    respostaValorParcela.innerText = 'R$ ' + resposta.dado.valor_parcela;
    respostaParcela.innerText = resposta.dado.parcela + 'X';

    irParaProximoPasso(botaoFazerSimulacao);
});
botaoRefazerSimulacao.addEventListener('click', () => {
    irParaPassoAnterior(botaoRefazerSimulacao);
});

/*
|--------------------------------------------------------------------------
| CONTRATAR
|--------------------------------------------------------------------------
*/
botaoEnviarSimulacao.addEventListener('click', async () => {
    const resposta = await ajaxPost(
        LINK + '/credito/salvar',
        {
            tipo,
            // eslint-disable-next-line camelcase
            valor_total: valorTotal,
            parcela: numeroParcela,
            operadora: 'sicoob',
        },
        'Erro ao fazer contratação, por favor, tente novamente.'
    );
    if (false === resposta) {
        return;
    }

    await Alerta.mensagem(
        'Solicitação enviada',
        'Sua solicitação foi enviada com sucesso!<br>O Sicoob Judiciário entrará em contato em breve.',
        true
    );

    window.location.replace(LINK + '/credito/sicoob');
});
