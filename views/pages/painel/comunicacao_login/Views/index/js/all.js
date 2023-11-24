// @template "painel"
// @painel "app_lista_index"

const linhas = document.querySelectorAll('.form_geral');
const linhaPadrao = linhas[2];

const checkbox = linhaPadrao.querySelector('.checkbox');
checkbox.parentNode.removeChild(checkbox);
