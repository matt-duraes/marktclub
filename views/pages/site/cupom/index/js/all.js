// @system "Form"
// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"

const cupomDetalhe = () => {
    const inputCodigo = document.getElementById('input_cupom_valor');
    const botaoCopiar = document.getElementById('botao_cupom_copiar');
    const botaoFechar = document.querySelector('#bloco_popup_detalhe .fechar');
    botaoFechar.addEventListener('click', () => {
        Pagina.staticFechar();
    });

    if (!inputCodigo) {
        return;
    }
    inputCodigo.addEventListener('click', () => {
        inputCodigo.select();
    });
    botaoCopiar.addEventListener('click', () => {
        let range, copiar;
        if (document.selection) {
            range = document.body.createTextRange();
            range.moveToElementText(inputCodigo);
            range.select();
            copiar = document.execCommand('copy');
            range = document.body.createTextRange();
            range.moveToElementText();
        } else if (window.getSelection) {
            range = document.createRange();
            range.selectNode(inputCodigo);
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            copiar = document.execCommand('copy');
            window.getSelection().removeAllRanges();
        }

        if (copiar) {
            Alerta.notificacao('Copiado com sucesso.', true);
        } else {
            Alerta.notificacao('Erro ao copiar, seu navegador pode não ter suporte a essa função.', false);
        }
    });
};

window.addEventListener('load', () => {
    const cupomLista = document.querySelectorAll('#bloco_cupom article.botao_abrir_parceiro');

    if (!cupomLista) {
        return;
    }

    cupomLista.forEach(cupom => {
        const url = cupom.getAttribute('data-url');
        const PaginaCupom = new Pagina('cupom - ' + url, '/cupom/' + url, {}, true, true, cupomDetalhe);

        cupom.addEventListener('click', () => {
            PaginaCupom.abrir();
        });
    });
});
