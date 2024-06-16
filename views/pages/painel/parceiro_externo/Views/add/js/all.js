// @template "painel"
// @system "Form"
// @system "Galeria"
// @system "Mascara"
// @painel "app_geral_add"

window.addEventListener('load', () => {
    const inputCidade = $('#input_endereco_cidade');
    const inputEstado = $('#input_endereco_estado');
    buscarEnderecoPeloCep(
        $('#input_endereco_cep'),
        $('#input_endereco_logradouro'),
        $('#input_endereco_numero'),
        $('#input_endereco_bairro'),
        inputCidade,
        inputEstado
    );

    inputEstado.evento('formChange', () => {
        buscarCidadePeloEstado(inputCidade, inputEstado.valor(), '', 'Escolha uma cidade');
    });
});
