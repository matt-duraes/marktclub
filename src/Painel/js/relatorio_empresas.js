const blocoCheckboxEmpresa = document.querySelector('.bloco_checkbox');
const botaoEmpresa = document.querySelector('.botao_empresa');
const empresasBusca = document.querySelector('.empresas_busca');
const blocoPopup = document.querySelector('#bloco_empresas');
const inputMarcarTodos = document.querySelector("#input_marcar_todos");

window.addEventListener('load', () => {
    if(!blocoCheckboxEmpresa) return;

    const PopupAtualizar = new Popup('atualizar-dado', 'bloco_empresas', true, true);

    inputMarcarTodos.addEventListener('change', (e) => {
        const checkboxes = blocoCheckboxEmpresa.querySelectorAll('input[type=checkbox]');
        for (let i = 0; i < checkboxes.length; i++) {
            checkboxes[i].checked = e.target.checked;
            adicionarEmpresaNaBusca(checkboxes[i]);
        }
    });

    botaoEmpresa.addEventListener('click', () => {
        PopupAtualizar.abrir();
    });

    blocoCheckboxEmpresa.addEventListener('change', (e) => {
        inputMarcarTodos.checked = false;
        adicionarEmpresaNaBusca(e.target);
    });

    const adicionarEmpresaNaBusca = (checkbox) => {
        const div = checkbox.parentNode;
        const label = div.querySelector('label');

        if(checkbox.checked) {
            const divEmpresa = document.createElement('div');
            divEmpresa.title = label.innerText;
            divEmpresa.innerText = label.innerText;

            empresasBusca.appendChild(divEmpresa);
            return;
        }

        const empresas = empresasBusca.children;
        for (let i = 0; i < empresas.length; i++) {
            if (empresas[i].innerText === label.innerText) {
                empresasBusca.removeChild(empresas[i]);
                break;
            }
        }
    };
});

/*
|--------------------------------------------------------------------------
| PEGAR VALORES QUE FORAM MARCADOS NO CHECKBOX
|---------------------------------------------- ----------------------------
*/

const pegarValoresMarcados = () => {
    if(!blocoCheckboxEmpresa) return '';

    var valoresMarcados = [];
    var checkboxes = blocoCheckboxEmpresa.getElementsByTagName('input');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type === 'checkbox' && checkboxes[i].checked) {
            valoresMarcados.push(checkboxes[i].value);
        }
    }
    return valoresMarcados;
};
