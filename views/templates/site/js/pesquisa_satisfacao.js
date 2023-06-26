window.addEventListener('load', () => {
    const carregarFuncaoPesquisaSatisfacao = () => {
        const formulario = document.getElementById('formulario_pesquisa');

        const botaoEnviar = document.querySelector('#botao_envia_pesquisa');
        botaoEnviar.addEventListener('click', async e => {
            e.preventDefault();
            const navegar = formulario.querySelector('input[name=navegar]:checked');
            const procura = formulario.querySelector('input[name=procura]:checked');
            const suporte = formulario.querySelector('input[name=suporte]:checked');
            const comentario = formulario.querySelector('textarea[name=comentario]');
            const atendimento = formulario.querySelector('input[name=atendimento]:checked');

            const sistema = [];
            const sistemaInputs = document.querySelectorAll("input[name='sistema']:checked");
            sistemaInputs.forEach(function (input) {
                sistema.push(input.value);
            });

            validateAndSubmitForm(navegar, procura, suporte, atendimento, sistema);

            const body = new FormData();
            body.append('navegar', navegar.value);
            body.append('procura', procura.value);
            body.append('suporte', suporte.value);
            body.append('comentario', comentario);
            body.append('atendimento', atendimento.value);
            body.append('sistema', sistema.value);

            const resposta = await fetch('/pesquisa-de-satisfacao', {
                method: 'POST',
                body,
            });

            let json;
            try {
                json = await resposta.json();
            } catch (error) {
                json = {};
            }

            if (resposta.status === 201) {
                Alerta.notificacao(
                    'Obrigado pelo seu feedback. Sua resposta será analizada para melhorias do seu clube.',
                    true
                );
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
                return;
            }

            Alerta.notificacao(
                json.erro.mensagem !== undefined
                    ? json.erro.mensagem
                    : 'Ocorreu um erro ao simular, por favor, tente novamente.',
                false
            );
        });

        const botaoFechar = document.querySelectorAll('.botao_fechar_popup');
        botaoFechar.forEach(fecha => {
            fecha.addEventListener('click', () => {
                Pagina.staticFechar();
            });
        });
    };

    const validateAndSubmitForm = (navegar, procura, suporte, atendimento, sistema) => {
        if (navegar === null) {
            Alerta.notificacao('Selecione se o clube é fácil de navegar ou não', false);
            return;
        }
        if (procura === null) {
            Alerta.notificacao('Selecione se você geralmente acha o que procura', false);
            return;
        }
        if (suporte === null) {
            Alerta.notificacao('Selecione como foi sua experiência com o suporte', false);
            return;
        }
        if (atendimento === null) {
            Alerta.notificacao('Avalie a qualidade do atendimento', false);
            return;
        }
        if (sistema.length === 0) {
            Alerta.notificacao('Marque os sistemas que você conhece', false);
            return;
        }
    };

    const botaoAbrirPesquisaSatisfacao = document.getElementById('abrePesquisaSatisfacao');
    const paginaPesquisaSatisfacao = new Pagina(
        'Pesquisa de satisfação',
        document.querySelector('#LINK').value + '/pesquisa-de-satisfacao',
        {},
        true,
        true,
        carregarFuncaoPesquisaSatisfacao
    );

    botaoAbrirPesquisaSatisfacao.addEventListener('click', () => {
        paginaPesquisaSatisfacao.abrir();
    });
});
