let idDemanda;

const PopupTemp = new Popup();
const usuarioGerente = false;
const listaColuna = $$('#bloco_demanda_index .bloco_coluna');

const cloneDemandaTarefa = $('#clone_demanda_tarefa_item');
cloneDemandaTarefa.removeAttribute('id');

const adicionarTexto = (bloco, classe, valor) => {
    bloco.querySelector(classe).innerText = valor;
};
const adicionarHtml = (bloco, classe, valor) => {
    bloco.querySelector(classe).innerHTML = valor;
};
const removerDisplayNone = (bloco, classe, valor) => {
    if (valor == '') {
        return;
    }
    bloco.querySelector(classe).classList.remove('display_none');
};
const adicionarNovaTarefa = (blocoLista, item) => {
    blocoLista.classList.remove('display_none');
    const clone = cloneDemandaTarefa.cloneNode(true);

    const blocoEquipe = clone.querySelector('.item_equipe');
    blocoEquipe.setAttribute('data-ajuda', item.equipe.nome);
    blocoEquipe.style.backgroundImage = `url(${item.equipe.imagem})`;

    adicionarTexto(clone, '.item_titulo', item.titulo);
    adicionarTexto(clone, '.item_tipo', item.tipo);
    adicionarTexto(clone, '.item_status', item.status);
    adicionarHtml(clone, '.item_texto', item.texto);
    adicionarTexto(clone, '.item_data_inicio', item.data_inicio);
    adicionarTexto(clone, '.item_data_final', item.data_final);
    removerDisplayNone(clone, '.bloco_dono', item.dono || usuarioGerente ? 'sim' : '');
    removerDisplayNone(clone, '.bloco_data_inicio', item.data_inicio);
    removerDisplayNone(clone, '.bloco_data_final', item.data_final);
    blocoLista.insertBefore(clone, blocoLista.firstChild);
};
const atualizarTarefaExistente = (blocoLista, item) => {
    //
};
