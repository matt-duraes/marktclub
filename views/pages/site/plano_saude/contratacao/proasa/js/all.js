// @template "site"
// @system "Alerta"
// @system "Form"
// @system "Mascara"

window.addEventListener('load', () => {
    const idSimulacao = $('#input_id_simulacao').value;
    const botaoContratar = $('#botao_contratar');
    const inputNome = $('#input_nome');
    const inputEmail = $('#input_email_pessoal');
    const inputTelefone = $('#input_telefone_celular');
    const inputTermo = $('#input_termo');

    botaoContratar.addEventListener('click', async () => {
        if (inputNome.valor() === '') {
            Alerta.notificacao('Digite seu nome para continuar.', false);
            return;
        } else if (inputTelefone.valor() === '') {
            Alerta.notificacao('Digite seu telefone celular para continuar.', false);
            return;
        } else if (inputEmail.valor() === '') {
            Alerta.notificacao('Digite seu e-mail pessoal para continuar.', false);
            return;
        } else if (!inputTermo.checked) {
            Alerta.notificacao('Aceite os termo de uso para continuar.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/saude-contratacao',
            {
                /* eslint-disable camelcase */
                id_saude_simulacao: idSimulacao,
                email_pessoal: inputEmail.value,
                telefone_celular: inputTelefone.value,
                /* eslint-enable camelcase */
                nome: inputNome.value,
            },
            'Erro ao salvar contratação, por favor, tente novamente.'
        );

        Loading.hide();
        if (false === resposta) {
            return;
        }
        await Alerta.mensagem(
            'Solicitação enviada',
            'Seus dados foram enviados com sucesso. Em média, o tempo de retorno do parceiro está sendo em 72 horas.',
            true
        );
        window.location.assign(LINK + '/saude/escolher-estado');
    });
});
