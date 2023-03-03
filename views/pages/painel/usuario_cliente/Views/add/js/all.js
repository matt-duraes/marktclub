// @template "painel"
// @resource "painel/app_geral_add"

window.addEventListener('load', () => {
    const selectEmpresa = document.querySelector('#input_empresa');
    const selectGrupo = document.querySelector('#input_grupo');
    const grupoInicial = selectGrupo ? selectGrupo.value : '';
    const empresaInicial = selectEmpresa ? selectEmpresa.value : '';

    formSelectChange = change => {
        if (change == 'buscarGrupoEmpresa' && selectGrupo) {
            buscarGrupoEmpresa(selectEmpresa.value);
        }
    };

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

        formSelectOption(selectGrupo, json.dado, valor);
    };

    if (selectEmpresa && selectGrupo && empresaInicial != '') {
        buscarGrupoEmpresa(empresaInicial, grupoInicial);
    } else if (selectEmpresa && selectGrupo && grupoInicial == '') {
        formSelectOption(selectGrupo, { '': 'Escolha uma empresa' });
    } else if (selectGrupo) {
        buscarGrupoEmpresa('', grupoInicial);
    }
});
