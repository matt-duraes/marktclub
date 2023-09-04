const salvarDeclaracao = async (parceiro, modelo, versao) => {
    Loading.show();
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
    Alerta.mensagem(
        'Solicitação enviada',
        'A declaração foi solicitada com sucesso e será encaminhada para seu e-mail após assinatura do documento, em até 8h úteis.',
        true
    );
};
