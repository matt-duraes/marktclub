// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const listaCheckbox = document.querySelectorAll('.input_checkbox');
    const inputGrupoLabel = document.querySelector('#input_grupo_label');
    const inputGrupoPlaceholder = document.querySelector('#input_grupo_placeholder');

    listaCheckbox.forEach((checkbox => {
        if (checkbox.children[0].value == 'grupo') {
            if (!checkbox.children[0].checked) {
                inputGrupoLabel.parentNode.classList.add('display_none');
                inputGrupoPlaceholder.parentNode.classList.add('display_none');
            }

            checkbox.addEventListener('click', () => {
                if (checkbox.children[0].checked) {
                    inputGrupoLabel.parentNode.classList.remove('display_none');
                    inputGrupoPlaceholder.parentNode.classList.remove('display_none');
                    return;
                }

                inputGrupoLabel.parentNode.classList.add('display_none');
                inputGrupoPlaceholder.parentNode.classList.add('display_none');
            });
        }
    }));
})
