window.addEventListener('load', () => {
    const blocoConfirmar = $('#bloco_loja_confirmar');
    if (!blocoConfirmar) {
        return;
    }

    const botaoConfirmar = $$('.botao_confirmar_abrir');
    let podeAbrirDireto = false;

    const PopupConfirmar = new Popup('confirmar-parceiro', 'bloco_loja_confirmar', true, false);
    botaoConfirmar.evento('click', e => {
        if (podeAbrirDireto) {
            return;
        }
        e.preventDefault();
        PopupConfirmar.abrir();
        podeAbrirDireto = true;
    });

    setTimeout(() => {
        podeAbrirDireto = true;
    }, 30000);
});
