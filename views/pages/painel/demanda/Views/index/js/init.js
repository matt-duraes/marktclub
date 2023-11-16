let idDemanda, statusDemanda, liberadoDemanda;

const inputTarefaId = $('#input_tarefa_id');
const inputTarefaTitulo = $('#input_tarefa_titulo');
const inputTarefaTexto = $('#input_tarefa_texto');
const inputTarefaTipo = $('#input_tarefa_tipo');

const PopupDemandaEditar = new Popup('Editar Demanda', 'bloco_demanda_editar', true, false, demandaEditar);
const PopupTarefa = new Popup('Nova Tarefa', 'bloco_tarefa_nova', true, false);
const PopupTemp = new Popup();
const listaColuna = $$('#bloco_demanda_index .bloco_coluna');

const cloneDemandaTarefa = $('#clone_demanda_tarefa_item');
cloneDemandaTarefa.removeAttribute('id');

const cloneTarefaLista = $('#clone_tarefa_lista');
cloneTarefaLista.removeAttribute('id');

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

// ADICIONAR/EDITAR TAREFA
const adicionarNovaTarefa = item => {
    const bloco = $('#bloco_tarefa_lista');
    if (!bloco) {
        return;
    }
    bloco.classList.remove('display_none');

    const clone = cloneDemandaTarefa.cloneNode(true);
    clone.setAttribute('id', 'id_tarefa_' + item.id);
    clone.setAttribute('data-tipo', item.tipo_valor);

    const blocoEquipe = clone.querySelector('.item_equipe');
    blocoEquipe.setAttribute('data-ajuda', item.equipe.nome);
    blocoEquipe.style.backgroundImage = `url(${item.equipe.imagem})`;
    const editarDeletar = item.dono || usuarioGerente ? 'sim' : '';

    adicionarTexto(clone, '.item_titulo', item.titulo);
    adicionarTexto(clone, '.item_tipo', item.tipo);
    adicionarTexto(clone, '.item_status', item.status);
    adicionarHtml(clone, '.item_texto', item.texto);
    adicionarTexto(clone, '.item_data_inicio', item.data_inicio);
    adicionarTexto(clone, '.item_data_final', item.data_final);
    removerDisplayNone(clone, '.bloco_dono', editarDeletar);
    removerDisplayNone(clone, '.bloco_data_inicio', item.data_inicio);
    removerDisplayNone(clone, '.bloco_data_final', item.data_final);
    bloco.insertBefore(clone, bloco.firstChild);

    if (liberadoDemanda && item.status == 'aguardando') {
        removerDisplayNone(clone, '.item_finalizar', 'sim');
    } else if (item.status == 'concluida') {
        removerDisplayNone(clone, '.item_finalizado', 'sim');
    }

    if (editarDeletar == 'sim') {
        clone.querySelector('.botao_editar').addEventListener('click', () => {
            abrirPopupTarefaEditar(item.id);
        });
        clone.querySelector('.botao_deletar').addEventListener('click', () => {
            tarefaDeletar(item.id);
        });
    }
};
const atualizarTarefaExistente = (id, titulo, texto, tipo) => {
    const bloco = $('#id_tarefa_' + id);
    bloco.setAttribute('data-tipo', tipo);
    adicionarTexto(bloco, '.item_titulo', titulo);
    adicionarHtml(bloco, '.item_texto', texto);
};

const abrirPopupTarefaEditar = id => {
    PopupTarefa.abrir();
    const bloco = $('#id_tarefa_' + id);
    inputTarefaId.value = id;
    inputTarefaTitulo.value = bloco.querySelector('.item_titulo').innerText;
    formValue(inputTarefaTipo, bloco.getAttribute('data-tipo'));
    formValue(inputTarefaTexto, bloco.querySelector('.item_texto').innerHTML);
};

// ADICIONAR/EDITAR DEMANDA
const adicionarNovaDemanda = (bloco, item, abrir) => {
    return new Promise(resolve => {
        const id = item.id;
        const clone = cloneTarefaLista.cloneNode(true);
        clone.setAttribute('data-id', item.id);
        clone.setAttribute('data-status', item.status);

        const perfil = clone.querySelector('.tarefa_perfil');
        perfil.setAttribute('data-ajuda', item.equipe.nome);
        perfil.style.backgroundImage = `url(${item.equipe.imagem})`;

        perfil.addEventListener('mouseover', () => {
            const texto = perfil.getAttribute('data-ajuda');
            Ajuda.show(perfil, texto);
        });
        perfil.addEventListener('mouseout', () => {
            Ajuda.hide();
        });

        adicionarTexto(clone, '.tarefa_titulo', item.titulo);
        adicionarTexto(clone, '.tarefa_data_criacao', item.data_criacao);
        adicionarTexto(clone, '.tarefa_data_entrega', item.data_entrega);
        removerDisplayNone(clone, '.bloco_entrega', item.data_entrega);
        bloco.appendChild(clone);

        const blocoZero = bloco.querySelector('.tarefa_zero');
        if (blocoZero) {
            blocoZero.classList.add('display_none');
        }

        const PaginaDemanda = new Pagina(
            'demanda-' + id,
            LINK + '/demanda/demanda/' + id,
            undefined,
            true,
            true,
            demandaDetalhe
        );
        if (abrir === true) {
            PaginaDemanda.abrir();
        }
        clone.addEventListener('click', e => {
            if (e.target.classList.contains('botao_drag') || e.target.closest('.botao_drag')) {
                return;
            }
            PaginaDemanda.abrir();
        });
        resolve(true);
    });
};

// CONTATO NUMERO TAREFA
const contarTarefaDemanda = coluna => {
    const numero = coluna.querySelectorAll('.conteudo .bloco_tarefa_item').length;
    const blocoNumero = coluna.querySelector('header h1 span');
    blocoNumero.innerText = `(${numero})`;
};

// DELETAR TAREFA
const tarefaDeletar = async id => {
    if (
        !(await Alerta.confirmar(
            'Deletar tarefa',
            'Tem certeza que deseja deletar essa tarefa? Essa ação não poderá ser desfeita.',
            false
        ))
    ) {
        return;
    }

    const bloco = $('#id_tarefa_' + id);
    if (!bloco) {
        return;
    }
    bloco.classList.add('display_none');
    const resposta = await ajaxPost(
        LINK + '/demanda/tarefa-deletar/' + id,
        undefined,
        'Erro ao deletar tarefa, por favor, tente novamente.'
    );
    if (false === resposta) {
        bloco.classList.remove('display_none');
        return;
    }
    bloco.remove();
    verificarExisteTarefa();
};
const verificarExisteTarefa = () => {
    const quantidade = $$('#bloco_tarefa_lista article.tarefa').length;
    if (quantidade > 0) {
        return;
    }
    const bloco = $('#bloco_tarefa_zero');
    if (!bloco) {
        return;
    }
    bloco.classList.remove('display_none');
};
