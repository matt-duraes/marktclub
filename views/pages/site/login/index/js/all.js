// @template "login"
// @system "Loading"
// @system "Alerta"
// @system "Mascara"
// @system "Form"

window.addEventListener('load', () => {
    const inputLogin = $('#input_login');
    const inputSenha = $('#input_senha');
    const botaoLogin = $('#botao_fazer_login');

    botaoLogin.addEventListener('click', async () => {
        const login = inputLogin.value;
        const senha = inputSenha.value;
        const resposta = await ajaxPost(LINK + '/login', { login, senha }, 'Erro ao fazer o login, tente novamente.');
        if (false === resposta) {
            return;
        }
        window.location.assign(resposta.dado.link);
    });
});
