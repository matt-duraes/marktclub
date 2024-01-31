// @system "Icone"

const blocoCheckboxEmpresa = document.querySelector('.bloco_checkbox');
const botaoFiltro = document.querySelector('.botao_empresa');
const inputMarcarTodos = document.querySelector("#input_marcar_todos");
const headerResto = document.querySelector('#header_template .bloco_app .resto');
const botaoBuscar = document.getElementById('botao_buscar_relatorio');

const formParceiro = document.querySelector('#form_parceiro');
const inputParceiro = document.querySelector('#input_parceiro');
const inputParceiroTexto = document.querySelector('#input_parceiro_texto');
const listaParceiro = document.querySelector('#lista_parceiro');

window.addEventListener('load', () => {
    if(!blocoCheckboxEmpresa) return;

    const botaoFiltro = adicionarBotaoFiltro(headerResto);
    const PopupFiltro = new Popup('filtro-popup', 'popup_filtros', true, true);

    inputMarcarTodos.addEventListener('change', lidarMarcarTodos);

    botaoFiltro.addEventListener('click', () => {
        PopupFiltro.abrir();
    });

    blocoCheckboxEmpresa.addEventListener('change', (e) => {
        inputMarcarTodos.checked = false;
    });

    botaoBuscar.addEventListener('click', () => {
        PopupFiltro.fechar();
    });

    if (inputParceiro) {
        inputParceiro.addEventListener('formChange', (e) => {
            adicionarTagItem(e.target.value, inputParceiroTexto.value);
        })
    }
});

/*
|--------------------------------------------------------------------------
| MARCA E DESMARCA TODOS OS CHECKBOX
|---------------------------------------------- ----------------------------
*/

const lidarMarcarTodos = (e) => {
    const checkboxes = blocoCheckboxEmpresa.querySelectorAll('input[type=checkbox]');

    checkboxes.forEach((checkbox) => {
        checkbox.checked = e.target.checked;
        const div = checkbox.parentNode;
        const label = div.querySelector('label');

        if (e.target.checked) {
            div.classList.add('checked');
            label.classList.add('checked');
        } else {
            div.classList.remove('checked');
            label.classList.remove('checked');
        }
    });
}

/*
|--------------------------------------------------------------------------
| CRIAR SELECT DO PARCEIRO
|---------------------------------------------- ----------------------------
*/
const adicionarTagItem = (id, value) => {
    if (listaParceiro.querySelector(`[data-id="${id}"]`)) {
        return;
    }

    if (!id) {
        return;
    }

    const item = document.createElement('div');
    item.classList.add('tag_item');
    item.dataset.id = id;
    item.innerHTML = `
        <span>${value}</span>
        ${Icone.fechar(8)}
    `;

    item.addEventListener('click', () => {
        listaParceiro.removeChild(item);
    });

    listaParceiro.appendChild(item);
}

/*
|--------------------------------------------------------------------------
| ADICIONAR E RETORNAR BOTAO DE FILTRO
|---------------------------------------------- ----------------------------
*/

function adicionarBotaoFiltro(bloco) {
    const botao = document.createElement('button');
    botao.classList.add('botao_empresa');
    botao.innerHTML = `
        <div class="icon">
            <i class="fas fa-filter"></i>
        </div>
        <div class="texto">${Icone.filtrar(20)}</div>
    `;
    return bloco.insertAdjacentElement('afterend', botao);
}

/*
|--------------------------------------------------------------------------
| PEGAR VALORES QUE FORAM MARCADOS
|---------------------------------------------- ----------------------------
*/

const pegarValoresMarcadosParceiro = () => {
    if(!listaParceiro) return '';

    let valoresMarcados = [];
    let tags = listaParceiro.querySelectorAll('.tag_item');
    for (var i = 0; i < tags.length; i++) {
        valoresMarcados.push(tags[i].dataset.id);
    }

    return valoresMarcados;
}

const pegarValoresMarcadosEmpresa = () => {
    if(!blocoCheckboxEmpresa) return '';

    let valoresMarcados = [];
    let checkboxes = blocoCheckboxEmpresa.getElementsByTagName('input');
    for (var i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].type === 'checkbox' && checkboxes[i].checked) {
            valoresMarcados.push(checkboxes[i].value);
        }
    }
    return valoresMarcados;
};


