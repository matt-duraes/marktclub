window.addEventListener('load', () => {
    const inputCategoria = $('#input_categoria');
    const inputSubcategoria = $('#input_subcategoria');

    formSelectChange = acao => {
        if (acao == 'mudarCategoria') {
            buscarSubCategoria(inputCategoria.value);
        }
    };

    const buscarSubCategoria = async (categoria, valor) => {
        if (categoria == '') {
            formSelectOption(inputSubcategoria, { '': 'Escolha uma categoria' });
            return;
        }
        formSelectLoading(inputSubcategoria);
        const resposta = await ajaxPost(LINK + '/convenios/subcategoria', { categoria }, '');
        if (false === resposta) {
            return;
        }
        formSelectOption(inputSubcategoria, resposta.dado, valor);
    };
    buscarSubCategoria(inputCategoria.value, inputSubcategoria.value);

    const removerItemDaBusca = item => {
        Loading.show();
        item.parentNode.removeChild(item);
        const lista = $$('.buscar_convenio_lista .item');
        const parametro = [];
        lista.forEach(item => {
            parametro.push(item.getAttribute('data-indice') + '=' + item.getAttribute('data-valor'));
        });
        if (parametro.length == 0) {
            window.location.assign(LINK + '/convenios');
            return;
        }
        window.location.assign(LINK + '/convenios/buscar?' + parametro.join('&'));
    };
    const blocoFiltro = $('.buscar_convenio_lista');
    if (blocoFiltro) {
        blocoFiltro.addEventListener('click', e => {
            const remover = e.target.classList.contains('.remover') || e.target.closest('.remover');
            if (remover) {
                removerItemDaBusca(e.target.closest('.item'));
            }
        });
    }
});

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
