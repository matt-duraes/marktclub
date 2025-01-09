const fwFormUriLimparString = (input, livre, validar) => {
    let valor = input
        .valor()
        .replace(/^ */, '')
        .replace(/ {2,}$/, '');
    valor = valor.replace(/^\/*/, '');
    if (true === validar) {
        valor = valor.replace(/\/*$/, '').replace(/-*$/, '');
    }

    if (/^https?:\/\//i.test(valor)) {
        valor = valor.replace(/^https?:\/\//i, '').split('/');
        valor.shift();
        valor = valor.join('/');
    }

    if (livre) {
        valor = valor.replace(/[^a-zA-Z0-9\-._~!$&'()*+,;=/?%:@]/g, '');
    } else {
        valor = valor.replace(/ |_/g, '-');
        valor = valor.replace(/-+/g, '-');
        valor = valor.toLowerCase();
        valor = valor.replace(/[^a-z\/-]/g, '');
    }
    input.valor(valor);
};
const fwFormUriMascara = (input, livre) => {
    input.evento('keyup', () => {
        fwFormUriLimparString(input, livre);
    });
    input.evento('change', () => {
        fwFormUriLimparString(input, livre, true);
    });
};
const fwFormUriLoading = bloco => {
    const lista = $$('.form_input_uri input', bloco);
    if (lista.length == 0) {
        return;
    }
    for (const input of lista) {
        const livre = input.attr('data-livre') === 'sim';
        fwFormUriMascara(input, livre);
    }
};
fwFormUriLoading(document);
