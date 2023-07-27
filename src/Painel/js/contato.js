const contatoLoad = () => {
    const relacionado = $('#input_contato_geral_id').value;
    const app = $('#input_contato_geral_app').value;
    const inputHistorico = $('#input_contato_geral_historico');
    const botaoSalvar = $('#botao_contato_geral_salvar');

    const PaginaContatoFechar = new Pagina();
    botaoSalvar.addEventListener('click', async () => {
        const mensagem = inputHistorico.value;
        if (mensagem == '') {
            Alerta.notificacao('Digite um histórico para salvar.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost('/historico', {
            relacionado,
            mensagem,
            app,
        });
        Loading.hide();
        if (false === resposta) {
            return;
        }
        PaginaContatoFechar.fechar();
    });
};
