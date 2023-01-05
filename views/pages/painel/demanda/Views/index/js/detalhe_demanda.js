const detalheDemanda = () => {
    const demandaId = document.getElementById('input_demanda_id').value;

    /*
    |--------------------------------------------------------------------------
    | HELPER DE AJUDA
    |--------------------------------------------------------------------------
    */
    const listaAjuda = document.querySelectorAll('#bloco_demanda_tarefa *[data-ajuda]');
    listaAjuda.forEach(bloco => {
        bloco.addEventListener('mouseover', () => {
            const texto = bloco.getAttribute('data-ajuda');
            Ajuda.show(bloco, texto);
        });
        bloco.addEventListener('mouseout', () => {
            Ajuda.hide();
        });
    });

    const listaTarefa = document.querySelectorAll('#bloco_demanda_tarefa .bloco_tarefa article');
    listaTarefa.forEach(tarefa => {
        const id = tarefa.getAttribute('data-id');
        const PaginaEditar = new Pagina(
            'tarefa-editar-' + id,
            LINK + '/demanda/tarefa-editar/' + id + '/' + demandaId,
            {},
            true,
            false,
            editarTarefa
        );
        const editar = tarefa.querySelector('.botao_editar');
        const deletar = tarefa.querySelector('.botao_deletar');
        editar.addEventListener('click', () => {
            PaginaEditar.abrir();
        });
        deletar.addEventListener('click', async () => {
            if (
                await Alerta.confirmar(
                    'Deletar tarefa',
                    'Tem certeza que deseja deletar essa tarefa? Essa ação não poderá ser desfeita.',
                    false
                )
            ) {
                deletarTarefa(id, tarefa);
            }
        });
    });

    const deletarTarefa = async (id, tarefa) => {
        const resposta = await fetch(LINK + '/demanda/tarefa/' + id, {
            method: 'DELETE',
        });

        if (!(await respostaJson(resposta, 'Erro ao deletar a tarefa, por favor, tente novamente.'))) {
            return;
        }

        tarefa.parentNode.removeChild(tarefa);
        Alerta.notificacao('Tarefa deletada com sucesso.', true);
    };
};
