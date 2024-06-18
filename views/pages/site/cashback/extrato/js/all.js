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
    const inputConta = $('#input_conta');
    const inputTitular = $('#input_titular');
    const inputCpf = $('#input_cpf_conta');
    const inputBanco = $('#input_banco');
    const inputAgencia = $('#input_agencia');
    const inputContaBancaria = $('#input_conta');
    const inputTipoConta = $('#input_tipo');

    inputConta.addEventListener('input', event => {
        let value = event.target.value;
        // Remove todos os espaços existentes
        value = value.replace(/\s+/g, '');

        // Adiciona um espaço a cada 4 dígitos
        let formattedValue = value.match(/.{1,4}/g).join(' ');

        // Atualiza o valor do input com o formato correto
        event.target.value = formattedValue;
    });

    const solicitarResgate = async () => {
        if (!(await validarInput(form))) {
            return;
        } else if (inputQuantidade.value > saldo) {
            Alerta.notificacao('Você não pode solicitar mais pontos que seu saldo atual.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(LINK + '/cashback/resgatar', {
            nome: inputNome.value,
            email: inputEmail.value,
            pontos: inputQuantidade.value,
            titular: inputTitular.value,
            cpf: inputCpf.value,
            banco: inputBanco.value,
            agencia: inputAgencia.value,
            contaBancaria: inputContaBancaria.value.replace(/\s+/g, ''),
            tipoConta: inputTipoConta.value,
        });
        Loading.hide();
        if (false === resposta) {
            return;
        }
        PopupResgate.fechar();
        await Alerta.mensagem('Dados Enviados!', 'Sua solicitação foi enviada. Aguarde nosso retorno!', true);
        Loading.show();
    };

    adicionarEventoEnter([inputNome, inputEmail, inputQuantidade], solicitarResgate);
    botaoSolicitarPonto.addEventListener('click', () => {
        solicitarResgate();
    });
});
