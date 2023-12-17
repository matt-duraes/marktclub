// @template "painel"
// @painel "app_geral_add"

const inputPadrao = document.querySelector('#input_padrao');

if (inputPadrao.value == 1) {
    const blocoCheckBox = document.querySelector('.bloco_checkbox_geral');
    blocoCheckBox.parentNode.classList.add('display_none');

    const inputDataInicio = document.querySelector('#input_data_inicio');
    inputDataInicio.parentNode.classList.add('display_none');

    const inputDataFim = document.querySelector('#input_data_fim');
    inputDataFim.parentNode.classList.add('display_none');
}

inputPadrao.remove();
