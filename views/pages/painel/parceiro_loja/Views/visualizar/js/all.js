// @template "painel"
// @painel "app_geral_visualizar"
// @import "endereco"
// @import "contato"

window.addEventListener('load', () => {
    const lista = $$('.bloco_fieldset_abrir_fechar');
    if (lista.length == 0) {
        return;
    }
    for (const item of lista) {
        const botao = $('.botao_abrir_fieldset', item);
        if (!botao) {
            continue;
        }
        botao.evento('click', () => {
            item.classe('bloco_fieldset_fechado');
        });
    }
});
