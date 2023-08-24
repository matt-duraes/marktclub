// @template "site"
// @system "Form"
// @system "Alerta"
// @system "Pagina"
// @system "Mascara"
// @resource "site/tab"
// @resource "site/loja/cheque_bonus"
// @resource "site/loja/declaracao"

const declaracaoParceiro = $('#input_declaracao_parceiro').value;
const declaracaoModelo = $('#input_declaracao_modelo').value;
const procedimentoDeclaracao = lista => {
    lista.forEach(botao => {
        botao.addEventListener('click', () => {
            const versao = botao.querySelector('header h1').innerText || '';
            salvarDeclaracao(declaracaoParceiro, declaracaoModelo, versao);
        });
    });
};
window.addEventListener('load', () => {
    const procedimento = $('#input_tipo_procedimento').value;
    if (procedimento == 'cheque-bonus') {
        procedimentoChequeBonus($$('.botao_abrir_parceiro'));
    } else if (procedimento == 'declaracao') {
        procedimentoDeclaracao($$('.botao_abrir_parceiro'));
    }
});
