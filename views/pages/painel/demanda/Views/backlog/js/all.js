// @template "painel"

window.addEventListener('load', () => {
    const botaoAbrir = $$('#bloco_app_lista_detalhe .linha .botao_acao');
    botaoAbrir.evento('click', (e, item) => {
        const linha = item.closest('.lista');
        if (!linha) {
            return;
        }
        linha.classe('aberto');
    });
});
