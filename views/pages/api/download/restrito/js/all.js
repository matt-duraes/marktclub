// @system "Loading"
// @system "Alerta"

window.addEventListener('load', () => {
    const LINK = document.getElementById('LINK').value;

    const blocoEnviarCodigo = document.getElementById('bloco_enviar_codigo');
    const blocoValidarCodigo = document.getElementById('bloco_validar_codigo');
    const blocoDownload = document.getElementById('bloco_download');

    const botaoEnviarCodigo = document.getElementById('botao_enviar_codigo');
    const botaoValidarCodigo = document.getElementById('botao_validar_codigo');
    const botaoReenviarCodigo = document.getElementById('botao_reenviar_codigo');
    const botaoDownload = document.getElementById('botao_download');

    const id = document.getElementById('input_id').value;
    const hashEmail = document.querySelector('#bloco_enviar_codigo input[name=form_system_hash]').value;
    const inputCodigo = document.getElementById('input_codigo');

    botaoEnviarCodigo.addEventListener('click', () => {
        enviarCodigoParaEmail();
    });
    botaoReenviarCodigo.addEventListener('click', () => {
        enviarCodigoParaEmail();
    });
    botaoValidarCodigo.addEventListener('click', () => {
        validarCodigo();
    });

    const enviarCodigoParaEmail = async () => {
        Loading.show();

        const body = new FormData();
        body.append('id', id);
        body.append('form_system_hash', hashEmail);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/download-restrito/email', {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await response.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();

        if (response.status != 201) {
            Alerta.notificacao(
                json.erro != undefined && json.erro.mensagem != undefined
                    ? json.erro.mensagem
                    : 'Erro ao enviar o código para seu e-mail.',
                false
            );
            return;
        }
        blocoEnviarCodigo.classList.add('hide');
        blocoValidarCodigo.classList.remove('hide');
        setTimeout(() => {
            inputCodigo.focus();
        }, 50);
    };
    const validarCodigo = async () => {
        Loading.show();

        const body = new FormData();
        body.append('id', id);
        body.append('codigo', inputCodigo.value);

        const response = await fetch(LINK + '/download-restrito/validar', {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await response.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();

        if (json.status != 'sucesso') {
            Alerta.notificacao(
                json.erro != undefined && json.erro.mensagem != undefined
                    ? json.erro.mensagem
                    : 'Erro ao validar seu código.',
                false
            );
            return;
        }

        botaoDownload.setAttribute('href', LINK + '/download-restrito/download/' + id + '/' + json.dado.codigo);

        blocoValidarCodigo.classList.add('hide');
        blocoDownload.classList.remove('hide');
        inputCodigo.value = '';
    };

    botaoDownload.addEventListener('click', () => {
        setTimeout(() => {
            botaoDownload.removeAttribute('href');
            blocoEnviarCodigo.classList.remove('hide');
            blocoDownload.classList.add('hide');
        }, 100);
    });
});
