// @template "painel"
// @painel "app_geral_visualizar"

window.addEventListener('load', () => {
    const div_standby = document.querySelector('.bloco_standby');
    const div_perdido = document.querySelector('.bloco_perdido');

    const blocoFieldsetStandby = div_standby.parentNode.parentNode;
    const status = document.querySelector('#hidden_status').value;
    blocoFieldsetStandby.style.display = 'none';

    const blocoFieldsetPerdido = div_perdido.parentNode.parentNode;
    blocoFieldsetPerdido.style.display = 'none';

    const removerDisplayNone = (div, statusEsperado) => {
        div.childNodes.forEach((item) => {
            if (item.nodeType === 3) {
                return
            }

            const p = item.querySelector('p');

            if(!p) {
                return
            }

            if (!p.querySelector('span') || status === statusEsperado) {
                div.style.display = '';
            }
        })
    }

    removerDisplayNone(div_standby, 'standby');
    removerDisplayNone(div_perdido, 'inativo');
})
