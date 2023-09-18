// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const selectEmpresa = document.querySelector('#input_empresa');
    const selectSubempresa = document.querySelector('#input_subempresa');
    if (selectEmpresa) {
        selectEmpresa.addEventListener('formChange', () => {
            if (selectSubempresa) {
                buscarSubempresa(selectEmpresa.value);
            }
        });
    }

    const buscarSubempresa = async empresa => {
        formSelectLoading(selectSubempresa);
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

        formSelectOption(selectSubempresa, montarRetornoVazio(resposta.dado, 'Não existe subempresa cadastrada'), '');
    };

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
