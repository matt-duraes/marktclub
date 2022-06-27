// @template "painel"
// @system "Form"
// @resource "painel/app_geral_add"

window.addEventListener('load', () => {
    const blocoTamanho = document.getElementById('bloco_tamanho_fixo');
    const inputTipo = document.getElementById('input_tipo');
    const inputWidth = document.getElementById('input_width');
    const inputHeight = document.getElementById('input_height');

    formSelectChange = tipo => {
        if (tipo == 'mudarTipoAlbum') {
            mudarTipoAlbum(false);
        }
    };

    const mudarTipoAlbum = iniciar => {
        const tipo = inputTipo.value;
        if (!iniciar) {
            inputWidth.value = '';
            inputHeight.value = '';
        }
        if (tipo == 2) {
            blocoTamanho.className = 'tamanho_width_height';
            if (!iniciar) {
                inputWidth.focus();
            }
        } else if (tipo == 3) {
            blocoTamanho.className = 'tamanho_width';
            if (!iniciar) {
                inputWidth.focus();
            }
        } else if (tipo == 4) {
            blocoTamanho.className = 'tamanho_height';
            if (!iniciar) {
                inputHeight.focus();
            }
        } else {
            blocoTamanho.className = '';
        }
    };
    mudarTipoAlbum(true);
});
