// @template "painel"
// @import "demanda_detalhe"
// @import "demanda_salvar"
// @import "demanda_editar"
// @import "tarefa_editar"
// @import "tarefa_salvar"

window.addEventListener('load', () => {
    const LINK = document.getElementById('LINK').value;

    /*
    |--------------------------------------------------------------------------
    | ABRIR ADD NOVO
    |--------------------------------------------------------------------------
    */
    const botaoAdd = document.getElementById('botao_add_tarefa');
    const PaginaAddTarefa = new Pagina(
        'demanda-salvar',
        LINK + '/demanda/demanda-salvar',
        {},
        true,
        true,
        demandaSalvar
    );

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
        const PaginaDetalhe = new Pagina(
            'demanda-' + id,
            LINK + '/demanda/demanda/' + id,
            {},
            true,
            true,
            demandaDetalhe
        );
        tarefa.addEventListener('click', () => {
            PaginaDetalhe.abrir();
        });
    });
});
