// @template "painel"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const inputFinalidadePrincipal = document.getElementById('input_finalidade_principal');
    const inputFinalidadeSecundaria = document.getElementById('input_finalidade_secundaria');

    inputFinalidadePrincipal.evento('formChange', () => {
        buscarListaFinalidadeSecundaria();
    });
    const buscarListaFinalidadeSecundaria = async () => {
        const valor = inputFinalidadePrincipal.value;
        formSelectLoading(inputFinalidadeSecundaria);
        const resposta = await ajaxPost(LINK + '/app/classe/comercial-empresa', {
            classe: 'finalidade_secundaria',
            tipo: valor,
        });
        if (false === resposta) {
            formSelectOption(inputFinalidadeSecundaria, { '': 'Ocorreu um erro ao buscar lista' }, '');
            return;
        }
        formSelectOption(inputFinalidadeSecundaria, resposta.dado);
    };
});
