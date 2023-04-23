// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Galeria"
// @system "Calendario"
// @system "Mascara"

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
    if(botaoAtributo === 'contratar'){
        document.querySelector('.bloco_solicitar_federal').classList.add('inativo');
        const contrata = document.querySelector('.container_contratacao');
        contrata.classList.toggle('inativo');
    } else if(botaoAtributo === 'solicitar'){
        const solicita = document.querySelector('.bloco_solicitar_federal');
        document.querySelector('.container_contratacao').classList.add('inativo');
        solicita.classList.toggle('inativo');
    }
    
}
