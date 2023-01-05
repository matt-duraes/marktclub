// @template "painel"
// @import "detalhe_demanda"
// @import "nova_demanda"

window.addEventListener('load', () => {
    const LINK = document.getElementById('LINK').value;

    /*
    |--------------------------------------------------------------------------
    | ABRIR ADD NOVO
    |--------------------------------------------------------------------------
    */
    const botaoAdd = document.getElementById('botao_add_tarefa');
    const PaginaAddTarefa = new Pagina('nova-tarefa', LINK + '/demanda/nova', {}, true, true, demandaNova);

    botaoAdd.addEventListener('click', () => {
        PaginaAddTarefa.abrir();
    });

    /*
    |--------------------------------------------------------------------------
    | ABRIR DETALHE DA DEMANDA
    |--------------------------------------------------------------------------
    */
    const tarefaLista = document.querySelectorAll('#bloco_demanda_index article');
    tarefaLista.forEach(tarefa => {
        const id = tarefa.getAttribute('data-id');
        const PaginaDetalhe = new Pagina('tarefa-' + id, LINK + '/demanda/tarefa/' + id, {}, true, true);
        tarefa.addEventListener('click', () => {
            PaginaDetalhe.abrir();
        });
    });
});
