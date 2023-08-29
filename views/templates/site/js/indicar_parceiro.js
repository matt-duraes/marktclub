window.addEventListener('load', () => {
    const botaoAbrirIndiqueParceiro = document.getElementById('abreIndiqueParceiro');
    if (!botaoAbrirIndiqueParceiro) {
        return;
    }
    const carregarFuncaoIndicarParceiro = () => {
        const formulario = document.getElementById('formulario_indica_parceiro');

        const botaoEnviarIndicacao = document.querySelector('#botao_enviar_indicacao');
        botaoEnviarIndicacao.addEventListener('click', async e => {
            e.preventDefault();
            const parceiro = formulario.querySelector('input[name=parceiro]');
            const telefone = formulario.querySelector('input[name=telefone]');
            const email = formulario.querySelector('input[name=email]');
            const mensagem = formulario.querySelector('textarea[name=mensagem]');

            validarDadosDoForm(parceiro, telefone, email, mensagem);

            const body = new FormData();
            body.append('parceiro', parceiro.value);
            body.append('telefone', telefone.value);
            body.append('email', email.value);
            body.append('mensagem', mensagem);

            Loading.show();

            const resposta = await fetch('/indicacao/salvar', {
                method: 'POST',
                body,
            });

            let json;
            try {
                json = await resposta.json();
            } catch (error) {
                json = {};
            }

            Loading.hide();
            if (resposta.status === 201) {
                Alerta.notificacao(
                    `Você indica a empresa que gostaria de ter desconto exclusivo, e nós negociaremos o melhor para você!
                    <br> Esse processo completo leva em torno de 90 dias.
                    <br> Para garantirmos exclusividade e segurança, fazemos pesquisa sobre o histórico no Reclame Aqui,
                    aprovação das condições de desconto e análise jurídica, mas não se preocupe que avisaremos por e-mail a finalização do processo.`,
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

    const validarDadosDoForm = (parceiro, telefone, email) => {
        if (parceiro === null) {
            Alerta.notificacao('Digite o nome do parceiro que deseja indicar', false);
            return;
        }
        if (telefone === null) {
            Alerta.notificacao('Digite o telefone do parceiro que deseja indicar', false);
            return;
        }
        if (email === null) {
            Alerta.notificacao('Digite o email do parceiro que deseja indicar', false);
            return;
        }
    };

    const paginaIndiqueParceiro = new Pagina(
        'Indicar um Parceiro',
        LINK + '/indique-um-parceiro',
        {},
        true,
        true,
        carregarFuncaoIndicarParceiro
    );

    botaoAbrirIndiqueParceiro.addEventListener('click', () => {
        paginaIndiqueParceiro.abrir();
    });
});
