// @template "painel"
// @painel "app_lista_index"
// @painel "form_geral"

const linhas = document.querySelectorAll('.dado');

const atualizarStatus = async (id, e) => {
    const status = e.target.checked ? 'novo' : 'cancelado';

    Loading.show();

    await ajaxPost(LINK + '/app/ajax/parceiro-cupom', {
        indice: 'status-atualizar',
        id,
        status
    });

    Loading.hide();
};

const removerStatusDaLinha = (linha) => {
    const divStatus = linha.querySelector('.status');
    const barra = linha.querySelectorAll('.barra');

    if(divStatus) {
        divStatus.style.display = 'none';
    }

    if(barra) {
        barra[barra.length - 1].style.display = 'none';
    }
};

const criarSwitch = (status, index) => {
    const botaoSwitch = document.createElement('div');
    botaoSwitch.classList.add('form_geral');

    botaoSwitch.innerHTML = `
        <div class="bloco_switch">
            <input
                type="checkbox"
                id="input_status${index}"
                ${status == 'Novo' ? "checked value='sim'" : ''}"
            >
            <label for="input_status${index}">
                <p></p>
                <span></span>
            </label>
        </div>
    `;

    return botaoSwitch;
};

linhas.forEach((linha, index) => {
    if(index <= 1 || index == linhas.length - 1) {
        return;
    }
    removerStatusDaLinha(linha);

    const status = linha.querySelector('.status').dataset.titulo;
    const botaoSwitch = criarSwitch(status, index);

    linha.appendChild(botaoSwitch);

    const input = botaoSwitch.querySelector('input');
    const id = linha.parentNode.querySelector('input').value;

    input.addEventListener('change', (e) => atualizarStatus(id, e));
});

