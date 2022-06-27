window.addEventListener('load', () => {
    const blocoOrdem = document.querySelector('#bloco_ordem_template');
    const botaoOrdem = document.querySelector('#botao_ordem_abrir');

    document.querySelector('body').addEventListener('click', e => {
        if (blocoOrdem) {
            ordemFechar(e);
        }
    });

    const ordemFechar = e => {
        const display = window.getComputedStyle(blocoOrdem).getPropertyValue('display');
        const fecharOrdem = blocoOrdem && display == 'block' &&
            (
                e.target.getAttribute('id') != 'bloco_ordem_template' &&
                !e.target.closest('#bloco_ordem_template') &&
                e.target.getAttribute('id') != 'botao_ordem_abrir' &&
                !e.target.closest('#botao_ordem_abrir')
            );
        if (fecharOrdem) {
            fecharBlocoDeOrdem(botaoOrdem, blocoOrdem);
        }
    };
});