// @template "site"
// @system "Alerta"
// @system "Pagina"
// @system "Form"
// @system "Mascara"
// @resource "site/tab"

window.onload = function () {
    const carregarFuncoesCashback = () => {
        const botaoFechar = document.querySelector('.botao_fechar_popup');

        botaoFechar.addEventListener('click', () => {
            Pagina.staticFechar();
        });
    };

    const PaginaBuscaCashback = new Pagina(
        'Busca',
        LINK + '/cashback/resgatar',
        {},
        true,
        true,
        carregarFuncoesCashback
    );

    const botaoPopup = document.querySelector('.botao_resgata');
    botaoPopup.addEventListener('click', () => {
        PaginaBuscaCashback.abrir();
    });
};
