// @template "site"
// @system "Loading"
// @system "Alerta"

window.addEventListener('load', () => {
    const formulario = document.getElementById('formulario_indica_automovel');

    const botaoEnviarIndicacao = document.querySelector('#botao_enviar_automovel');
    botaoEnviarIndicacao.addEventListener('click', async e => {
        e.preventDefault();

        const cidade = formulario.querySelector('input[name=cidade]');
        const modelo = formulario.querySelector('input[name=modelo]');
        const versao = formulario.querySelector('input[name=versao]');
        const cor = formulario.querySelector('input[name=cor]');
        const veiculo = formulario.querySelector('input[name=veiculo]');
        const mensagem = formulario.querySelector('textarea[name=mensagem]');

        if (validarDadosDoForm(veiculo, modelo, versao, cor, cidade, mensagem)) {
            return false;
        }

        const body = new FormData();
        body.append('veiculo', veiculo.value);
        body.append('modelo', modelo.value);
        body.append('versao', versao.value);
        body.append('cor', cor.value);
        body.append('cidade', cidade.value);
        body.append('mensagem', mensagem);

        Loading.show();

        const resposta = await fetch('/automovel-indicacao', {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();

        if (resposta.status === 201) {
            Alerta.notificacao(`Em breve entraremos em contato!`, true);
            setTimeout(() => {
                window.location.reload();
            }, 2000);
            return;
        }

        Alerta.notificacao(
            json.erro.mensagem !== undefined
                ? json.erro.mensagem
                : 'Ocorreu um erro ao solicitar, por favor, tente novamente.',
            false
        );
    });

    const validarDadosDoForm = (veiculo, modelo, versao, cor, cidade, mensagem) => {
        if (!veiculo.value) {
            Alerta.notificacao('Digite o nome do veiculo que deseja solicitar', false);
            return true;
        }
        if (!modelo.value) {
            Alerta.notificacao('Digite o telefone do veiculo que deseja solicitar', false);
            return true;
        }
        if (!versao.value) {
            Alerta.notificacao('Digite o email do veiculo que deseja solicitar', false);
            return true;
        }
        if (!cor.value) {
            Alerta.notificacao('Digite o cor do veiculo que deseja solicitar', false);
            return true;
        }
        if (!cidade.value) {
            Alerta.notificacao('Digite o cidade do parceiro que deseja solicitar', false);
            return true;
        }
        if (!mensagem.value) {
            Alerta.notificacao('Digite uma mensagem', false);
            return true;
        }
    };
});
