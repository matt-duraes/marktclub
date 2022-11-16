// @template "painel"
// @import "novo"

window.addEventListener('load', () => {
    const LINK = document.getElementById('LINK').value;

    const botaoAdd = document.getElementById('botao_add_tarefa');
    const PaginaAddTarefa = new Pagina('nova-tarefa', LINK + '/demanda/nova-tarefa', {}, true, true, demandaNovaLoad);

    botaoAdd.addEventListener('click', () => {
        PaginaAddTarefa.abrir();
    });
});
