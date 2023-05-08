// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/passo_passo"

window.addEventListener('load', () => {
    const btnSubmit = document.querySelector('#enviar_solicitacao');
    btnSubmit.addEventListener('click', function (e) {
        event.preventDefault();

        bullets[3].classList.add('ativo');
        progressChecks[3].classList.add('ativo');
        progressTexts[3].classList.add('ativo');

        setTimeout(function () {
            Alerta.mensagem('Envio do formulário com sucesso!');
        }, 800);
    });
});
