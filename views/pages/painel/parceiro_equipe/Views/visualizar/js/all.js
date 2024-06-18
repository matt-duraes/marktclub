// @template "painel"
// @system "Popup"
// @painel "app_geral_visualizar"

window.addEventListener('load', () => {
    const botaoAbrir = $('#botao_adicionar_captador');
    const botaoSalvar = $('#botao_salvar_equipe');
    const parceiro = $('#input_visualizar_id').valor();
    const inputEquipe = $('#input_captador_equipe');

    const PopupEquipe = new Popup('Equipe', 'bloco_popup_equipe', true, false);
    botaoAbrir.evento('click', () => {
        PopupEquipe.abrir();
    });

    botaoSalvar.evento('click', async () => {
        const equipe = inputEquipe.valor();
        if (vazio(equipe)) {
            Alerta.notificacao('Escolha um captador para o parceiro.', false);
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/parceiro-equipe',
            {
                id: parceiro,
                equipe,
                indice: 'equipe',
            },
            'Ocorreu um erro ao adicionar captador.'
        );
        if (false === resposta) {
            Loading.hide();
            return;
        }
        window.location.replace(LINK + '/app/parceiro-equipe');
    });
});
