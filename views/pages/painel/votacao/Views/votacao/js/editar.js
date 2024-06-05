window.addEventListener('load', () => {
    const bloquearEditar = $('#botao_bloquear_votacao');
    if (!bloquearEditar) {
        return;
    }

    if (bloquearEditar.valor() == 'sim') {
        const botao = $('#bloco_botao_salvar');
        if (botao) {
            botao.remove();
        }
    }
});
