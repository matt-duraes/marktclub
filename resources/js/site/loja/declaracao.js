
const salvarDeclaracao = async (parceiro, modelo, versao) => {
    Loading.show();
    const textoPrazoDeclaracao = document.querySelector('#texto_prazo');
    let tempoDeclaracao = 8;
    const resposta = await ajaxPost(
        LINK + '/convenios/declaracao',
        {
            parceiro,
            modelo,
            versao,
        },
        'Erro ao solicitar a declaração, por favor, tente novamente.'
    );
    Loading.hide();
    if (false === resposta) {
        return;
    }

    if(textoPrazoDeclaracao != null) {
        tempoDeclaracao = textoPrazoDeclaracao.value;
    }

    Alerta.mensagem(
        'Solicitação enviada',
        `A declaração foi solicitada com sucesso e será encaminhada para seu e-mail após assinatura do documento, em até ${tempoDeclaracao} úteis.`,
        true
    );


};
