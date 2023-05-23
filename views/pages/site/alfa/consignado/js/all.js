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

        setTimeout(function () {
            Alerta.mensagem('Envio do formulário com sucesso!');
        }, 800);
    });
});
