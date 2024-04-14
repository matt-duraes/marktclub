window.addEventListener('load', () => {
    const bloco = $('#bloco_arquivo');
    if (!bloco) {
        return;
    }
    const botao = $('#botao_arquivo_abrir');
    const PopupArquivo = new Popup('arquivo-parceiro', 'bloco_arquivo', true, false);
    botao.evento('click', () => {
        PopupArquivo.abrir();
    });
});
