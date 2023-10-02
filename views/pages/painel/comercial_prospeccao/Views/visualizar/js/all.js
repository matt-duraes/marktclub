// @template "painel"
// @painel "app_geral_visualizar"

window.addEventListener('load', () => {
    const inputStatus = document.querySelector('#hidden_status');
    const status = inputStatus.value

    const div_standby = inputStatus.parentNode;
    const div_perdido = document.querySelector('#hidden_perdido').parentNode;

    const blocoFieldsetStandby = div_standby.parentNode;
    blocoFieldsetStandby.style.display = 'none';

    const blocoFieldsetPerdido = div_perdido.parentNode;
    blocoFieldsetPerdido.style.display = 'none';


    const removerDisplayNone = (div, bloco, statusEsperado) => {
        div.childNodes.forEach((item) => {
            if (item.nodeType === 3) {
                return
            }

            const p = item.querySelector('p');

            if(!p) {
                return
            }

            if (!p.querySelector('span') || status === statusEsperado) {
                bloco.style.display = '';
            }
        })
    }

    removerDisplayNone(div_standby, blocoFieldsetStandby, 'standby');
    removerDisplayNone(div_perdido, blocoFieldsetPerdido, 'inativo');
})
