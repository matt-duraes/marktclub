window.addEventListener('load', () => {
    const blocoCodigoGeral = document.getElementById('bloco_codigo_geral');
    const menuTrace = document.querySelectorAll('.botao_menu_trace');
    const blocoListaTrace = document.getElementById('bloco_lista_trace');
    const blocoTrace = document.getElementById('bloco_menu_trace');
    const menuTraceFechar = document.getElementById('botao_menu_trace_fechar');

    const listaArquivo = document.querySelectorAll('.botao_escolher_codigo');

    const criarAcaoParaEscolherArquivo = () => {
        [].forEach.call(listaArquivo, botao => {
            botao.addEventListener('click', function () {
                colocarBlocoCodigoSelecionado(botao).then(response => {
                    if (!response) {
                        return false;
                    }
                    colocarHoverNoArquivo(botao).then(response => {
                        if (!response) {
                            return false;
                        }
                        setarTamanhoMenuTrace();
                    });
                });
            });
        });
    };

    const colocarTracePrincipalScrollTopo = () => {
        const li = document.querySelector('.botao_escolher_codigo.hover');
        if (!li) {
            return false;
        }
        blocoListaTrace.scrollTop = li.offsetTop - 80;
    };
    if (listaArquivo.length > 0) {
        criarAcaoParaEscolherArquivo();
        colocarTracePrincipalScrollTopo();
    }

    const colocarHoverNoArquivo = async botao => {
        const hoverAtual = document.querySelectorAll('.botao_escolher_codigo.hover');
        [].forEach.call(hoverAtual, li => {
            li.classList.remove('hover');
        });
        botao.classList.add('hover');
        return true;
    };

    const colocarBlocoCodigoSelecionado = async botao => {
        const id = botao.getAttribute('data-id');
        const blocoAtual = document.querySelector('.bloco_codigo_geral.codigo_ativo');
        const blocoNovo = document.getElementById(id);

        if (!blocoAtual || !blocoNovo) {
            return false;
        }

        try {
            blocoAtual.classList.remove('codigo_ativo');
            blocoNovo.classList.add('codigo_ativo');
            blocoTrace.classList.remove('menu_trace_ativo');
            return true;
        } catch (error) {
            return false;
        }
    };

    [].forEach.call(menuTrace, menu => {
        menu.addEventListener('click', () => {
            setarTamanhoMenuTrace();
            blocoTrace.classList.add('menu_trace_ativo');
        });
    });
    menuTraceFechar.addEventListener('click', () => {
        blocoTrace.classList.remove('menu_trace_ativo');
    });

    const setarTamanhoMenuTrace = () => {
        const tamanho = blocoCodigoGeral.getBoundingClientRect().height;
        blocoListaTrace.style.height = tamanho - 40 + 'px';
    };
    setarTamanhoMenuTrace();

    window.addEventListener('resize', () => {
        blocoTrace.classList.remove('menu_trace_ativo');
        setarTamanhoMenuTrace();
    });

    /*
    |--------------------------------------------------------------------------
    | TRACE
    |--------------------------------------------------------------------------
    */
    const blocoPilhaRastreio = document.querySelector('.bloco_trace');
    const blocoPilhaTamanho = blocoPilhaRastreio ? blocoPilhaRastreio.getBoundingClientRect().height : 0;
    if (blocoPilhaTamanho > 400) {
        const botaoAbrirPilha = document.querySelector('.trace_abrir button');
        blocoPilhaRastreio.style['max-height'] = '400px';
        document.querySelector('.trace_abrir').style.display = 'flex';

        let pilhaRastreioAberta = false;
        botaoAbrirPilha.addEventListener('click', () => {
            if (!pilhaRastreioAberta) {
                blocoPilhaRastreio.style['max-height'] = '';
                botaoAbrirPilha.innerText = 'Fechar pilha';
            } else {
                blocoPilhaRastreio.style['max-height'] = '400px';
                botaoAbrirPilha.innerText = 'Abrir pilha';
            }
            pilhaRastreioAberta = !pilhaRastreioAberta;
        });
    }
});
