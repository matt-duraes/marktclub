// @template "site"
// @system "Pagina"
// @resource "site/passo_passo"
// @resource "site/scrollBotao"

window.onload = function () {
    const botao = document.querySelectorAll('.bloco_botao_plano button');
    botao.forEach(botao => {
        botao.addEventListener('click', event => {
            const clicado = event.target;
            abrirModal(clicado);
        });
    });
    const botaoScroll = document.querySelector('.botao_scroll');
    animaScroll(botaoScroll);
    window.addEventListener('scroll', function () {
        animaScroll(botaoScroll);
    });
};

function abrirModal(clicado) {
    const id = clicado.getAttribute('data-id');
    const local = clicado.getAttribute('data-local');

    const loadingConfirmarLoja = () => {
        const botao = $('#bloco_loja_confirmar a');
        const botaoFechar = $('.botao_fechar_popup');
        botaoFechar.addEventListener('click', () => {
            PaginaConfirmar.fechar();
        });
        botao.addEventListener('click', () => {
            PaginaConfirmar.fechar();
        });
    };
    const PaginaConfirmar = new Pagina(
        'Plano de Saúde-' + id,
        LINK + `/saude/abrir-tabela-preco?id=${id}&local=${local}`,
        undefined,
        true,
        true,
        loadingConfirmarLoja
    );
    PaginaConfirmar.abrir();
}
