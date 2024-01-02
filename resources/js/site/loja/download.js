window.addEventListener('load', () => {
    const PopupArquivos = new Popup('popup-arquivos', 'bloco_arquivos', true);
    const botaoArquivo = document.querySelector('#botao_arquivo');

    if (botaoArquivo) {
        botaoArquivo.addEventListener('click', () => {
            PopupArquivos.abrir();
        });
    }
});
