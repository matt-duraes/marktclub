const loadingMensagem = () => {
    const idDemanda = document.getElementById('input_id_demanda').value;
    const blocoFormMensagem = document.getElementById('form_mensagem');
    const blocoFormMensagemFake = document.getElementById('form_mensagem_fake');
    const inputMensagem = document.getElementById('input_mensagem');
    const hashMensagem = document.querySelector('#form_mensagem input[name=form_system_hash]').value;

    /*
    |--------------------------------------------------------------------------
    | MANIPULA TAMANHO DO FORM FAKE
    |--------------------------------------------------------------------------
    */
    const manipulaTamanhoFormFake = () => {
        const height = blocoFormMensagem.getBoundingClientRect().height;
        blocoFormMensagemFake.style.height = height + 'px';
    };
    manipulaTamanhoFormFake();

    /*
    |--------------------------------------------------------------------------
    | AÇÃO DO ENTER NO INPUT
    |--------------------------------------------------------------------------
    */
    inputMensagem.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
        }
        if (e.shiftKey && e.key == 'Enter') {
            let valor = inputMensagem.value;

            const posicaoCursorInicial = inputMensagem.selectionStart;
            const posicaoCursorFinal = inputMensagem.selectionEnd;
            const valorInicial = valor.substring(0, posicaoCursorInicial);
            const valorFinal = valor.substring(posicaoCursorFinal);
            const cursorFinal = posicaoCursorInicial + 1;

            inputMensagem.value = valorInicial + '\n' + valorFinal;
            inputMensagem.setSelectionRange(cursorFinal, cursorFinal);

            textareaTamanho();
        } else if (e.key == 'Enter') {
            salvarNovaMensagem();
        }
    });

    /*
    |--------------------------------------------------------------------------
    | SALVAR NOVA MENSAGEM
    |--------------------------------------------------------------------------
    */
    const salvarNovaMensagem = async () => {
        const texto = inputMensagem.value;

        if (texto == '') {
            Alerta.notificacao('O campo texto é obrigatório.', false);
            return;
        }
        Loading.show();

        const body = new FormData();
        body.append('id', idDemanda);
        body.append('texto', texto);
        body.append('form_system_hash', hashMensagem);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/demanda/mensagem', {
            method: 'POST',
            body,
        });
        Loading.hide();

        const json = await response.json();

        if (response.status == 201 && json.texto != undefined) {
            adicionarNovaMensagem(json.texto);
            return;
        }

        const mensagem = json.texto != undefined ? json.texto : 'Ocorreu um erro ao salvar sua mensagem.';
        Alerta.notificacao(mensagem, false);
    };

    /*
    |--------------------------------------------------------------------------
    | CONTROLA TAMANHO DO TEXTAREA
    |--------------------------------------------------------------------------
    */
    blocoFormMensagem.oninput = function () {
        textareaTamanho();
    };
    textareaTamanho();
};
