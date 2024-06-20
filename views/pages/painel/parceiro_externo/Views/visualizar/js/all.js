// @template "painel"
// @system "Form"
// @painel "app_geral_visualizar"

window.addEventListener('load', () => {
    /*
    |--------------------------------------------------------------------------
    | UPLOAD DE IMAGEM
    |--------------------------------------------------------------------------
    */
    const botaoUpload = $('#botao_historico_upload');
    const previaPadrao = $('#bloco_previa_item_padrao');
    const blocoPreviaLista = $('#bloco_previa_lista');
    let uploadId = 1;
    if (botaoUpload) {
        botaoUpload.evento('change', () => {
            const quantidade = botaoUpload.files.length;
            if (quantidade == 0) {
                botaoUpload.value = '';
                return;
            }

            blocoPreviaLista.aparecer();
            let i = 0;
            for (; i < quantidade; ++i) {
                adicionarArquivoPrevio(botaoUpload.files[i]);
            }
            botaoUpload.value = '';
        });
    }
    const adicionarArquivoPrevio = arquivo => {
        const clone = previaPadrao.clonar();
        $('.arquivo_previa_titulo', clone).texto(arquivo.name);
        blocoPreviaLista.final(clone);
    };
});
