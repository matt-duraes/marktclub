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
            ppe(bloco);
            fwCkeditorLoading(bloco);
        }
        // UPLOAD ARQUIVO;
        if (typeof fwFormArquivoListaLoading === 'function') {
            fwFormArquivoListaLoading(bloco);
        }
        if (typeof fwFormArquivoLoading === 'function') {
            fwFormArquivoLoading(bloco);
        }
    }
}
