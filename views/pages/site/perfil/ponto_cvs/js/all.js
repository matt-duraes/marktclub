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
const botaoExtrato = document.querySelector('#extrato_cvs');

const blocoRealizarResgate = () => {
    const nome = document.querySelector('input#input_nome');
    const email = document.querySelector('input#input_email');
    const quantidade = document.querySelector('input#input_quantidade');
    const enviarResgate = document.querySelector('#enviar_solicitacao');

    enviarResgate.addEventListener('click', () => {
        fazerSolicitacao();
    });
    const fazerSolicitacao = async () => {
        const resposta = await ajaxPost(
            LINK + '/ponto-cvs/solicitar',
            {
                nome: nome.value,
                email: email.value,
                ponto: quantidade.value,
            },
            'Não foi possível fazer a solicitação'
        );

        if (false === resposta) {
            return;
        }
        Alerta.notificacao('Solicitação enviada com sucesso.', true);
    };
};

const PaginaSolicitarCvs = new Pagina(
    'Solicitar',
    LINK + '/popup/solicita-ponto-cvs',
    undefined,
    true,
    true,
    blocoRealizarResgate
);

botaoSolicitarResgate.addEventListener('click', () => {
    PaginaSolicitarCvs.abrir();
});

const blocoVerExtrato = () => {
    console.log('testando');
};

const PaginaAbrirExtrato = new Pagina(
    'Extrato',
    LINK + '/popup/extrato-ponto-cvs',
    undefined,
    true,
    true,
    blocoVerExtrato
);

botaoExtrato.addEventListener('click', () => {
    PaginaAbrirExtrato.abrir();
});
