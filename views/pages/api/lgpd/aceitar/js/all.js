// @system "Alerta"
// @system "Loading"

window.addEventListener('load', () => {
    const inputTermo = document.querySelector('#input_termo');
    const inputHash = document.querySelector('#input_hash').value;
    const botaoAceitarTermo = document.querySelector('#botao_aceitar_termo');

    inputTermo.addEventListener('change', () => {
        botaoAceitarTermo.classList.toggle('inativo');
    });

    botaoAceitarTermo.addEventListener('click', async () => {
        if (botaoAceitarTermo.classList.contains('inativo')) {
            Alerta.notificacao('Marque o box de aceite dos termos para continuar.', false);
            return;
        }

        Loading.show();

        const body = new FormData();
        body.append('hash', inputHash);
        body.append('termo', inputTermo.checked ? 'sim' : 'nao');

        const resposta = await fetch(LINK + '/termo-lgpd', {
            body,
            method: 'POST',
        });

        const json = await respostaJson(resposta, 'Ocorreu um erro ao aceitar os termos, por favor, tente novamente.');
        if (false === json) {
            Loading.hide();
            return;
        }

        window.location.assign(json.dado.link);
    });
});
