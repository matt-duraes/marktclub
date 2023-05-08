// @template "site"

window.onload = function () {
    const opcao = document.querySelectorAll('.bloco_opcao button');
    opcao.forEach(botao => {
        botao.addEventListener('click', function (event) {
            const botao = event.currentTarget;
            clicado(botao);
        });
    });
};

function clicado(botao) {
    const botaoAtributo = botao.getAttribute('data-opcao');
    if (botaoAtributo === 'contratar') {
        document.querySelector('.boleto').classList.add('display_none');
        const contrata = document.querySelector('.contratacao');
        contrata.classList.toggle('display_none');
    } else if (botaoAtributo === 'solicitar') {
        const solicita = document.querySelector('.boleto');
        document.querySelector('.contratacao').classList.add('display_none');
        solicita.classList.toggle('display_none');
    }
}
