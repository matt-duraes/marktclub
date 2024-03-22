// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const inputTipoProcedimento = $('#input_tipo_procedimento');
    const inputTipoJuridico = $('#input_tipo_juridico');
    const inputDocumentoCpf = $('#input_documento_cpf');
    const inputDocumentoCnpj = $('#input_documento_cnpj');

    const blocoDocumentoCpf = $('#bloco_documento_cpf');
    const blocoDocumentoCnpj = $('#bloco_documento_cnpj');
    const blocoVoucher = $$('#bloco_limite_voucher, #bloco_prazo_voucher, #bloco_prazo_voucher_fixo');

    inputTipoJuridico.evento('formChange', () => {
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
    });

    inputTipoProcedimento.evento('formChange', () => {
        const valor = inputTipoProcedimento.valor();
        if (valor == 'voucher') {
            blocoVoucher.aparecer();
            return;
        }
        blocoVoucher.sumir();
    });
});
