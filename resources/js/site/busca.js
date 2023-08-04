window.addEventListener('load', () => {
    const botaoBuscarAbrir = $('#botao_buscar_abrir');
    const botaoBuscarFechar = $$('.botao_buscar_fechar');
    const blocoBuscar = $('#bloco_buscar');
    const formBuscar = $('#form_buscar');

    botaoBuscarAbrir.addEventListener('click', () => {
        body.classList.add('body_scroll_hidden');
        formBuscar.classList.remove('display_none');
        setTimeout(() => {
            blocoBuscar.classList.add('ativo');
        }, 40);
    });
    formBuscar.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'form_buscar') {
            fecharBusca();
        }
    });
    botaoBuscarFechar.forEach(botao => {
        botao.addEventListener('click', () => {
            fecharBusca();
        });
    });

    const fecharBusca = () => {
        blocoBuscar.classList.remove('ativo');
        setTimeout(() => {
            body.classList.remove('body_scroll_hidden');
            formBuscar.classList.add('display_none');
        }, 300);
    };
});
