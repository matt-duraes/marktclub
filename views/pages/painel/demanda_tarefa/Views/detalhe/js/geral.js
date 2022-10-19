const loadingDetalhe = () => {
    /*
    |--------------------------------------------------------------------------
    | ADICIONA UMA NOVA MENSAGEM
    |--------------------------------------------------------------------------
    */
    const inputMensagem = document.getElementById('input_mensagem');
    const blocoMensagemLista = document.getElementById('bloco_mensagem_lista');
    const blocoMensagemAgora = document.getElementById('bloco_mensagem_agora');
    const blocoMensagemAgoraDado = document.getElementById('bloco_mensagem_agora_dado');
    const blocoMensagemZero = document.getElementById('bloco_mensagem_zero');
    const blocoFormMensagem = document.getElementById('form_mensagem');
    const blocoFormMensagemFake = document.getElementById('form_mensagem_fake');

    adicionarNovaMensagem = texto => {
        inputMensagem.value = '';
        blocoMensagemLista.classList.remove('mensagem_lista_primeiro');
        blocoMensagemAgora.classList.remove('hide');
        blocoMensagemAgoraDado.insertAdjacentHTML('afterbegin', '<div class="texto">' + texto + '</div>');
        blocoMensagemZero.classList.add('hide');
        textareaTamanho();
    };
    textareaTamanho = () => {
        inputMensagem.style.height = 0;
        const tamanho = inputMensagem.scrollHeight < 50 ? 50 : inputMensagem.scrollHeight;
        inputMensagem.style.height = tamanho + 'px';

        if (tamanho >= 70 && !blocoFormMensagem.classList.contains('sombra_digitando')) {
            blocoFormMensagem.classList.add('sombra_digitando');
        } else if (tamanho < 70 && blocoFormMensagem.classList.contains('sombra_digitando')) {
            blocoFormMensagem.classList.remove('sombra_digitando');
        }
        const formHeight = blocoFormMensagem.getBoundingClientRect().height;
        blocoFormMensagemFake.style.height = formHeight + 'px';
    };

    // Funções da página
    loadingMensagem();
    loadingScroll();
    loadingArquivo();
    loadingSeguindo();
    loadingConfig();

    /*
    |--------------------------------------------------------------------------
    | FECHAR DEMANDA
    |--------------------------------------------------------------------------
    */
    const botaoFechar = document.getElementById('botao_fechar_demanda');
    botaoFechar.addEventListener('click', () => {
        Pagina.staticFechar();
    });
};
