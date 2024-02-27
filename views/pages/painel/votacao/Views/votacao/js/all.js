// @template "painel"
// @system "DragDrop"
// @system "Popup"

window.addEventListener('load', () => {
    const botaoSalvarPergunta = $('#botao_pergunta_salvar');
    const botaoAdicionarResposta = $('#botao_adicionar_resposta');
    const blocoRespostaPadrao = $('#bloco_linha_resposta');
    const blocoRespostaLoading = $('#bloco_resposta_zero');
    const blocoRespostaZero = $('#bloco_resposta_zero');
    const blocoRespostaLista = $('#bloco_lista_resposta');

    const inputRespostaTitulo = $('input[name="pergunta_titulo"]');
    const inputRespostaTexto = $('textarea[name="pergunta_texto"]');
    const inputRespostaTipo = $('input[name="pergunta_tipo"]');
    const inputRespostaNulo = $('input[name="pergunta_nulo"]');

    const botaoPergunta = $('#botao_adicionar_pergunta');
    const PopupSalvar = new Popup('Adicionar pergunta', 'bloco_pergunta_add');

    new DragDrop().bloco(blocoRespostaLista).item('.linha').botao('i.drag').iniciar();

    botaoAdicionarResposta.evento('click', () => {
        if (botaoAdicionarResposta.classe('loading', '?')) {
            return;
        }
        const clone = blocoRespostaPadrao.clonar();
        const titulo = $('[name="resposta_titulo"]', clone);
        blocoRespostaLista.inicio(clone);
        titulo.focus();
        blocoRespostaZero.sumir();
    });
    blocoRespostaLista.evento('click', e => {
        const target = e.target;
        if (target.classe('deletar', '?') || target.closest('.deletar')) {
            removerResposta(target.closest('.linha'));
            return;
        } else if (target.classe('editar', '?') || target.closest('.editar')) {
            const bloco = target.closest('.linha');
            editarPergunta(bloco.attr('data-id'));
        }
    });
    const removerResposta = linha => {
        if (!linha) {
            return;
        }
        linha.remover();
        if ($$('.linha', blocoRespostaLista).length > 0) {
            return;
        }
        blocoRespostaZero.aparecer();
    };

    const editarPergunta = async id => {
        botaoAdicionarResposta.classe('loading', true);
        blocoRespostaZero.sumir();
        blocoRespostaLoading.aparecer();
        const resposta = await ajaxPost(LINK + '/app/ajax/votacao', {
            pergunta: id,
            acao: 'pergunta',
        });
    };

    const resetarPergunta = () => {
        blocoRespostaLista.html('');
        inputRespostaTitulo.valor('');
        inputRespostaTexto.valor('');
        inputRespostaTipo.valor('');
        inputRespostaNulo.valor(false);
    };

    botaoPergunta.evento('click', () => {
        botaoAdicionarResposta.classe('loading', false);
        blocoRespostaZero.sumir();
        blocoRespostaLoading.aparecer();
        resetarPergunta();
        PopupSalvar.abrir();
    });
});
