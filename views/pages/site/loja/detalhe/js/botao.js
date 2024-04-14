window.addEventListener('load', () => {
    const blocoBotao = $('#bloco_botao_lista');
    if (!blocoBotao) {
        return;
    }
    blocoBotao.aparecer();
    const botao = $$('#bloco_botao_lista .borda');
    const linha = $('#bloco_botao_lista .linha');
    if (botao.length == 0) {
        linha.sumir();
        return;
    }
    botao.classe('borda_' + botao.length);
});
