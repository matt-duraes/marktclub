// @template "painel"

window.addEventListener('load', () => {
    const botaoSalvar = $('#botao_salvar_apple');
    botaoSalvar.addEventListener('click', async () => {
        Loading.show();
        const resposta = await ajaxPost(LINK + '/usuario-apple');
        Loading.hide();
        if (false === resposta) {
            return;
        }
    });
});
