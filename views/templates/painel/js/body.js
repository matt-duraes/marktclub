window.addEventListener('load', () => {
    const blocoOrdem = document.querySelector('#bloco_ordem_template');
    const botaoOrdem = document.querySelector('#botao_ordem_abrir');

    const blocoNotificacao = document.querySelector('#bloco_notificacao');
    const botaoNotificacao = document.querySelector('#botao_notificacao_abrir');

    document.querySelector('body').addEventListener('click', e => {
        if (blocoOrdem) {
            ordemFechar(e);
        }
        if (blocoNotificacao) {
            notificacaoFechar(e);
        }
    });

    const ordemFechar = e => {
        const display = window.getComputedStyle(blocoOrdem).getPropertyValue('display');
        const fecharOrdem =
            blocoOrdem &&
            display == 'block' &&
            e.target.getAttribute('id') != 'bloco_ordem_template' &&
            !e.target.closest('#bloco_ordem_template') &&
            e.target.getAttribute('id') != 'botao_ordem_abrir' &&
            !e.target.closest('#botao_ordem_abrir');
        if (fecharOrdem) {
            fecharBlocoDeOrdem(botaoOrdem, blocoOrdem);
        }
    };

    const notificacaoFechar = e => {
        const target = e.target;
        if (
            !blocoNotificacao.classList.contains('display_none') &&
            target != botaoNotificacao &&
            target != blocoNotificacao &&
            !target.closest('#bloco_notificacao') &&
            !target.closest('#botao_notificacao_abrir')
        ) {
            blocoNotificacao.classList.add('display_none');
        }
    };
});
