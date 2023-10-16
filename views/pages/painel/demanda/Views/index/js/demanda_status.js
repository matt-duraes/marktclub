const mudarStatusDemanda = async (demanda, item) => {
    const status = demanda.getAttribute('data-status');
    const id = item.getAttribute('data-id');
    await ajaxPost(
        LINK + '/demanda/demanda-status',
        {
            id,
            status,
        },
        'Erro ao mudar status da demanda, por favor, tente novamente.'
    );
};

const atualizarOrdemDemanda = async bloco => {
    const lista = bloco.querySelectorAll('.bloco_tarefa_item');
    const id = [];
    for (const item of lista) {
        id.push(item.getAttribute('data-id'));
    }
    await ajaxPost(
        LINK + '/demanda/demanda-ordenar',
        { id },
        'Erro ao atualizar a ordem das demandas, por favor, tente novamente.'
    );
};
