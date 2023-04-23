// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Galeria"
// @system "Calendario"
// @system "Mascara"
// @resource "site/passo_passo"

window.onload = function () {
    const botaoAuxilio = document.querySelector('.auxilio_ressarcimento');
    botaoAuxilio.addEventListener('click', function (event) {
        const clicado = event.currentTarget;
        abrirModal(clicado);
    });
};

function abrirModal(clicado) {
    const LINK = document.getElementById('LINK').value;
    const botaoAuxilio = {};
    const id = clicado.getAttribute('data-id');
    const url = `${LINK}/saude/abrirtabela?id=${id}`;
    botaoAuxilio[id] = new Pagina('auxilio ressarcimento - ' + id, url);
    botaoAuxilio[id].abrir();
}
