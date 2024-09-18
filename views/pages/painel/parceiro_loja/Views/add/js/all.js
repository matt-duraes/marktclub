// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const inputTipoLoja = $('#input_tipo_loja');
    const inputTipoProcedimento = $('#input_tipo_procedimento');
    const inputTipoJuridico = $('#input_tipo_juridico');
    const inputDocumentoCpf = $('#input_documento_cpf');
    const inputDocumentoCnpj = $('#input_documento_cnpj');
    const inputConfirmarStatus = $('#input_confirmar_status');
    const inputConfirmarTituloTipo = $('#input_confirmar_titulo_tipo');
    const inputConfirmarTitulo = $('#input_confirmar_titulo');
    const inputConfirmarTextoTipo = $('#input_confirmar_texto_tipo');
    const inputConfirmarTexto = $('#input_confirmar_texto');

    const blocoDocumentoCpf = $('#bloco_documento_cpf');
    const blocoDocumentoCnpj = $('#bloco_documento_cnpj');
    const blocoVoucher = $$('#bloco_limite_voucher, #bloco_prazo_voucher, #bloco_prazo_voucher_fixo');

    const blocoConfirmarTitulo = $('#bloco_confirmar_titulo');
    const blocoConfirmarTexto = $('#bloco_confirmar_texto');

    const blocoConfirmarAtivo = $$('#bloco_confirmar_titulo_tipo, #bloco_confirmar_texto_tipo');
    const blocoConfirmarInativo = $$(
        '#bloco_confirmar_titulo, #bloco_confirmar_titulo_tipo, #bloco_confirmar_texto, #bloco_confirmar_texto_tipo'
    );

    inputTipoJuridico.evento('formChange', () => {
        mudarTipoJuridico();
    });
    const mudarTipoJuridico = () => {
        const valor = inputTipoJuridico.valor();
        if (valor == 'fisica') {
            blocoDocumentoCpf.aparecer();
            blocoDocumentoCnpj.sumir();
            inputDocumentoCnpj.valor('');
        } else if (valor == 'juridica') {
            blocoDocumentoCpf.sumir();
            blocoDocumentoCnpj.aparecer();
            inputDocumentoCpf.valor('');
        }
    };
    mudarTipoJuridico();

    inputTipoProcedimento.evento('formChange', () => {
        mudarTipoProcedimento();
    });
    const mudarTipoProcedimento = () => {
        const valor = inputTipoProcedimento.valor();
        if (valor == 'voucher') {
            blocoVoucher.aparecer();
            return;
        }
        blocoVoucher.sumir();
    };
    mudarTipoProcedimento();

    /*
    |--------------------------------------------------------------------------
    | CONFIRMAR
    |--------------------------------------------------------------------------
    */
    inputConfirmarStatus.evento('change', () => {
        if (!inputConfirmarStatus.checked) {
            inputConfirmarTituloTipo.valor('padrao');
            inputConfirmarTextoTipo.valor('padrao');
            inputConfirmarTitulo.valor('');
            inputConfirmarTexto.valor('');
        }
        mudarConfirmarStatus();
    });
    const mudarConfirmarStatus = () => {
        blocoConfirmarInativo.sumir();
        inputConfirmarTituloTipo.valor(vazio(inputConfirmarTitulo.valor()) ? 'padrao' : 'outro');
        inputConfirmarTextoTipo.valor(vazio(inputConfirmarTexto.valor()) ? 'padrao' : 'outro');
        if (inputConfirmarStatus.checked) {
            blocoConfirmarAtivo.aparecer();
            return;
        }
    };
    mudarConfirmarStatus();

    inputConfirmarTituloTipo.evento('formChange', () => {
        inputConfirmarTitulo.valor('');
        mudarConfirmarTitulo();
    });
    const mudarConfirmarTitulo = () => {
        if (inputConfirmarTituloTipo.valor() == 'outro') {
            blocoConfirmarTitulo.aparecer();
            inputConfirmarTitulo.focus();
            return;
        }
        blocoConfirmarTitulo.sumir();
    };
    mudarConfirmarTitulo();
    inputConfirmarTextoTipo.evento('formChange', () => {
        inputConfirmarTexto.valor('');
        mudarConfirmarTexto();
    });
    const mudarConfirmarTexto = () => {
        if (inputConfirmarTextoTipo.valor() == 'outro') {
            blocoConfirmarTexto.aparecer();
            inputConfirmarTexto.focus();
            return;
        }
        blocoConfirmarTexto.sumir();
    };
    mudarConfirmarTexto();
});
