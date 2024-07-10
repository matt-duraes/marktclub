// @template "painel"

window.addEventListener('load', () => {
    const id = $('#input_visualizar_id').valor();
    const botaoIniciar = $('#botao_sprint_iniciar');
    const botaoConcluir = $('#botao_sprint_concluir');
    const botaoCancelar = $('#botao_sprint_cancelar');
    const blocoTitulo = $('#bloco_mudar_status_titulo');
    const blocoTexto = $('#bloco_mudar_status_texto');
    const inputStatus = $('#input_mudar_status_status');
    const inputTexto = $('#input_mudar_status_texto');
    const botaoMudarStatus = $('#botao_mudar_status');

    const PopupStatus = new Popup('Mudar Status', 'bloco_popup_status', false, false);
    if (botaoIniciar) {
        botaoIniciar.evento('click', () => {
            gerarAberturaPopup(
                'Iniciar sprint',
                'Deseja iniciar a sprint? Descreva com detalhes qual a história dessa sprint.',
                'andamento'
            );
        });
    }
    if (botaoConcluir) {
        botaoConcluir.evento('click', () => {
            gerarAberturaPopup(
                'Concluir sprint',
                'Deseja concluir a demanda? Caso tenha alguma demanda não finalizada, ela será colocada novamente no backlog. Descreva com detalhes a história da conclusão dessa sprint',
                'concluida-prazo'
            );
        });
    }
    if (botaoCancelar) {
        botaoCancelar.evento('click', async () => {
            gerarAberturaPopup(
                'Cancelar sprint',
                'Deseja cancelar a demanda? Todas as demandas não finalizadas serão colocadas novamente no backlog. Descreva com detalhes o porque do cancelamento dessa sprint.',
                'cancelada'
            );
        });
    }
    const gerarAberturaPopup = (titulo, texto, status) => {
        blocoTitulo.texto(titulo);
        blocoTexto.texto(texto);
        inputTexto.valor('');
        inputStatus.valor(status);
        PopupStatus.abrir();
    };

    botaoMudarStatus.evento('click', async () => {
        const texto = inputTexto.valor();
        if (vazio(texto)) {
            Alerta.notificacao('Preencha a descrição para continuar.', false);
            return;
        }
        const status = inputStatus.valor();
        const body = {
            id,
            status,
        };
        if (status == 'andamento') {
            // eslint-disable-next-line camelcase
            body.texto_inicio = texto;
            body.indice = 'status_inicial';
        } else {
            // eslint-disable-next-line camelcase
            body.texto_final = texto;
            body.indice = 'status_andamento';
        }
        Loading.show();
        const resposta = await ajaxPost(LINK + '/app/ajax/demanda-sprint', body);
        if (false === resposta) {
            Loading.hide();
            return;
        }
        PopupStatus.fechar();
        window.location.reload();
    });
});
