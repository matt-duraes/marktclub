const containerScroll = document.querySelectorAll('.container_scroll');

function animaScroll(botaoScroll) {
    const metadeJanela = window.innerHeight * 0.9;

    containerScroll.forEach(section => {
        const sectionTopo = section.getBoundingClientRect().top;

        const janelaevisivel = sectionTopo - metadeJanela < 0;

        if (janelaevisivel) {
            section.classList.add('ativar');
            botaoScroll.classList.add('animar-botao');
        } else if (sectionTopo - metadeJanela > 0) {
            section.classList.remove('ativar');
            botaoScroll.classList.remove('animar-botao');
        }
    });
}
