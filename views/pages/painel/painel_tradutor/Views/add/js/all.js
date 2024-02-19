// @template "painel"
// @painel "app_geral_add"

//window.addEventListener('load', () => {
const inputTermo = $('#input_termo');
const inputTraducaoIngles = $('#traducao_en input');
const inputTraducaoEspanhol = $('#traducao_es input');

const buscarTraducao = async (termo) => {
    const resposta = await ajaxPost(
        LINK + '/app/ajax/painel-tradutor',
        {
            indice: 'traduzir',
            texto: termo,
        },
        'Ocorreu um erro ao tentar traduzir.',
    );

    if (false === resposta) {
        return;
    }

    formValue(inputTraducaoIngles, resposta.dado.traducao.en);
    formValue(inputTraducaoEspanhol, resposta.dado.traducao.es);
};

if (inputTermo) {
    inputTermo.addEventListener('change', () => {
        buscarTraducao(inputTermo.value);
    });
}
//});
