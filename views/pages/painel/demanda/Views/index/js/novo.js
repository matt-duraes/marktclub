const demandaNovaLoad = () => {
    formSelectChange = acao => {
        if (acao == 'mudarTipoDominio') {
            mudarTipoDominio();
        }
    };

    /*
    |--------------------------------------------------------------------------
    | DOMINIO
    |--------------------------------------------------------------------------
    */
    const blocoDominioSub = document.getElementById('bloco_dominio_sub');
    const blocoDominioSubTexto = document.getElementById('bloco_dominio_sub_texto');
    const blocoDominioDns = document.getElementById('bloco_dominio_dns');
    const blocoDominioProprio = document.getElementById('bloco_dominio_proprio');
    const blocoDominioExclusivo = document.getElementById('bloco_dominio_exclusivo');

    const inputDominioTipo = document.getElementById('input_dominio_tipo');
    const inputDominioExclusivo = document.getElementById('input_dominio_exclusivo');
    const inputDominioProprio = document.getElementById('input_dominio_proprio');
    const inputDominioSub = document.getElementById('input_dominio_sub');

    const mudarTipoDominio = () => {
        blocoDominioSub.classList.add('display_none');
        blocoDominioDns.classList.add('display_none');
        blocoDominioProprio.classList.add('display_none');
        blocoDominioExclusivo.classList.add('display_none');
        inputDominioExclusivo.checked = false;
        inputDominioProprio.value = '';
        inputDominioSub.value = '';

        const valor = inputDominioTipo.value;
        if (valor == '') {
            return;
        } else if (valor == 'proprio') {
            blocoDominioProprio.classList.remove('display_none');
            blocoDominioExclusivo.classList.remove('display_none');
            inputDominioProprio.focus();
            return;
        }
        blocoDominioSub.classList.remove('display_none');
        blocoDominioSubTexto.innerText = '.' + valor + '.com.br';
        inputDominioSub.focus();
    };

    inputDominioExclusivo.addEventListener('change', () => {
        if (inputDominioExclusivo.checked) {
            blocoDominioDns.classList.remove('display_none');
            return;
        }
        blocoDominioDns.classList.add('display_none');
    });

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    const blocoDominioLogin = document.getElementById('bloco_dominio_login');
    const inputDominioLogin = document.getElementById('input_dominio_login');
    const inputLoginApi = document.getElementById('input_login_api');
    inputLoginApi.addEventListener('change', () => {
        mostrarObservacaoApp();
        if (inputLoginApi.checked) {
            blocoDominioLogin.classList.remove('display_none');
            return;
        }
        blocoDominioLogin.classList.add('display_none');
        inputDominioLogin.value = '';
    });

    /*
    |--------------------------------------------------------------------------
    | APP
    |--------------------------------------------------------------------------
    */
    const blocoApp = document.getElementById('bloco_app');
    const inputApp = document.getElementById('input_app');

    inputApp.addEventListener('click', () => {
        mostrarObservacaoApp();
    });
    const mostrarObservacaoApp = () => {
        if (inputApp.checked && inputLoginApi.checked) {
            blocoApp.classList.remove('display_none');
            return;
        }
        blocoApp.classList.add('display_none');
    };
};
