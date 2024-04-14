window.addEventListener('load', () => {
    const botaoDeclaracao = $('#botao_salvar_declaracao');
    if (!botaoDeclaracao) {
        return;
    }
    const parceiroId = $('#input_loja_id').valor();
    botaoDeclaracao.evento('click', () => {
        salvarDeclaracao(parceiroId);
    });
});
