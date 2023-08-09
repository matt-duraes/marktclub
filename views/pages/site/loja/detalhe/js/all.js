// @template "site"
// @resource "site/busca"
// @resource "site/loja/favorito"

window.addEventListener('load', () => {
    const url = $('#input_loja_url').value;
    const botaoConfirmar = $$('.botao_confirmar_abrir');

    let podeAbrirDireto = false;
    if (botaoConfirmar.length > 0) {
        botaoConfirmar.forEach(botao => {
            botao.addEventListener('click', e => {
                if (podeAbrirDireto) {
                    return;
                }
                e.preventDefault();
                abrirBoxConfirmacao();
            });
        });
    }

    const loadingConfirmarLoja = () => {
        const botao = $('#bloco_loja_confirmar a');
        botao.addEventListener('click', () => {
            PaginaConfirmar.fechar();
        });
    };
    const PaginaConfirmar = new Pagina(
        'loja-confirmar-' + url,
        LINK + '/convenios/confirmar/' + url,
        undefined,
        true,
        true,
        loadingConfirmarLoja
    );
    const abrirBoxConfirmacao = () => {
        // podeAbrirDireto = true;
        PaginaConfirmar.abrir();
    };

    // setTimeout(() => {
    //     podeAbrirDireto = true;
    // }, 30000);
});
