class LoadingSistema {
    static carregar(bloco) {
        // MASCARA
        if (typeof fwMascaraLoading === 'function') {
            fwMascaraLoading(bloco);
        }

        // FORM
        if (typeof fwFormLoading === 'function') {
            fwFormLoading(bloco);
        }

        // CKEDITOR;
        if (typeof fwCkeditorLoading === 'function') {
            fwCkeditorLoading(bloco);
        }
        // UPLOAD ARQUIVO;
        if (typeof fwFormArquivoListaLoading === 'function') {
            fwFormArquivoListaLoading(bloco);
        }
        if (typeof fwFormArquivoLoading === 'function') {
            fwFormArquivoLoading(bloco);
        }
        // FORM SELECT INPUT TAG
        if (typeof fwFormSelectInputTagLoading === 'function') {
            fwFormSelectInputTagLoading(bloco);
        }
        // FORM TABELA
        if (typeof fwFormTabelaLoading === 'function') {
            fwFormTabelaLoading(bloco);
        }
        // FORM LISTA
        if (typeof fwFormListaLoading === 'function') {
            fwFormListaLoading(bloco);
        }
    }
}
