// @import "../../../../comercial_empresa/Views/add/js/all"

window.addEventListener('load', () => {
    const input_standby = document.querySelector('#input_motivo_standby');
    const blocoFieldsetStandby = input_standby.parentNode.parentNode.parentNode;
    const status = document.querySelector('#input_status').value;

    const input_motivo_perdido = document.querySelector('#input_motivo_perdido');
    const blocoFieldsetPerdido = input_motivo_perdido.parentNode.parentNode.parentNode;

    if (status !== 'standby') {
        blocoFieldsetStandby.style.display = 'none';
    }

    blocoFieldsetPerdido.style.display = 'none';

    if(input_motivo_perdido.value !== '' || status === 'inativo'){
        blocoFieldsetPerdido.style.display = '';
    }

    const input_contratou_concorrente = document.getElementById('input_contratou_concorrente')
    const input_qual_concorrente = document.getElementById('input_qual_concorrente')

    if(!input_qual_concorrente.value) {
        input_qual_concorrente.parentNode.style.display = 'none';
    }

    input_contratou_concorrente.addEventListener('change', () => {
        if (input_contratou_concorrente.value === 'sim') {
            input_qual_concorrente.parentNode.style.display = '';
        } else {
            input_qual_concorrente.parentNode.style.display = 'none';
        }
    })
});
