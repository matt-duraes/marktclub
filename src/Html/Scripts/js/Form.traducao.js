const fwFormTraducaoPegarValor = input => {
    const br = $('.form_input_traducao_br', input);
    const en = $('.form_input_traducao_en', input);
    const es = $('.form_input_traducao_es', input);
    return {
        br: br ? br.value : '',
        en: en ? en.value : '',
        es: es ? es.value : '',
    };
};
const fwFormTraducaoSetarValor = (input, valor) => {
    const eObject = typeof valor === 'object';
    $('.form_input_traducao_br', input).value = eObject && 'br' in valor ? valor.br : '';
    $('.form_input_traducao_en', input).value = eObject && 'en' in valor ? valor.en : '';
    $('.form_input_traducao_es', input).value = eObject && 'es' in valor ? valor.es : '';
};
