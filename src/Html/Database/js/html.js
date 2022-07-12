window.addEventListener('load', () => {
    const checkMarcarTodos = document.querySelector('input[name=marcar_todos]');
    const checkTabela = document.querySelectorAll('.input_tabela');
    checkTabela.forEach(input => {
        input.addEventListener('change', () => {
            const quantidade = document.querySelectorAll('.input_tabela:checked').length;
            if (quantidade == checkTabela.length) {
                checkMarcarTodos.checked = true;
                return;
            } else if (checkMarcarTodos) {
                checkMarcarTodos.checked = false;
            }
        });
    });

    checkMarcarTodos.addEventListener('change', () => {
        if (checkMarcarTodos.checked) {
            checkTabela.forEach(input => {
                input.checked = true;
            });
        }
    });

    const checkAceito = document.querySelector('input[name=aceito]');
    document.querySelector('button').addEventListener('click', () => {
        if (!checkAceito.checked) {
            alert('Marque a caixa de alerta com os termos para continuar.');
            return false;
        }

        const quantidadeMarcado = document.querySelectorAll('.input_tabela:checked').length;
        if (quantidadeMarcado < 1) {
            alert('Marque pelo menos uma tabela para continuar.');
            return false;
        }
    });
});
