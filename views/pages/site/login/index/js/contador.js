const blocoContador = $('#bloco_contador');
const blocoContadorNumero = $('#bloco_contador_numero');
const contadorLoja = $('#contador_loja');
const contadorParceiro = $('#contador_parceiro');
const numeroLoja = parseInt(contadorLoja.attr('data-numero'));
const numeroParceiro = parseInt(contadorParceiro.attr('data-numero'));

const montarContadorHome = (loja, parceiro) => {
    document.addEventListener('scroll', () => {
        animarContador();
    });

    const animarContador = () => {
        const alturaJanela = window.innerHeight;
        const distanciaBlocoTopo = blocoContador.getBoundingClientRect().top;
        const distanciaNumeroTopo = blocoContadorNumero.getBoundingClientRect().top;

        if (distanciaBlocoTopo < alturaJanela && window.matchMedia('(min-width: 400px)').matches) {
            let margin = (alturaJanela - distanciaBlocoTopo) / 3;
            margin = margin > 200 ? 200 : margin;
            blocoContador.style.marginTop = '-' + margin + 'px';
        }
        if (
            distanciaNumeroTopo + 200 < alturaJanela &&
            !blocoContadorNumero.classList.contains('bloco_contador_ativo')
        ) {
            fazerNumerosDoContadorCorrer(contadorLoja, loja);
            fazerNumerosDoContadorCorrer(contadorParceiro, parceiro);
        }
    };

    const fazerNumerosDoContadorCorrer = (bloco, numero) => {
        blocoContadorNumero.classList.add('bloco_contador_ativo');
        let contador = 0;
        const atualizarContador = () => {
            const target = +numero;
            const c = contador;

            const increment = target / 200;

            if (c < target) {
                contador = c + increment;
                const stringContador = Math.ceil(c + increment).toString();
                bloco.innerText = `${stringContador.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1.')}`;
                setTimeout(atualizarContador, 1);
            } else {
                bloco.innerText = target.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1.');
            }
        };
        atualizarContador();
    };

    animarContador();
};

if (numeroLoja > 0 && numeroParceiro > 0) {
    montarContadorHome(numeroLoja, numeroParceiro);
}
