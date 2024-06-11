window.addEventListener('load', () => {
    const botaoPopupPergunta = $('#botao_adicionar_pergunta');
    if (botaoPopupPergunta) {
        return;
    }

    const botao = $('#bloco_botao_salvar');
    if (botao) {
        botao.remove();
    }
});
