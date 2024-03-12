// @template "site"
// @system "Form"
// @system "Alerta"
// @system "Pagina"
// @system "Mascara"
// @resource "site/tab"
// @resource "site/loja/cheque_bonus"
// @resource "site/loja/declaracao"
// @resource "site/automovel/solicitacao"

const declaracaoParceiro = $('#input_declaracao_parceiro').value;
const declaracaoModelo = $('#input_declaracao_modelo').value;
const botaoSolicitar = $$('.botao_solicitar_declaracao');

const procedimentoDeclaracao = parceiro => {
    const versao = parceiro.querySelector('header h1').innerText || '';
    salvarDeclaracao(declaracaoParceiro, declaracaoModelo, versao);
};

botaoSolicitar.forEach(botao => {
    botao.addEventListener('click', e => {
        const procedimento = $('#input_tipo_procedimento').value;
        if (procedimento == 'cheque-bonus') {
            procedimentoChequeBonus(e.target.parentNode);
        } else if (procedimento == 'declaracao') {
            procedimentoDeclaracao(e.target.parentNode);
        }
    });
});
