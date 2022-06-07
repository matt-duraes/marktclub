const fwFormTagLista = document.querySelectorAll('.form_geral .fw_form_tag');
const fwFormTagAjuda = document.querySelector('#bloco_fw_ajuda');

if (fwFormTagLista.length > 0) {
    let fwFormTagInput, fwFormTagEspaco;
    fwFormTagLista.forEach(bloco => {
        fwFormTagInput = bloco.querySelector('input');
        fwFormTagEspaco = bloco.getAttribute('data-espaco');
        fwDormTagTipo = bloco.getAttribute('data-tipo');

        // key up
        fwFormTagInput.addEventListener('keyup', e => {
            const tecla = e.key;
            if ((tecla == ' ' && fwFormTagEspaco == 'nao') || tecla == 'Enter' || tecla == ',' || tecla == ';') {
                fwFormTagAdicionarTag(bloco, fwFormTagInput, fwDormTagTipo);
                return;
            }
            fwFormTagInput.value = fwFormTagLimparValor(fwFormTagInput.value, fwDormTagTipo);
        });

        // remover todos
        bloco.querySelector('.fw_form_tag_remover_todos').addEventListener('click', async () => {
            const confimar = await Alerta.confirmar(
                'Remover tags',
                'Tem certeza que deseja remover todas as tags? Essa ação não poderá ser desfeita.',
                '!'
            );
            if (!confimar) {
                return;
            }
            const itemLista = bloco.querySelectorAll('.fw_form_tag_item');
            itemLista.forEach(item => {
                item.parentNode.removeChild(item);
            });
            bloco.querySelector('.fw_form_tag_remover_todos').classList.add('fw_form_tag_hide');
        });

        // remover individual
        bloco.addEventListener('dblclick', e => {
            const target = e.target;
            if (target.classList.contains('fw_form_tag_remover') || target.closest('.fw_form_tag_remover')) {
                const item = target.closest('.fw_form_tag_item');
                const bloco = target.closest('.fw_form_tag');
                if (item) {
                    item.parentNode.removeChild(item);
                }

                if (fwFormTagAjuda) {
                    fwFormTagAjuda.innerHTML = '';
                }

                if (!bloco) {
                    return;
                }

                if (bloco && bloco.querySelectorAll('.fw_form_tag_item').length == 0) {
                    bloco.querySelector('.fw_form_tag_remover_todos').classList.add('fw_form_tag_hide');
                }
            }
        });
    });
}

const fwFormTagAdicionarTag = (bloco, input, tipo) => {
    const valor = fwFormTagLimparValor(input.value, tipo).trim();

    input.value = '';
    if (!/^https\:\/\/[a-z\.\-]+\.[a-z\.\-]+/.test(valor)) {
        Alerta.notificacao('URL inválida!', false);
        return;
    } else if (valor == '') {
        return;
    }

    const existe = bloco.querySelector('.fw_form_tag_item[data-item="' + valor + '"]');
    if (existe) {
        return;
    }

    bloco.querySelector('.fw_form_tag_remover_todos').classList.remove('fw_form_tag_hide');
    bloco.querySelector('.fw_form_tag_lista').insertAdjacentHTML(
        'afterbegin',
        `
            <div class="fw_form_tag_item" data-item="${valor}"><span>${valor}</span><div class="fw_form_tag_remover" data-ajuda="Clique duas vezes no x para remover"><svg height="8" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"  xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg></div></div>
        `
    );
};

const fwFormTagLimparValor = (valor, tipo) => {
    if (tipo == 'tag') {
        return valor
            .replace(/[ÀÁÂÃÄÅ]/gi, 'A')
            .replace(/[àáâãäå]/gi, 'a')
            .replace(/[ÈÉÊË]/gi, 'E')
            .replace(/[^a-z0-9\ ]/gi, '')
            .toLowerCase();
    } else if (tipo == 'url') {
        return valor
            .replace(/[ÀÁÂÃÄÅ]/gi, 'A')
            .replace(/[àáâãäå]/gi, 'a')
            .replace(/[ÈÉÊË]/gi, 'E')
            .replace(/[^a-z0-9\.\/\?\=\:]/gi, '')
            .toLowerCase();
    }
};
