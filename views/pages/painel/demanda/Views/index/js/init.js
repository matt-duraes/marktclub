let idDemanda, statusDemanda, liberadoDemanda;

const USUARIO_ID = $('#USUARIO_ID').value;
const USUARIO_NOME = $('#USUARIO_NOME').value;
const USUARIO_IMAGEM = $('#USUARIO_IMAGEM').value;
const USUARIO_GERENTE = $('#USUARIO_GERENTE').value;

const area = $('#input_area').value;

const inputTarefaId = $('#input_tarefa_id');
const inputTarefaTitulo = $('#input_tarefa_titulo');
const inputTarefaTexto = $('#input_tarefa_texto');
const inputTarefaTipo = $('#input_tarefa_tipo');

const PaginaFechar = new Pagina();
const PopupDemandaCancelar = new Popup('Cancelar Demanda', 'bloco_demanda_cancelar', false, false, demandaCancelar);
const PopupDemandaEditar = new Popup('Editar Demanda', 'bloco_demanda_editar', false, false, demandaEditar);
const PopupTarefa = new Popup('Nova Tarefa', 'bloco_tarefa_nova', false, false);
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
    clone.attr({
        id: 'id_tarefa_' + item.id,
        'data-tipo': item.tipo_valor,
        'data-status': item.status_valor,
    });

    const blocoEquipe = clone.querySelector('.item_equipe');
    blocoEquipe.attr({
        'data-equipe': item.equipe.id,
        'data-ajuda': item.equipe.nome,
    });
    blocoEquipe.css('backgroundImage', `url(${item.equipe.imagem})`);
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

    const botaoTrabalhar = $('.item_play', clone);
    const botaoFinalizar = $('.item_finalizar', clone);
    const botaoConcluido = $('.item_concluido', clone);
    const blocoTeste = $('.bloco_teste', clone);
    const blocoTestado = $('.bloco_testado', clone);
    const botaoLike = $('.botao_like', clone);
    const botaoDeslike = $('.botao_deslike', clone);

    if (statusDemanda == 'concluida') {
        blocoTestado.displayShow();
        botaoConcluido.displayShow();
    } else if (liberadoDemanda && item.status_valor == 'aguardando') {
        botaoTrabalhar.displayShow();
    } else if (liberadoDemanda && item.status_valor == 'andamento') {
        botaoFinalizar.displayShow();
    } else if (item.status_valor == 'concluida' || statusDemanda == 'teste') {
        blocoTeste.displayShow();
        botaoConcluido.displayShow();
    }
    botaoTrabalhar.evento('click', () => {
        comecarTrabalhar(botaoTrabalhar, botaoFinalizar, item.id);
    });
    botaoFinalizar.evento('click', () => {
        finalizarTarefa(botaoFinalizar, botaoConcluido, blocoTeste, item.id);
    });

    botaoLike.evento('click', () => {
        adicionarLike(item.id);
    });
    botaoDeslike.evento('click', () => {
        cancelarTarefa(item.id);
    });

    if (editarDeletar == 'sim') {
        clone.querySelector('.botao_editar').addEventListener('click', () => {
            abrirPopupTarefaEditar(item.id);
        });
        clone.querySelector('.botao_deletar').addEventListener('click', () => {
            tarefaDeletar(item.id);
        });
    }
    ajudaLoading(clone);
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
        const clone = cloneTarefaLista.clonar();
        clone.attr({
            id: 'id_demanda_' + item.id,
            'data-id': item.id,
            'data-status': item.status,
        });

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
            false,
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
    const blocoColuna = coluna.classe('.bloco_coluna', '?') ? coluna : coluna.closest('.bloco_coluna');
    const numero = blocoColuna.querySelectorAll('.conteudo .bloco_tarefa_item').length;
    const blocoNumero = blocoColuna.querySelector('header h1 span');
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

const mudarDemandaColuna = (atual, destino, demanda) => {
    const destinoZero = $('.tarefa_zero', destino);
    if (destinoZero) {
        destinoZero.displayHide();
    }
    destino.inicio(demanda);
    contarTarefaDemanda(destino);
    contarTarefaDemanda(atual);
    if ($$('.bloco_tarefa_item', atual).length == 0) {
        $('.tarefa_zero', atual).displayShow();
    }

    const id = demanda.attr('data-id');
    const status = destino.closest('.bloco_coluna').attr('data-status');
    ajaxPost(
        LINK + '/demanda/demanda-status',
        {
            id,
            status,
        },
        ''
    );
};
