const blocoCheckboxEmpresa = document.querySelector('.bloco_checkbox');
const botaoEmpresa = document.querySelector('.botao_empresa');

window.addEventListener('load', () => {
    const PopupAtualizar = new Popup('atualizar-dado', 'bloco_empresas', true, true);

    botaoEmpresa.addEventListener('click', () => {
        PopupAtualizar.abrir();
    });
});

/*
|--------------------------------------------------------------------------
| PEGAR VALORES QUE FORAM MARCADOS NO CHECKBOX
|--------------------------------------------------------------------------
*/

const pegarValoresMarcados = () => {
    var valoresMarcados = [];
    var checkboxes = blocoCheckboxEmpresa.getElementsByTagName('input');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type === 'checkbox' && checkboxes[i].checked) {
            valoresMarcados.push(checkboxes[i].value);
        }
    }
    return valoresMarcados;
};
