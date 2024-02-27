// @template "painel"
// @system "DragDrop"
window.addEventListener('load', () => {
    manipularResposta();
});
const manipularResposta = () => {
    const botaoAdicionarResposta = $('#botao_adicionar_resposta');
    const blocoRespostaPadrao = $('#bloco_linha_resposta');
    const blocoRespostaLoading = $('#bloco_resposta_zero');
    const blocoRespostaZero = $('#bloco_resposta_zero');
    const blocoResposta = $('#bloco_lista_resposta');
    new DragDrop().bloco(blocoResposta).item('.linha').botao('i.drag').iniciar();

    botaoAdicionarResposta.evento('click', () => {
        if (botaoAdicionarResposta.classe('loading', '?')) {
            return;
        }
        const clone = blocoRespostaPadrao.clonar();
        const titulo = $('[name="resposta_titulo"]', clone);
        blocoResposta.inicio(clone);
        titulo.focus();
        blocoRespostaZero.sumir();
    });
    blocoResposta.evento('click', e => {
        const target = e.target;
        if (!target.classe('deletar', '?') && !target.closest('.deletar')) {
            return;
        }
        removerResposta(target.closest('.linha'));
    });
    const removerResposta = linha => {
        if (!linha) {
            return;
        }
        linha.remover();
        if ($$('.linha', blocoResposta).length > 0) {
            return;
        }
        blocoRespostaZero.aparecer();
    };
};
