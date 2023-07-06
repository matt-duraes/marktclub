const containerScroll = document.querySelector('.container_scroll');
const botaoScroll = document.querySelector('.botao_scroll');
const animaScroll = () => {
    const metadeJanela = window.innerHeight * 0.9;
    const sectionTopo = containerScroll.getBoundingClientRect().top;
    const janelaevisivel = sectionTopo - metadeJanela < 0;
    if (janelaevisivel && !botaoScroll.classList.contains('fechar')) {
        botaoScroll.classList.add('fechar');
    } else if (!janelaevisivel && botaoScroll.classList.contains('fechar')) {
        botaoScroll.classList.remove('fechar');
    }
};

animaScroll();
window.addEventListener('scroll', function () {
    animaScroll();
});
