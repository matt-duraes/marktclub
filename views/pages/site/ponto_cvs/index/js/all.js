// @template "site"
// @system "Mascara"
// @system "Form"
// @system "Alerta"
// @system "Loading"
// @resource "site/tab"

window.addEventListener('load', () => {
    const saldo = $('#input_ponto_saldo').value;
    const botaoPopupResgate = $('#botao_popup_resgate');
    if (saldo <= 0) {
        // botaoSolicitarResgate.addEventListener('click', () => {
        //     Alerta.mensagem('Verificar titulo', 'Verificar qual mensagem está hoje.', '!');
        // });
        // return;
    }
    const PopupResgate = new Popup('Resgatar pontos', 'bloco_resgatar_ponto');
    botaoPopupResgate.addEventListener('click', () => {
        PopupResgate.abrir();
    });

    const botaoSolicitarPonto = $('#botao_solicitar_ponto');
    botaoSolicitarPonto.addEventListener('click', () => {
        // Solicitar ponto
        PopupResgate.fechar();
    });
});
