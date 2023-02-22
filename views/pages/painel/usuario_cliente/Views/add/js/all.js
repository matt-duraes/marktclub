// @template "painel"

window.addEventListener('load', () => {
    const selectEmpresa = document.querySelector('#input_empresa');
    const selectGrupo = document.querySelector('#input_grupo');

    formSelectChange = change => {
        if (change == 'buscarGrupoEmpresa' && selectGrupo) {
            buscarGrupoEmpresa(selectEmpresa.value);
        }
    };

    const buscarGrupoEmpresa = async empresa => {
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

        formSelectOption(selectGrupo, json.dado);
    };

    if (selectEmpresa && selectGrupo) {
        formSelectOption(selectGrupo, { '': 'Escolha uma empresa' });
    } else if (selectGrupo) {
        buscarGrupoEmpresa();
    }
});
