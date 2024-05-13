window.addEventListener('load', () => {
    const botaoArquivo = document.querySelector('#botao_arquivo');
    if (!botaoArquivo) {
        return;
    }

    const PopupArquivos = new Popup('popup-arquivos', 'bloco_arquivos', true);
    botaoArquivo.addEventListener('click', () => {
        PopupArquivos.abrir();
    });
});
