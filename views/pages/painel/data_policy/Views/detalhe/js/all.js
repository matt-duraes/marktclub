// @template "painel"

window.addEventListener('load', () => {
    const blocoTramitacao = document.querySelector('#bloco_tramitacao');
    const blocoTexto = document.querySelector('#bloco_texto');

    const mostrarBoxCompleto = (bloco, comparacao) => {
        const botaoMostrarMais = document.getElementById('botao_mostrar_mais');
        const blocoDegrade = document.getElementById('bloco_degrade');

        const blocoHeight = bloco.getBoundingClientRect().height;
        const topo = comparacao.offsetTop + comparacao.getBoundingClientRect().height;

        if (topo > blocoHeight) {
            botaoMostrarMais.classList.remove('hide');
            blocoDegrade.classList.remove('hide');
        }

        botaoMostrarMais.addEventListener('click', () => {
            bloco.classList.add('abrir');
            botaoMostrarMais.classList.add('hide');
            blocoDegrade.classList.add('hide');
        });
    };
    if (blocoTramitacao) {
        const tramitacaoLista = blocoTramitacao.querySelectorAll('article');
        const ultimaTramitacao = tramitacaoLista[tramitacaoLista.length - 1];
        mostrarBoxCompleto(blocoTramitacao, ultimaTramitacao);
    } else if (blocoTexto) {
        const blocoTextoReal = blocoTexto.querySelector('.texto');
        mostrarBoxCompleto(blocoTexto, blocoTextoReal);
    }
});
