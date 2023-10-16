window.addEventListener('load', () => {
    const botaoTema = $('#botao_tema');
    const botaoTemaMobile = $('#botao_tema_mobile');
    if (!botaoTema) {
        return;
    }

    const HTML = $('html');
    const loadingTema = () => {
        const lista = $$('#bloco_tema article');
        lista.forEach(botao => {
            botao.addEventListener('click', () => {
                const atual = $('#bloco_tema .hover');
                if (atual) {
                    atual.classList.remove('hover');
                }
                botao.classList.add('hover');
                let tema = botao.getAttribute('data-tema');
                ajaxPost(
                    LINK + '/tema',
                    {
                        tema,
                    },
                    ''
                );
                if (tema == 'automatico') {
                    const hora = new Date().getHours();
                    tema = hora >= 8 && hora <= 17 ? 'light' : 'dark';
                } else if (tema == 'sistema') {
                    tema = window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
                }
                HTML.setAttribute('data-theme', tema);
            });
        });
    };
    const PaginaThema = new Pagina('escolher-tema', LINK + '/tema', undefined, true, true, loadingTema);
    botaoTema.addEventListener('click', () => {
        PaginaThema.abrir();
    });
    botaoTemaMobile.addEventListener('click', () => {
        PaginaThema.abrir();
    });
});
