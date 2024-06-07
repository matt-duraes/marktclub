window.addEventListener('load', () => {
    const botao = $('#botao_resultado_votacao');
    if (!botao) {
        return;
    }

    const PopupResultado = new Popup('Resultado', 'bloco_resultado', true, false);
    PopupResultado.abrir();
    botao.evento('click', () => {
        PopupResultado.abrir();
    });
});
