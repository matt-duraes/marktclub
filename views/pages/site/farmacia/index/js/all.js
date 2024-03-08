// @system "Popup"
// @template "site"
// @resource "site/loja/favorito"

window.addEventListener('load', () => {
    const blocoPopup = $('#popup_medicamento');
    if (!blocoPopup) {
        return;
    }
    const PopupAlerta = new Popup('alerta', 'popup_medicamento', true, false);
    PopupAlerta.abrir();
});
