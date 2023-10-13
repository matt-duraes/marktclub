// @sytem "Alerta"

window.addEventListener('load', () => {
    const botao = $('#botao_fazer_solicitacao');
    const nome = $('#input_nome');
    const cpf = $('#input_cpf');
    botao.addEventListener('click', () => {
        if (nome.value == '') {
            Alerta.notificacao('O campo nome é obrigatório!', false);
            return;
        } else if (cpf.value == '') {
            Alerta.notificacao('O campo CPF é obrigatório!', false);
            return;
        }
        Alerta.notificacao('Solicitação enviada com sucesso!', true);
    });
});
