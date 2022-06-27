const loadingScroll = () => {
    const blocoDemanda = document.getElementById('bloco_demanda_detalhe');
    const blocoMensagem = document.getElementById('bloco_mensagem');
    const blocoFormMensagem = document.getElementById('form_mensagem');
    const blocoTopo = document.getElementById('bloco_topo');

    /*
    |--------------------------------------------------------------------------
    | ANIMAR TOPO
    |--------------------------------------------------------------------------
    */
    const animarBlocoTopo = scroll => {
        if (scroll >= 20 && !blocoTopo.classList.contains('fixo')) {
            blocoTopo.classList.add('fixo');
        } else if (scroll < 20 && blocoTopo.classList.contains('fixo')) {
            3;
            blocoTopo.classList.remove('fixo');
        }
        if (scroll >= 50 && !blocoTopo.classList.contains('sombra')) {
            blocoTopo.classList.add('sombra');
        } else if (scroll < 50 && blocoTopo.classList.contains('sombra')) {
            blocoTopo.classList.remove('sombra');
        }
    };
    animarBlocoTopo(blocoDemanda.scrollTop);

    /*
    |--------------------------------------------------------------------------
    | ANIMAR FORMULÁRIO DE MENSAGEM
    |--------------------------------------------------------------------------
    */
    const animarFormMensagem = () => {
        const top = blocoMensagem.getBoundingClientRect().top;
        if (top <= 50 && !blocoFormMensagem.classList.contains('fixo')) {
            blocoFormMensagem.classList.add('fixo');
        } else if (top > 50 && blocoFormMensagem.classList.contains('fixo')) {
            blocoFormMensagem.classList.remove('fixo');
        }

        if (top <= 20 && !blocoFormMensagem.classList.contains('sombra')) {
            blocoFormMensagem.classList.add('sombra');
        } else if (top > 20 && blocoFormMensagem.classList.contains('sombra')) {
            blocoFormMensagem.classList.remove('sombra');
        }
    };
    animarFormMensagem();

    /*
    |--------------------------------------------------------------------------
    | ANIMAR AO SCROLL
    |--------------------------------------------------------------------------
    */
    blocoDemanda.addEventListener('scroll', e => {
        const scroll = blocoDemanda.scrollTop;
        animarBlocoTopo(scroll);
        animarFormMensagem();
    });
};
