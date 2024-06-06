window.addEventListener('load', () => {
    const bloquearEditar = $('#inputinterno_bloqueado');
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
