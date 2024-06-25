// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const inputUsuario = $('#input_saque_texto');

    const buscarUsuario = async (saque) => {
        formSelectLoading(inputUsuario);
        const resposta = await ajaxPost(
            LINK + '/app/ajax/silium-deposito',
            {
                saque,
                indice: 'saques'
            },
            'Erro ao listar solicitações'
        );
        if (false === resposta) {
            return;
        }
        formSelectOption(inputUsuario, montarRetornoVazio(resposta.dado, 'Não existe solicitações de saque'));
    };

    if (inputUsuario) {
        inputUsuario.addEventListener('change', () => {
            buscarUsuario(inputUsuario.value);
        });
    }

    const montarRetornoVazio = (lista, mensagem) => {
        let quantidade = 0;
        const retorno = {};
        for (const [indice, valor] of Object.entries(lista)) {
            retorno[indice] = valor;
            quantidade++;
        }

        if (quantidade < 1) {
            return { '': mensagem };
        }
        return retorno;
    };
});
