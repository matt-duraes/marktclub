const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);
const ppe = console.log.bind(console);

window.addEventListener('load', () => {
    const listaTodos = $$('nav .todos input');
    if (listaTodos.length == 0) {
        return;
    }
    listaTodos.forEach(input => {
        input.addEventListener('change', () => {
            marcarDesmarcarTodos(input.closest('li').querySelectorAll('ul input'), input.checked);
        });
    });
    const marcarDesmarcarTodos = (lista, valor) => {
        lista.forEach(input => {
            input.checked = valor;
        });
    };

    const listaItem = $$('nav ul li ul input');
    listaItem.forEach(input => {
        input.addEventListener('change', () => {
            const blocoTodos = input.closest('.diretorio').querySelector('.todos input');
            if (!input.checked) {
                blocoTodos.checked = false;
                return;
            }
            const bloco = input.closest('ul');
            const total = bloco.querySelectorAll('input').length;
            const ativo = bloco.querySelectorAll('input:checked').length;
            if (total == ativo) {
                blocoTodos.checked = true;
            }
        });
    });
});
