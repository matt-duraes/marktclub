// @template "painel"

window.addEventListener('load', () => {
    const idSprint = $('#input_visualizar_id').valor();
    const botaoIniciar = $('#botao_sprint_iniciar');
    const botaoConcluir = $('#botao_sprint_concluir');
    const botaoCancelar = $('#botao_sprint_cancelar');
    const blocoTitulo = $('#bloco_mudar_status_titulo');
    const blocoTexto = $('#bloco_mudar_status_texto');
    const inputStatus = $('#input_mudar_status_status');
    const inputTexto = $('#input_mudar_status_texto');
    const botaoMudarStatus = $('#botao_mudar_status');
    const blocoDemandaRemoverPadrao = $('#bloco_demanda_padrao');
    const blocoDemandaRemoverLista = $('#bloco_demanda_lista');

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
        botaoConcluir.evento('click', async () => {
            Loading.show();
            const resposta = await ajaxPost(
                LINK + '/app/ajax/demanda-sprint',
                {
                    indice: 'demanda_aberta',
                },
                'Erro ao pegar a lista de demandas abertas.'
            );
            Loading.hide();
            if (false === resposta) {
                return;
            }
            if ('dado' in resposta && resposta.dado.length > 0) {
                blocoDemandaRemoverLista.aparecer();
                for (const item of resposta.dado) {
                    const clone = blocoDemandaRemoverPadrao.clonar();
                    $('.input_id', clone).valor(item.id);
                    $('h3', clone).texto(item.titulo);
                    blocoDemandaRemoverLista.final(clone);
                }
            }
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
        const status = inputStatus.valor();
        if (vazio(texto)) {
            Alerta.notificacao('Preencha a descrição para continuar.', false);
            return;
        } else if (status == 'concluida-prazo' && !(await salvarMotivoNaoEntrega())) {
            return;
        }

        const body = {
            id: idSprint,
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

    const salvarMotivoNaoEntrega = async () => {
        return new Promise(async resolve => {
            const demanda = $$('.bloco', blocoDemandaRemoverLista);
            for (const item of demanda) {
                const id = $('.input_id', item).valor();
                const texto = $('.bloco_motivo_texto textarea', item).valor();
                if (vazio(texto)) {
                    Alerta.notificacao('Preencha o motivo da demanda não ter sido entregue para continuar.', false);
                    resolve(false);
                    return;
                }
                const resposta = await ajaxPost(
                    LINK + '/demanda-sprint/demanda-remover',
                    {
                        demanda: id,
                        sprint: idSprint,
                        texto,
                    },
                    'Erro ao remover demanda, por favor, tente novamente.'
                );
                if (false === resposta) {
                    resolve(false);
                    return;
                }
                item.remove();
            }
            blocoDemandaRemoverLista.sumir();
            resolve(true);
        });
    };
});
