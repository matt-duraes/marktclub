window.addEventListener('load', () => {
    const botaoBuscarAbrir = $$('.botao_buscar_abrir');
    const botaoBuscarFechar = $$('.botao_buscar_fechar');
    const formBuscar = $('#form_buscar');
    const blocoBuscarTemplate = $('#bloco_template_buscar');

    blocoBuscarTemplate.appendChild(formBuscar);

    const abrirBlocoBusca = () => {
        BODY.classList.add('body_scroll_hidden');
        formBuscar.classList.remove('display_none');
        setTimeout(() => {
            formBuscar.classList.add('ativo');
        }, 40);
    };
    adicionarEvento('click', botaoBuscarAbrir, abrirBlocoBusca);

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
        formBuscar.classList.remove('ativo');
        setTimeout(() => {
            BODY.classList.remove('body_scroll_hidden');
            formBuscar.classList.add('display_none');
        }, 300);
    };
});
