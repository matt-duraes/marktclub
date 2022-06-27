const fecharBlocoDeOrdem = (botao, bloco) => {
    botao.setAttribute('data-ajuda', 'Ordernar Resultados');
    bloco.classList.remove('ordem_abrir');
    setTimeout(() => {
        bloco.style.display = 'none';
        botao.classList.remove('animacao');
    }, 500);
};

window.addEventListener('load', () => {
    const botaoOrdem = document.querySelector('#botao_ordem_abrir');
    if (botaoOrdem) {
        const blocoOrdem = document.querySelector('#bloco_ordem_template');
        const blocoOrdemSeta = blocoOrdem.querySelector('.seta');
        const botaoFechar = document.querySelectorAll('.botao_ordem_fechar');

        botaoOrdem.addEventListener('click', () => {
            Ajuda.hide();
            botaoOrdem.setAttribute('data-ajuda', '');
            blocoOrdemAbrir();
        });
        [].forEach.call(botaoFechar, botao => {
            botao.addEventListener('click', () => {
                fecharBlocoDeOrdem(botaoOrdem, blocoOrdem);
            });
        });

        const blocoOrdemAbrir = () => {
            botaoOrdem.classList.add('animacao');
            blocoOrdem.style.display = 'block';
            setTimeout(() => {
                blocoOrdem.classList.add('ordem_abrir');
            }, 40);
            posicionarSeta();
        };

        window.addEventListener('resize', () => {
            posicionarSeta();
        });
        const posicionarSeta = () => {
            const botaoPosicao = botaoOrdem.getBoundingClientRect();
            const blocoPosicao = blocoOrdem.getBoundingClientRect();
            blocoOrdemSeta.style.left = botaoPosicao.left + 'px';
            const blocoLeft = ((botaoPosicao.left - (blocoPosicao.width / 2)) + (botaoPosicao.width / 2));
            blocoOrdem.style.left = blocoLeft + 'px';
        };
    }

});