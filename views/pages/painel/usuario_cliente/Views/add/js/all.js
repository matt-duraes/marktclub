// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const selectEmpresa = document.querySelector('#input_empresa');
    const selectSubempresa = document.querySelector('#input_subempresa');
    const selectGrupo = document.querySelector('#input_grupo');
    const grupoInicial = selectGrupo ? selectGrupo.value : '';
    const empresaInicial = selectEmpresa ? selectEmpresa.value : '';

    if (selectEmpresa) {
        selectEmpresa.addEventListener('formChange', () => {
            if (selectGrupo) {
                buscarGrupoEmpresa(selectEmpresa.value);
            }
            if (selectSubempresa) {
                buscarSubempresa(selectEmpresa.value);
            }
        });
    }

    const buscarGrupoEmpresa = async (empresa, valor) => {
        const body = new FormData();
        body.append('empresa', empresa);
        body.append('titulo', 'Escolha um grupo');
        body.append('indice', 'grupo');

        formSelectLoading(selectGrupo);

        const resposta = await fetch(LINK + '/app/ajax/usuario-cliente', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Ocorreu um erro para listar os grupos do usuário.');
        if (false === json) {
            return;
        }

        formSelectOption(selectGrupo, montarRetornoVazio(json.dado, 'Não existe subempresa cadastrada'), valor);
    };

    const buscarSubempresa = async empresa => {
        if (!selectSubempresa) {
            return;
        }

        const body = new FormData();
        body.append('empresa', empresa);
        body.append('titulo', 'Escolha uma subempresa');
        body.append('indice', 'subempresa');

        formSelectLoading(selectSubempresa);

        const resposta = await fetch(LINK + '/app/ajax/usuario-cliente', {
            method: 'POST',
            body,
        });
        const json = await respostaJson(
            resposta,
            'Ocorre um erro ao buscar lista de subempresa, por favor, tente novamente.'
        );
        if (false === json) {
            return;
        }

        formSelectOption(selectSubempresa, montarRetornoVazio(json.dado, 'Não existe subempresa cadastrada'), '');
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

    if (selectEmpresa && selectGrupo && empresaInicial != '') {
        buscarGrupoEmpresa(empresaInicial, grupoInicial);
    } else if (selectEmpresa && selectGrupo && grupoInicial == '') {
        formSelectOption(selectGrupo, { '': 'Escolha uma empresa' });
    } else if (selectGrupo) {
        buscarGrupoEmpresa('', grupoInicial);
    }
    if (!selectEmpresa && selectSubempresa) {
        buscarSubempresa('');
    }
});
