// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Form"
// @system "Galeria"
// @system "Calendario"
// @system "Mascara"

window.onload = function () {
    const enviarSolicitacao = document.querySelector('#enviarSolicitacao');
    enviarSolicitacao.addEventListener('click', e => {
        e.preventDefault();
        const resposta = await ajaxPost(
            LINK + '/ponto-cvs/solicitar',
            {
                ''
            }
            'Ocorreu um erro ao salvar solicitação, por favor, tente novamente.'
        );
    });
};
