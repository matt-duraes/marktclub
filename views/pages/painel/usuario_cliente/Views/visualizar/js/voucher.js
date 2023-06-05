window.addEventListener('load', () => {
    const inputEmpresa = document.querySelector('#input_id_empresa');
    const inputId = document.querySelector('#input_visualizar_id');
    const botao = document.querySelector('#botao_gerar_salavip');
    if (!botao) {
        return;
    }
    if (inputEmpresa && inputEmpresa.value == '0ffc5c56b99f81ca0edea8bdf524b688') {
        botao.setAttribute(
            'href',
            botao.getAttribute('href').replace(/\/^/, '') + '/890713a200a9e45aa85e2ae67aa41e74/' + inputId.value
        );
        return;
    }

    const bloco = botao.closest('.bloco_fieldset');
    bloco.parentNode.removeChild(bloco);
});
