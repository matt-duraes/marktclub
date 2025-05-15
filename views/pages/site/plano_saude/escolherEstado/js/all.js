// @template "site"
// @system "Form"
// @system "Loading"
window.addEventListener('load', function () {
    const inputEstado = $('#input_estado');
    const inputCidade = $('#input_cidade');
    const blocoCidade = inputCidade.closest('.input_select');
    const botao = $('#botao_escolher_estado');

    inputEstado.evento('formChange', async () => {
        blocoCidade.sumir();
        botao.sumir();

        const estado = inputEstado.valor();
        if (estado === '') {
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(LINK + '/saude/escolher-cidade', {estado: estado});
        Loading.hide();
        if (false === resposta) {
            return;
        }
        const cidade = resposta.dado.cidade;
        if (cidade.length === 0) {
            botao.aparecer();
            return;
        }
        blocoCidade.aparecer();
        formSelectOption(inputCidade, cidade);
    });

    inputCidade.evento('formChange', () => {
        const cidade = inputCidade.valor();
        if (cidade === '') {
            botao.sumir();
            return;
        }
        botao.aparecer();
    });

    botao.evento('click', (e) => {
        e.preventDefault();
        Loading.show();
        const estado = inputEstado.valor();
        const cidade = inputCidade.valor();
        window.location.assign(`${LINK}/saude?estado=${estado}&cidade=${encodeURIComponent(cidade)}`);
    });
});
