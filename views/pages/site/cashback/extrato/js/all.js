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
        botaoSolicitarResgate.addEventListener('click', () => {
            Alerta.mensagem('Salvo inválido!', 'Você precisa de pelo menos 1 ponto para solictar resgate.', '!');
        });
        return;
    }
    const PopupResgate = new Popup('Resgatar pontos', 'bloco_resgatar_ponto');
    botaoPopupResgate.addEventListener('click', () => {
        PopupResgate.abrir();
    });

    const botaoSolicitarPonto = $('#botao_solicitar_ponto');
    const form = $('#bloco_resgatar_ponto form');
    const inputNome = $('#input_ponto_nome');
    const inputEmail = $('#input_ponto_email');
    const inputQuantidade = $('#input_ponto_quantidade');

    const solicitarResgate = async () => {
        if (!(await validarInput(form))) {
            return;
        } else if (inputQuantidade.value > saldo) {
            Alerta.notificacao('Você não pode solicitar mais pontos que seu saldo atual.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(LINK + '/ponto-cvs', {
            nome: inputNome.value,
            email: inputEmail.value,
            ponto: inputQuantidade.value,
        });
        Loading.hide();
        if (false === resposta) {
            return;
        }
        PopupResgate.fechar();
        await Alerta.mensagem('Dados Enviados!', 'Sua solicitação foi enviada. Aguarde nosso retorno!', true);
        Loading.show();
        window.location.replace(LINK + '/ponto-cvs');
    };

    adicionarEventoEnter([inputNome, inputEmail, inputQuantidade], solicitarResgate);
    botaoSolicitarPonto.addEventListener('click', () => {
        solicitarResgate();
    });
});
