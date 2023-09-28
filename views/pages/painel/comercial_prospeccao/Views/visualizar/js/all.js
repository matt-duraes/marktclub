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

    div_standby.childNodes.forEach((item) => {
        const p = item.querySelector('p');

        if (!p.querySelector('span') || status === 'standby') {
            blocoFieldsetStandby.style.display = '';
        }
    })

    div_perdido.childNodes.forEach((item) => {
        const p = item.querySelector('p');

        if (!p.querySelector('span') || status === 'inativo') {
            blocoFieldsetPerdido.style.display = '';
        }
    })
})
