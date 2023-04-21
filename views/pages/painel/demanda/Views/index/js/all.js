// @template "painel"
// @import "demanda_detalhe"
// @import "demanda_salvar"
// @import "demanda_editar"
// @import "tarefa_editar"
// @import "tarefa_salvar"

window.addEventListener('load', () => {
    const LINK = document.getElementById('LINK').value;
    const area = document.querySelector('#input_area').value || '';
    /*
    |--------------------------------------------------------------------------
    | ABRIR ADD NOVO
    |--------------------------------------------------------------------------
    */
    const botaoAdd = document.getElementById('botao_add_tarefa');
    const PaginaAddTarefa = new Pagina(
        'demanda-salvar',
        LINK + '/demanda/demanda-salvar/' + area,
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
        tarefa.addEventListener('click', e => {
            if (e.target.classList.contains('botao_mover') || e.target.closest('.botao_mover')) {
                return;
            }
            PaginaDetalhe.abrir();
        });
    });

    const blocoTarefaLiberada = document.getElementById('bloco_coluna_liberado');
    reordenarTarefaLiberada = async () => {
        const body = new FormData();
        blocoTarefaLiberada.querySelectorAll('.bloco_tarefa_item').forEach(tarefa => {
            body.append('id[]', tarefa.getAttribute('data-id'));
        });
        const resposta = await fetch(LINK + '/demanda/demanda-ordenar', {
            method: 'POST',
            body,
        });
        if (resposta.status == 204) {
            return;
        }
        Alerta.notificacao('Erro ao ordenar tarefas, por favor, tente novamente.', false);
    };
});
