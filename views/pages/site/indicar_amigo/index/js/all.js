// @template "site"
const botaoEnviarIndicacao = document.querySelector('#botao_indicar_enviar');
const dadoNome = document.querySelector('#input_nome');
const dadoEmail = document.querySelector('#input_email');
const dadoTelefone = document.querySelector('#input_telefone');

botaoEnviarIndicacao.addEventListener('click', async () => {
    const resposta = await ajaxPost(
        LINK + '/indicar-amigo/',
        {
            nome: dadoNome.value,
            email: dadoEmail.value,
            telefone: dadoTelefone.value,
        },
        'Não foi possível indicar o amigo, tente novamente mais tarde.'
    );
    if (false === resposta) {
        return;
    }
    Alerta.mensagem(
        'Indicação Realizada',
        'Seu amigo foi indicado com sucesso, em breve entraremos em contato pelo e-mail enviado',
        true
    );
});
