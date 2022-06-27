window.addEventListener('load', () => {
    formGeralCheckboxLoading(document.querySelector('body'));
});

const formGeralCheckboxLoading = blocoBuscar => {
    const listaMarcarTodosInput = blocoBuscar.querySelectorAll('.marcar_todas .input_checkbox input');
    const listaMarcarTodosBloco = blocoBuscar.querySelectorAll('.bloco_checkbox_marcar_todos');
    const listaBotaoMais = blocoBuscar.querySelectorAll('.bloco_checkbox_geral .botao_mais');

    const marcarOuDesmarcarInputPrincipal = bloco => {
        const inputNaoChecado = bloco.querySelectorAll('.input_checkbox input:not(:checked)');
        const inputMarcarTodos = bloco.querySelector('.marcar_todas .input_checkbox input');
        if (
            inputNaoChecado.length > 1 ||
            (inputNaoChecado.length == 1 && !inputNaoChecado[0].closest('.marcar_todas'))
        ) {
            inputMarcarTodos.checked = false;
            return;
        }
        inputMarcarTodos.checked = true;
    };
    const criarEventoClickNosInputs = bloco => {
        const listaTodosInput = bloco.querySelectorAll('.input_checkbox input');
        const quantidadeInput = listaTodosInput.length;
        if (quantidadeInput > 1) {
            let i;
            for (i = 1; i < quantidadeInput; ++i) {
                listaTodosInput[i].addEventListener('click', () => {
                    marcarOuDesmarcarInputPrincipal(bloco);
                });
            }
        }
    };

    if (listaMarcarTodosBloco.length > 0) {
        listaMarcarTodosBloco.forEach(bloco => {
            marcarOuDesmarcarInputPrincipal(bloco);
            criarEventoClickNosInputs(bloco);
        });
    }

    // Ação ao clicar no botão principal
    if (listaMarcarTodosInput.length > 0) {
        listaMarcarTodosInput.forEach(input => {
            input.addEventListener('change', () => {
                const bloco = input.closest('.bloco_checkbox_marcar_todos');
                let listaInput, valor;
                if (input.checked) {
                    listaInput = bloco.querySelectorAll('.input_checkbox input:not(:checked)');
                    valor = true;
                } else {
                    listaInput = bloco.querySelectorAll('.input_checkbox input:checked');
                    valor = false;
                }
                if (listaInput.length == 0) {
                    return;
                }
                marcarOuDesmarcarTodosCheckbox(listaInput, valor);
            });
        });
    }
    const marcarOuDesmarcarTodosCheckbox = (lista, valor) => {
        lista.forEach(input => {
            input.checked = valor;
        });
    };
    if (listaBotaoMais.length > 0) {
        listaBotaoMais.forEach(botao => {
            botao.addEventListener('click', () => {
                const bloco = botao.closest('.bloco_checkbox_geral');
                if (bloco.classList.contains('bloco_checkbox_aberto')) {
                    bloco.classList.remove('bloco_checkbox_aberto');
                    botao.innerHTML = '<span>Mostrar todos</span>';
                } else {
                    bloco.classList.add('bloco_checkbox_aberto');
                    botao.innerHTML = '<span>Fechar</span>';
                }
            });
        });
    }
};
