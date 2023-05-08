// @template "painel"

window.addEventListener('load', () => {
    const botao = document.getElementById('botao_mudar_empresa');
    const inputEmpresa = document.getElementById('input_mudar_empresa');
    const hash = document.querySelector('#input_mudar_empresa_hash').value;

    botao.addEventListener('click', async () => {
        if (inputEmpresa.value == '') {
            Alerta.notificacao('Escolha uma empresa para continuar.', false);
            return;
        }
        Loading.show();
        const body = new FormData();
        body.append('empresa', inputEmpresa.value);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');

        const resposta = await fetch(LINK + '/perfil/empresa', {
            method: 'POST',
            body,
        });
        const json = await respostaJson(resposta, 'Ocorre um erro ao mudar a empresa, por favor, tente novamente.');
        if (false === json) {
            Loading.hide();
            return;
        }
        window.location.assign(LINK + '/sair');
    });
});
