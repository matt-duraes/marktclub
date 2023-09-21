// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const inputEmpresa = $('#input_empresa');
    const inputSubempresa = $('#input_subempresa');
    const inputGrupo = $('#input_grupo');

    const buscarGrupo = async (empresa, valor) => {
        formSelectLoading(inputGrupo);
        const resposta = await ajaxPost(
            LINK + '/app/ajax/usuario-cliente',
            {
                empresa,
                titulo: 'Escolha um grupo',
                indice: 'grupo',
            },
            'Ocorreu um erro para listar os grupos do usuário.'
        );

        if (false === resposta) {
            return;
        }

        formSelectOption(inputGrupo, montarRetornoVazio(resposta.dado, 'Não existe grupo cadastrado'), valor);
    };

    const buscarSubempresa = async (empresa, valor) => {
        formSelectLoading(inputSubempresa);
        const resposta = await ajaxPost(
            LINK + '/app/ajax/usuario-cliente',
            {
                empresa,
                titulo: 'Escolha uma subempresa',
                indice: 'subempresa',
            },
            ''
        );
        if (false === resposta) {
            return;
        }
        formSelectOption(inputSubempresa, montarRetornoVazio(resposta.dado, 'Não existe subempresa cadastrada'), valor);
    };

    if (inputEmpresa) {
        inputEmpresa.addEventListener('formChange', () => {
            if (inputGrupo) {
                buscarGrupo(inputEmpresa.value);
            }
            if (inputSubempresa) {
                buscarSubempresa(inputEmpresa.value);
            }
        });
        if (inputGrupo && inputGrupo.value != '') {
            buscarGrupo(inputEmpresa.value, inputGrupo.value);
        }
        if (inputSubempresa && inputSubempresa.value != '') {
            buscarSubempresa(inputEmpresa.value, inputSubempresa.value);
        }
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
