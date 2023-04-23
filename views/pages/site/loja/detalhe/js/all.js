// @template "site"

window.addEventListener('load', () => {
    const url = document.getElementById('input_loja_url').value;
    const botaoDeclaracao = document.getElementById('botao_abrir_declaracao');
    const botaoVoucher = document.getElementById('botao_abrir_voucher');

    let podeAbrirDireto = false;

    botaoDeclaracao.addEventListener('click', e => {
        abrirBoxConfirmacao(e);
    });
    botaoVoucher.addEventListener('click', e => {
        abrirBoxConfirmacao(e);
    });

    const PaginaConfirmar = new Pagina('loja-confirmar-' + url, LINK + '/convenios/confirmar/' + url);
    const abrirBoxConfirmacao = e => {
        if (podeAbrirDireto) {
            return;
        }
        e.preventDefault();
        podeAbrirDireto = true;
        PaginaConfirmar.abrir();
    };

    setTimeout(() => {
        podeAbrirDireto = true;
    }, 20000);
});
