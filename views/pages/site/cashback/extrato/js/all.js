// @template "site"
// @system "Mascara"
// @system "Form"
// @system "Alerta"
// @system "Loading"
// @resource "site/tab"

window.addEventListener('load', () => {
    const botaoPopupResgate = document.querySelector('#botao_popup_resgate');
    const botaoPopupEscolherMetodo = document.querySelector('#botao_popup_metodo');
    const botaoPopupResgateDinheiro = document.querySelector('#botao_popup_resgate_dinheiro');
    const blocoResgateDinheiro = document.querySelector('.resgate_dinheiro');
    let tipo = '';

    const PopupResgate = new Popup('Resgatar pontos', 'bloco_resgatar_ponto', false);
    const PopupResgateMetodo = new Popup('Escolher método', 'bloco_escolher_metodo', false);

    function abrirPopupResgate() {
        tipo = document.querySelector('#input_tipo_resgate').value;
        if (tipo !== 'dinheiro' && tipo !== 'mensalidade') {
            Alerta.notificacao('Selecione um tipo de resgate.', false);
            return;
        }
        if (blocoResgateDinheiro) {
            blocoResgateDinheiro.classList.toggle('display_none', tipo !== 'dinheiro');
        }
        PopupResgate.abrir();
    }

    if (botaoPopupResgate) {
        botaoPopupResgate.addEventListener('click', () => PopupResgateMetodo.abrir());
    }

    if (botaoPopupResgateDinheiro) {
        botaoPopupResgateDinheiro.addEventListener('click', () => {
            document.querySelector('#input_tipo_resgate').value = 'dinheiro';
            abrirPopupResgate();
        });
    }

    if (botaoPopupEscolherMetodo) {
        botaoPopupEscolherMetodo.addEventListener('click', abrirPopupResgate);
    }

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
        Loading.show();
        if (tipo === 'mensalidade') {
            inputTitular.value = '';
            inputCpf.value = '';
            inputBanco.value = '';
            inputAgencia.value = '';
            inputContaBancaria.value = '';
            inputTipoConta.value = '';
        }
        const resposta = await ajaxPost(LINK + '/cashback/resgatar', {
            tipoResgate: tipo,
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
        location.href = LINK + '/cashback/extrato';
    };

    adicionarEventoEnter([inputNome, inputEmail, inputQuantidade], solicitarResgate);
    botaoSolicitarPonto.addEventListener('click', () => {
        solicitarResgate();
    });
});
