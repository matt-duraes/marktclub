window.addEventListener('load', () => {
    /*
    |--------------------------------------------------------------------------
    | VERIFICA SE TEM DEPENDENTE
    |--------------------------------------------------------------------------
    */
    const blocoAnalytics = document.querySelector('#bloco_analytics');
    if (!blocoAnalytics) {
        return;
    }

    const idUsuario = document.querySelector('#input_visualizar_id').value;

    const buscarAnalytics = async (pagina, quantidade) => {
        const body = new FormData();
        body.append('usuario', idUsuario);
        body.append('pagina', pagina);
        body.append('quantidade', quantidade);
        body.append('indice', 'analytics');
        const resposta = await fetch(LINK + '/app/ajax/usuario-cliente', {
            method: 'POST',
            body,
        });

        const loading = blocoAnalytics.querySelector('.loading');
        loading.parentNode.removeChild(loading);

        const json = await respostaJson(resposta, 'Ocorreu um erro ao buscar o analytics.');
        if (false === json) {
            blocoAnalytics.insertAdjacentHTML('beforeend', `<div class="zero">Erro ao buscar dados</div>`);
            return;
        }

        if (json.dado.length == 0) {
            blocoAnalytics.insertAdjacentHTML('beforeend', `<div class="zero">Sem dados no momento</div>`);
            return;
        }
    };
    buscarAnalytics(1, 5);
});
