const listaDragDrop = $$('#bloco_demanda_index .bloco_coluna.drag .conteudo');
for (const item of listaDragDrop) {
    new DragDrop()
        .grupo('#bloco_demanda_index .bloco_coluna.drag .conteudo')
        .bloco(item)
        .eventoMover(async e => {
            manipularBlocoZero(e.to);
        })
        .botao('.drag')
        .eventoFim(e => {
            const bloco = e.to.closest('.bloco_coluna');
            if (e.from != e.to) {
                mudarStatusDemanda(bloco, e.item);
            }
            atualizarOrdemDemanda(bloco);
        })
        .item('article')
        .iniciar();
}

const manipularBlocoZero = atual => {
    let blocoZero, quantidade;
    for (const coluna of listaDragDrop) {
        blocoZero = coluna.querySelector('.tarefa_zero');
        if (!blocoZero) {
            continue;
        }
        quantidade = coluna.querySelectorAll('.bloco_kambam_item:not(.drag_drop_fantasma)').length;
        if (quantidade == 0) {
            blocoZero.classList.remove('display_none');
            continue;
        }
        blocoZero.classList.add('display_none');
    }
    const blocoZeroAtual = atual.querySelector('.tarefa_zero');
    blocoZeroAtual.classList.add('display_none');
};
