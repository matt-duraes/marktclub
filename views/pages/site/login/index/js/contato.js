// @system "Pagina"
window.addEventListener('load', () => {
    const carregarFuncaoContato = () => {
        const formulario = document.getElementById('formulario_contato');

        const botaoEnviarContato = document.querySelector('#botao_enviar_contato');
        botaoEnviarContato.addEventListener('click', async e => {
            e.preventDefault();
            const nome = formulario.querySelector('input[name=nome]');
            const telefone = formulario.querySelector('input[name=telefone]');
            const email = formulario.querySelector('input[name=email]');
            const mensagem = formulario.querySelector('textarea[name=mensagem]');
            const hash = formulario.querySelector('input[name=hash]');
            const validacao = formulario.querySelector('input[name=validacao]');

            const body = new FormData();
            body.append('nome', nome.value);
            body.append('telefone', telefone.value);
            body.append('email', email.value);
            body.append('mensagem', mensagem);
            body.append('hash', hash);
            body.append('validacao', validacao);

            Loading.show();

            const resposta = await fetch('/contato', {
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
                Alerta.notificacao(`Em breve entraremos em contato.`, true);
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
                return;
            }

            Alerta.notificacao(
                json.erro.mensagem !== undefined
                    ? json.erro.mensagem
                    : 'Ocorreu um erro ao enviar, por favor, tente novamente.',
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

    const paginaContato = new Pagina(
        'Entre em Contato',
        document.querySelector('#LINK').value + '/contato',
        {},
        true,
        true,
        carregarFuncaoContato
    );
    const botaoPopupContato = document.querySelector('.abrirModalContato');

    if (botaoPopupContato) {
        botaoPopupContato.addEventListener('click', () => {
            paginaContato.abrir();
        });
    }
});
