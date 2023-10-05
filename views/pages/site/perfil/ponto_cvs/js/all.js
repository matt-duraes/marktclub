// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @system "Form"
// @system "Alerta"
// @system "Loading"
const botaoSolicitarResgate = document.querySelector('#solicitar_resgate');

const carregarPopup = () => {
    const botaoFechar = $('#botao_faq_favorito_fechar');
    botaoFechar.addEventListener('click', () => {
        PaginaSolicitarCvs.fechar();
    });
};

const PaginaSolicitarCvs = new Pagina('Solicitar', LINK + '/popup/solicita-ponto-cvs', {}, true, true, carregarPopup);

botaoSolicitarResgate.addEventListener('click', () => {
    PaginaSolicitarCvs.abrir();
});
