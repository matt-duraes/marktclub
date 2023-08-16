window.addEventListener('load', () => {
    const inputCategoria = $('#input_categoria');
    const inputSubcategoria = $('#input_subcategoria');

    inputCategoria.addEventListener('formChange', () => {
        buscarSubCategoria(inputCategoria.value);
    });

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
