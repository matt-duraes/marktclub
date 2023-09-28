// @import "../../../../comercial_empresa/Views/add/js/all"

window.addEventListener('load', () => {
    const input_standby = document.querySelector('#input_motivo_standby');
    const blocoFieldset = input_standby.parentNode.parentNode.parentNode;
    const status = document.querySelector('#input_status').value;

    if (status !== 'standby') {
        blocoFieldset.style.display = 'none';
    }
});
