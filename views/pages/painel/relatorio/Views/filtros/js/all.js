const botaoFiltrar = document.querySelector('.botao_filtrar');
const blocoCheckboxEmpresa = document.querySelector('.bloco_checkbox');

/*
|--------------------------------------------------------------------------
| APARECER E DESAPARECER OS FILTROS
|--------------------------------------------------------------------------
*/

botaoFiltrar.addEventListener('click', () => {
    if (blocoCheckboxEmpresa.style.display === 'none') {
        blocoCheckboxEmpresa.style.display = '';
    } else {
        blocoCheckboxEmpresa.style.display = 'none';
    }
});
document.addEventListener('click', (e) => {
    const sectionCheckbox = blocoCheckboxEmpresa.querySelector('section');
    if(e.target !== botaoFiltrar && !sectionCheckbox.contains(e.target)) {
        blocoCheckboxEmpresa.style.display = 'none';
    }
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
