// @template "painel"
// @painel "app_geral_visualizar"

window.addEventListener('load', () => {
    const linha_dado = document.querySelectorAll('.linha_dado')
    const bloco_dependente = document.getElementById('bloco_dependente')

    bloco_dependente.style.display = 'none'

    if(linha_dado.length > 1) {
        bloco_dependente.style.display = ''
    }
});
