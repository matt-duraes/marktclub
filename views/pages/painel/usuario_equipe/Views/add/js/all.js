// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const inputEmpresa = $('#input_empresa');
    const inputSubempresa = $('#input_subempresa');

    const buscarSubempresa = async (empresa, valor) => {
        formSelectLoading(inputSubempresa);
        const resposta = await ajaxPost(
            LINK + '/app/ajax/usuario-equipe',
            {
                empresa,
                titulo: 'Escolha uma subempresa',
                indice: 'subempresa',
            },
            'Erro ao listar subempresa'
        );
        if (false === resposta) {
            return;
        }
        formSelectOption(inputSubempresa, montarRetornoVazio(resposta.dado, 'Não existe subempresa cadastrada'), valor);
    };

    if (inputEmpresa && inputSubempresa) {
        inputEmpresa.evento('formChange', () => {
            buscarSubempresa(inputEmpresa.value);
        });
        buscarSubempresa(inputEmpresa.value, inputSubempresa.value);
    }

    const montarRetornoVazio = (lista, mensagem) => {
        let quantidade = 0;
        const retorno = {};
        for (const [indice, valor] of Object.entries(lista)) {
            retorno[indice] = valor;
            quantidade++;
        }

        if (quantidade <= 1) {
            return { '': mensagem };
        }
        return retorno;
    };
});
