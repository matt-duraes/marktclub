window.addEventListener('load', () => {
    const botaoAbrirPesquisaSatisfacao = document.getElementById('abrePesquisaSatisfacao');
    if (!botaoAbrirPesquisaSatisfacao) {
        return;
    }
    const carregarFuncaoPesquisaSatisfacao = () => {
        const formulario = document.getElementById('formulario_pesquisa');
        const botaoEnviar = document.querySelector('#botao_envia_pesquisa');

        const PaginaFechar = new Pagina();

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

            if(!validarCampos(navegar, procura, suporte, atendimento, sistema)) {
                return;
            };

            Loading.show();
            const resposta = await ajaxPost(
                LINK + '/pesquisa-de-satisfacao',
                {
                    navegar: navegar.value,
                    procura: procura.value,
                    suporte: suporte.value,
                    comentario: comentario.value,
                    atendimento: atendimento.value,
                    sistema: sistema,
                },
                'Não foi possível enviar a pesquisa, tente novamente mais tarde'
            );

            Loading.hide();
            if (false === resposta) {
                return;
            }

            Alerta.mensagem(
                'Pesquisa enviada',
                'Obrigado pelo seu feedback. Sua resposta será analizada para melhorias do seu clube.',
                true
            );
            PaginaFechar.fechar();
        });
    };

    const validarCampos = (navegar, procura, suporte, atendimento, sistema) => {
        const campos = [
            { valor: navegar, mensagem: 'Selecione se o clube é fácil de navegar ou não' },
            { valor: procura, mensagem: 'Selecione se você geralmente acha o que procura' },
            { valor: suporte, mensagem: 'Selecione como foi sua experiência com o suporte' },
            { valor: atendimento, mensagem: 'Avalie a qualidade do atendimento' },
        ];
        for (const campo of campos) {
            if (campo.valor === null) {
                Alerta.notificacao(campo.mensagem, false);
                return false;
            }
            if (sistema.length === 0) {
                Alerta.notificacao('Marque os sistemas que você conhece', false);
                return false;
            }
        }

        return true;
    };

    const paginaPesquisaSatisfacao = new Pagina(
        'Pesquisa de satisfação',
        LINK + '/pesquisa-de-satisfacao',
        {},
        true,
        true,
        carregarFuncaoPesquisaSatisfacao
    );

    botaoAbrirPesquisaSatisfacao.addEventListener('click', () => {
        paginaPesquisaSatisfacao.abrir();
    });
});
