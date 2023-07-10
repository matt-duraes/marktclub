window.addEventListener('load', () => {
    const botaoLista = document.querySelectorAll('#bloco_app_visualizar .botao_status');
    if (botaoLista.length == 0) {
        return;
    }

    const botaoVerLista = document.querySelectorAll('.array_item_botao .botao_ver_lista');
    if (botaoVerLista.length > 0) {
        botaoVerLista.forEach(botao => {
            botao.addEventListener('click', () => {
                const bloco = botao.closest('.array_item');
                const linha = bloco.querySelector('.array_item_botao');
                const lista = bloco.querySelector('.lista_item');

                linha.classList.toggle('bg_hover');
                lista.classList.toggle('display_none');
                botao.classList.toggle('fechar');
            });
        });
    }

    botaoLista.forEach(botao => {
        const status = botao.getAttribute('data-status') || '';
        const mensagem = botao.getAttribute('data-mensagem') || '';
        if (status == '' || mensagem == '') {
            return;
        }
        botao.addEventListener('click', async () => {
            const resposta = await Alerta.confirmar('Confirmar mudança', mensagem, false);
            if (resposta) {
                mudarStatus(status);
            }
        });
    });

    const blocoVisualizar = document.querySelector('#bloco_app_visualizar');
    const blocoMain = document.querySelector('#main_template');
    const blocoBotaoLista = document.querySelector('.bloco_status_lista');
    if (blocoBotaoLista) {
        blocoMain.addEventListener('scroll', e => {
            validarScrollPositionBotao();
        });
    }
    const validarScrollPositionBotao = () => {
        const posicao = blocoVisualizar.getBoundingClientRect();
        const scrollTop = blocoMain.scrollTop;
        const alturaTela = window.innerHeight;

        if (posicao.height - alturaTela <= 0) {
            return;
        }

        const botaoScrollAltura = posicao.height - alturaTela + 60;
        if (scrollTop > botaoScrollAltura && !blocoBotaoLista.classList.contains('relative')) {
            blocoBotaoLista.classList.add('relative');
        } else if (scrollTop <= botaoScrollAltura && blocoBotaoLista.classList.contains('relative')) {
            blocoBotaoLista.classList.remove('relative');
        }
    };

    const idVisualizar = document.querySelector('#input_visualizar_id').value;
    const appVisualizar = document.querySelector('#input_visualizar_app').value;
    const mudarStatus = async status => {
        Loading.show();

        const body = new FormData();
        body.append('id', idVisualizar);
        body.append('status', status);
        body.append('app', appVisualizar);
        const resposta = await fetch(LINK + '/app/status', {
            body,
            method: 'POST',
        });

        Loading.hide();

        if (resposta.status == 204) {
            const resposta = await Alerta.mensagem('Status alterado', 'O status foi alterado com sucesso!', true);
            if (resposta) {
                window.location.reload();
            }
            return;
        }
        Alerta.notificacao('Ocorreu um erro ao alterar seu status.', false);
    };
});
