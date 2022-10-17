// @template "painel"
// @import "../../detalhe/js/config"
// @import "../../detalhe/js/arquivo"
// @import "../../detalhe/js/scroll"
// @import "../../detalhe/js/mensagem"
// @import "../../detalhe/js/seguindo"
// @import "../../detalhe/js/geral"

window.addEventListener('load', () => {
    const botaoAdd = document.getElementById('botao_add');
    const blocoAcao = document.querySelectorAll('#bloco_demanda_index .bloco_coluna');
    const blocoTarefa = document.querySelectorAll('#bloco_demanda_index article');

    blocoAcao.forEach(bloco => {
        const botao = bloco.querySelector('.botao_encolher');
        botao.addEventListener('click', () => {
            if (bloco.classList.contains('curto')) {
                bloco.classList.remove('curto');
                botao.innerText = '+';
                return;
            }
            bloco.classList.add('curto');
            botao.innerText = '-';
        });
    });

    const paginaAdd = new Pagina('Adicionar tarefa', LINK + '/demanda/salvar');
    botaoAdd.addEventListener('click', () => {
        paginaAdd.abrir();
    });

    if (blocoTarefa.length > 0) {
        blocoTarefa.forEach(item => {
            const titulo = item.querySelector('.tarefa_titulo').innerText;
            const url = item.getAttribute('data-url');
            const PaginaDetalhe = new Pagina(
                'Tarefa ' + titulo,
                LINK + '/demanda/' + url,
                undefined,
                true,
                true,
                loadingDetalhe
            );
            item.addEventListener('click', () => {
                abrirDetalheDaTarefa(PaginaDetalhe);
            });
        });
    }

    const abrirDetalheDaTarefa = Pagina => {
        Pagina.abrir();
    };
});
