// @template "painel"
// @painel "app_geral_visualizar"

window.addEventListener('load', () => {
    const linhaDado = document.querySelectorAll('.linha_dado');
    const blocoDependente = document.getElementById('bloco_dependente');

    blocoDependente.style.display = 'none';

    if(linhaDado.length > 1) {
        blocoDependente.style.display = '';
    }
});
