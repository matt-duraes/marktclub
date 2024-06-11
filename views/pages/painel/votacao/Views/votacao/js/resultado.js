window.addEventListener('load', () => {
    const botao = $('#botao_resultado_votacao');
    if (!botao) {
        return;
    }

    let blocoTipo = 'resultado';

    const id = $('#input_visualizar_id').valor();
    const menu = $$('#bloco_resultado_menu .item');

    const cloneTrSubtitulo = $('#tr_subtitulo');
    const cloneTrResposta = $('#tr_resposta');
    const cloneTrVoto = $('#tr_voto');
    const cloneTrUsuario = $('#tr_usuario');

    const blocoTodos = $$('#bloco_resultado_resultado, #bloco_resultado_voto, #bloco_resultado_usuario');

    const blocoResultadoConteudo = $('#bloco_resultado_resultado .conteudo');
    const blocoVotoConteudo = $('#bloco_resultado_voto .conteudo');
    const blocoUsuarioConteudo = $('#bloco_resultado_usuario .conteudo');

    const botaoCopiarTodos = $$('#botao_copiar_resultado, #botao_copiar_voto, #botao_copiar_usuario');

    let textoResultado = `Pergunta/Resposta\tQuantidade\n`;
    let textoVoto = '';
    let textoUsuario = 'Nome\tCpf\n';

    let jaBuscou = false;

    /*
    |--------------------------------------------------------------------------
    | MONTAR HTML DA RESPOSTA
    |--------------------------------------------------------------------------
    */
    const PopupResultado = new Popup('Resultado', 'bloco_resultado', true, false);

    botao.evento('click', () => {
        if (jaBuscou) {
            PopupResultado.abrir();
            return;
        }
        buscarResultado();
    });

    const buscarResultado = async () => {
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/votacao',
            {
                indice: 'votacao-resultado',
                id,
            },
            'Ocorreu um erro ao buscar resultado.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        jaBuscou = true;
        PopupResultado.abrir();
        adicionarTrResultado(resposta.dado.resultado);
        adicionarTrVoto(resposta.dado.voto);
        adicionarTrUsuario(resposta.dado.usuario);
    };

    const adicionarTrResultado = lista => {
        for (const pergunta of lista) {
            const titulo = cloneTrSubtitulo.clonar();
            blocoResultadoConteudo.final(titulo.texto(pergunta.pergunta));
            textoResultado += pergunta.pergunta + `\n`;
            for (const resposta of pergunta.resposta) {
                const tr = cloneTrResposta.clonar();
                $('.resultado_titulo', tr).texto(resposta.resposta);
                $('.resultado_quantidade', tr).texto(resposta.voto);
                blocoResultadoConteudo.final(tr);
                textoResultado += `${resposta.resposta}\t${resposta.voto}\n`;
            }
        }
    };
    const adicionarTrVoto = lista => {
        let cabecalhoComUsuario = `Nome\tCPF\tPergunta\tResposta\tData\n`;
        let cabecalhoSemUsuario = `Pergunta\tResposta\tData\n`;
        let temUsuario = false;
        for (const item of lista) {
            const tr = cloneTrVoto.clonar();
            if (!vazio(item.nome)) {
                temUsuario = true;
                const nome = $('.voto_nome', tr);
                nome.texto(item.nome);
                nome.aparecer();
                textoVoto += `${item.nome}\t`;
            }
            if (!vazio(item.cpf)) {
                const cpf = $('.voto_cpf', tr);
                cpf.texto(item.cpf);
                cpf.aparecer();
                textoVoto += `${item.cpf}\t`;
            }
            $('.voto_pergunta', tr).texto(item.pergunta);
            $('.voto_resposta', tr).texto(item.resposta);
            $('.voto_data', tr).texto(item.data);

            textoVoto += `${item.pergunta}\t${item.resposta}\t${item.data}\n`;
            blocoVotoConteudo.final(tr);
        }
        if (temUsuario) {
            textoVoto = cabecalhoComUsuario + textoVoto;
        } else {
            textoVoto = cabecalhoSemUsuario + textoVoto;
        }
    };
    const adicionarTrUsuario = lista => {
        for (const item of lista) {
            const tr = cloneTrUsuario.clonar();
            $('.usuario_nome', tr).texto(item.nome);
            $('.usuario_cpf', tr).texto(item.cpf);
            blocoUsuarioConteudo.final(tr);

            textoUsuario += `${item.nome}\t${item.cpf}\n`;
        }
    };

    /*
    |--------------------------------------------------------------------------
    | MENU
    |--------------------------------------------------------------------------
    */
    menu.evento('click', (e, item) => {
        blocoTipo = item.attr('data-tipo');
        const bloco = $('#bloco_resultado_' + blocoTipo);
        const copiar = $('#botao_copiar_' + blocoTipo);

        blocoTodos.sumir();
        bloco.aparecer();

        menu.classe('hover', false);
        item.classe('hover', true);

        botaoCopiarTodos.sumir();
        copiar.aparecer();
    });

    /*
    |--------------------------------------------------------------------------
    | COPIAR RESULTADO
    |--------------------------------------------------------------------------
    */
    botaoCopiarTodos.evento('click', () => {
        if (blocoTipo == 'resultado') {
            navigator.clipboard.writeText(textoResultado);
        } else if (blocoTipo == 'voto') {
            navigator.clipboard.writeText(textoVoto);
        } else if (blocoTipo == 'usuario') {
            navigator.clipboard.writeText(textoUsuario);
        }
        Alerta.notificacao('Dados copiados com sucesso!', true);
    });
});
