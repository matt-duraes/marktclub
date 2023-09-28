// @template "painel"
// @painel "app_geral_visualizar"

window.addEventListener('load', () => {
    const div_standby = document.querySelector('.bloco_standby');
    const blocoFieldset = div_standby.parentNode.parentNode;
    const status = document.querySelector('#hidden_status').value;
    blocoFieldset.style.display = 'none';

    div_standby.childNodes.forEach((item) => {
        const p = item.querySelector('p');

        if (!p.querySelector('span') || status === 'standby') {
            blocoFieldset.style.display = '';
        }
    })
})
