// @template "painel"

window.addEventListener('load', () => {
    const botaoSalvar = $('#botao_salvar_apple');
    botaoSalvar.addEventListener('click', async () => {
        if (
            !(await Alerta.confirmar(
                'Criar usuários',
                'Tem certeza que deseja criar os usuários? Essa ação não poderá ser desfeita.',
                '!'
            ))
        ) {
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(LINK + '/usuario-apple');
        Loading.hide();
        if (false === resposta) {
            return;
        }
        Alerta.notificacao('Todos os usuários foram criados com sucesso.', true);
    });
});
