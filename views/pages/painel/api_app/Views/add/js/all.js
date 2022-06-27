// @template "painel"
// @system "Form"
// @system "Galeria"
// @system "Mascara"
// @resource "painel/app_geral_add"

const scopeGrupoLista = document.querySelectorAll('.bloco_grupo');
console.log(scopeGrupoLista);
if (scopeGrupoLista.length > 0) {
    scopeGrupoLista.forEach(item => {
        item.querySelector('header').addEventListener('click', () => {
            item.classList.toggle('aberto');
        });
    });
}
