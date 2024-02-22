window.addEventListener('load', () => {
    const tituloPopupContato = $('#titulo_popup_contato');
    const botaoTelefone = $('#botao_telefone');
    const botaoEmail = $('#botao_email');
    const blocoEmail = $('#conteudo_popup_contato .email');
    const blocoTelefone = $('#conteudo_popup_contato .telefone');
    const botaoCopiar = $$('.botao_copiar');
    const PopupContato = new Popup('contato', 'bloco_contato', true, true);

    botaoTelefone.addEventListener('click', () => {
        tituloPopupContato.innerText = 'Telefone';

        blocoEmail.classList.add('display_none');
        blocoTelefone.classList.remove('display_none');

        PopupContato.abrir();
    });

    botaoEmail.addEventListener('click', () => {
        tituloPopupContato.innerText = 'E-mail';

        blocoTelefone.classList.add('display_none');
        blocoEmail.classList.remove('display_none');

        PopupContato.abrir();
    });

    botaoCopiar.forEach(botao => {
        let ultimaCopia = null;

        botao.addEventListener('click', () => {
            if (new Date() - ultimaCopia < 1000) {
                return;
            }

            const valor = botao.getAttribute('data-valor');
            const tipo = botao.getAttribute('data-tipo');

            navigator.clipboard.writeText(valor);
            Alerta.notificacao(tipo + ' copiado com sucesso!', true);

            ultimaCopia = new Date();
        });
    });
});
