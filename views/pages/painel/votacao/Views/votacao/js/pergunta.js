window.addEventListener('load', () => {
    const votacao = $('#input_visualizar_id').valor();
    const botaoSalvarPergunta = $('#botao_pergunta_salvar');

    const blocoPerguntaPadrao = $('#bloco_linha_pergunta');

    const blocoPerguntaLista = $('#bloco_pergunta_lista');
    const inputPerguntaTitulo = $('input[name="pergunta_titulo"]');
    const inputPerguntaTexto = $('textarea[name="pergunta_texto"]');
    const inputPerguntaTipo = $('input[name="pergunta_tipo"]');
    const inputPerguntaNulo = $('input[name="pergunta_nulo"]');

    const buscarPergunta = async () => {
        const resposta = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                votacao,
                acao: 'pergunta-listar',
                pagina: 1,
                quantidade: 40,
            },
            'Ocorreu um erro ao listar tarefas, recarregue a página e tente novamente.'
        );
        if (false === resposta) {
            return;
        }
    };
    buscarPergunta();

    // const PopupSalvar = new Popup('Adicionar pergunta', 'bloco_pergunta_add');

    const resetarPergunta = () => {
        inputPerguntaTitulo.valor('');
        inputPerguntaTexto.valor('');
        inputPerguntaTipo.valor('');
        inputPerguntaNulo.valor(false);
    };

    botaoSalvarPergunta.evento('click', () => {
        if (listaResposta().length == 0) {
            Alerta.notificacao('Você precisa salvar pelo menos uma resposta.', false);
            return;
        }
        salvarNovaPergunta();
    });
    const salvarNovaPergunta = async () => {
        if (!(await validarPergunta())) {
            return;
        }
        const resposta = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                votacao,
                acao: 'pergunta-salvar',
                pergunta,
                titulo: inputPerguntaTitulo.valor(),
                texto: inputPerguntaTexto.valor(),
                tipo: inputPerguntaTipo.valor(),
                nulo: inputPerguntaNulo.checked ? 'sim' : 'nao',
            },
            'Erro ao salvar pergunta, por favor, tente novamente.'
        );
        if (false === resposta) {
            return;
        }
    };

    const validarPergunta = () => {
        if (inputPerguntaTitulo.valor() == '') {
            Alerta.notificacao('Digite um título para a pergunta.', false);
            return false;
        } else if (inputPerguntaTipo.valor() == '') {
            Alerta.notificacao('Escolha um tipo para a pertunta.', false);
            return false;
        }
        return true;
    };
});
