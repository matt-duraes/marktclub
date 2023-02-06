// @system "Loading"
// @system "Form"
// @system "Funcao"
// @system "Loading"
// @system "Alerta"

window.addEventListener('load', () => {
    const LINK = document.getElementById('LINK').value;
    const download = document.getElementById('input_download').value;
    const inputSenha = document.getElementById('input_senha');
    const botaoSenha = document.getElementById('botao_validar_senha');

    setTimeout(() => {
        inputSenha.value = '';
    }, 100);

    botaoSenha.addEventListener('click', e => {
        enviarValidacao();
    });
    inputSenha.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            enviarValidacao();
        }
    });

    const enviarValidacao = async () => {
        Loading.show();

        const body = new FormData();
        body.append('senha', inputSenha.value);

        const resposta = await fetch(LINK + '/download-privado/' + download, {
            method: 'POST',
            body,
        });

        const json = await respostaJson(
            resposta,
            'Erro ao validar senha ou o arquivo não é mais válido, por favor, tente novamente.',
            false
        );

        Loading.hide();
        if (false === json) {
            return;
        }

        window.location.assign(LINK + '/download-privado/download/' + json.dado.id);
    };
});
