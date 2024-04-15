window.addEventListener('load', () => {
    const formBuscar = $('#form_buscar');
    if (!formBuscar) {
        return;
    }
    const botaoBuscarAbrir = $$('.botao_buscar_abrir');
    const botaoBuscarFechar = $$('.botao_buscar_fechar');
    const blocoBuscarTemplate = $('#bloco_template_buscar');

    blocoBuscarTemplate.appendChild(formBuscar);

    const abrirBlocoBusca = e => {
        const target = e.target;
        let id = '';
        if (target.classList.contains('botao_buscar_input') || target.closest('.botao_buscar_input')) {
            const bloco = target.classList.contains('botao_buscar_input')
                ? target
                : target.closest('.botao_buscar_input');
            id = bloco.getAttribute('id').replace(/\_fake(_texto){0,1}$/, '');
        }
        BODY.classList.add('body_scroll_hidden');
        formBuscar.classList.remove('display_none');
        setTimeout(() => {
            formBuscar.classList.add('ativo');
        }, 40);
        setTimeout(() => {
            if (id != '') {
                formFocus($('#' + id));
            }
        }, 340);
    };
    botaoBuscarAbrir.forEach(botao => {
        botao.addEventListener('click', e => {
            abrirBlocoBusca(e);
        });
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
        formBuscar.classList.remove('ativo');
        setTimeout(() => {
            BODY.classList.remove('body_scroll_hidden');
            formBuscar.classList.add('display_none');
        }, 300);
    };
});
