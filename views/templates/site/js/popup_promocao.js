window.addEventListener('load', () => {
    const blocoPopup = $('#bloco_popup_promocao');
    if (!blocoPopup) {
        return;
    }
    const PopupPromocao = new Popup('promocao', 'bloco_popup_promocao');
    PopupPromocao.abrir();
});
