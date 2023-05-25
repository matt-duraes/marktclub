// @template "site"

window.onload = function () {
    const tipoIngresso = document.getElementsByName('ingresso');
    if (tipoIngresso) {
        tipoIngresso.forEach(function (tipoIngressoCinema) {
            tipoIngressoCinema.addEventListener('change', function () {
                let valor = this.value;
                let blocoPreco = document.querySelector('.bloco_ingresso .ingresso_container .preco');

                let quantidade = blocoPreco.querySelector('.quantidade');
                let valorIngresso = blocoPreco.querySelector('.valor b');

                if (valor === '2_ingresso') {
                    quantidade.innerText = '2 Ingressos';
                    valorIngresso.innerText = '39,00';
                } else if (valor === '1_ingresso') {
                    quantidade.innerText = '1 Ingresso';
                    valorIngresso.innerText = '19,50';
                }
            });
        });
    }
};
