// @template "painel"

window.addEventListener('load', () => {
    const botaoSalvar = document.getElementById('botao_salvar_geral');

    const inputPerfil = document.querySelector('#bloco_app_add input[name=perfil]');
    const inputNome = document.querySelector('#bloco_app_add input[name=nome]');
    const inputData = document.querySelector('#bloco_app_add input[name=data_nascimento]');
    const inputGenero = document.querySelector('#bloco_app_add input[name=genero]');
    const inputEmailPessoal = document.querySelector('#bloco_app_add input[name=email_pessoal]');
    const inputTelefoneTrabalho = document.querySelector('#bloco_app_add input[name=telefone_trabalho]');
    const inputTelefonePessoal = document.querySelector('#bloco_app_add input[name=telefone_pessoal]');

    const validarPerfil = () => {
        const retorno = /^[a-z]{1,}[a-z0-9\.]{0,}[a-z0-9]{1,}$/.test(inputPerfil.value);
        if (!retorno) {
            Alerta.notificacao(
                `
                    O perfil deve conter apenas letras minúsculas (a-z), ponto (.) e não pode
                    começar ou terminar com ponto (.) e ter dois pontos (..) seguidos.
                `,
                false
            );
        }
        return retorno;
    };

    inputPerfil.addEventListener('keyup', () => {
        inputPerfil.value = inputPerfil.value
            .toLowerCase()
            .trim()
            .replace(/\.{2,}/g, '.');
    });
    inputPerfil.addEventListener('keydown', e => {
        if (e.key.length == 1 && !/^[a-z0-9\.]$/.test(e.key)) {
            e.preventDefault();
            return;
        }
    });
    inputPerfil.addEventListener('change', validarPerfil);

    const listaInput = document.querySelectorAll('#bloco_app_add input');
    const blocoSelect = document.getElementById('bloco_fw_select');
    listaInput.forEach(input => {
        input.addEventListener('keydown', e => {
            if (
                input.classList.contains('input_select_texto') &&
                window.getComputedStyle(blocoSelect).getPropertyValue('display') == 'block'
            ) {
                return;
            }
            if (e.key == 'Enter') {
                e.preventDefault();
                acaoParaAtualizarDado();
            }
        });
    });

    botaoSalvar.addEventListener('click', () => {
        acaoParaAtualizarDado();
    });
    const acaoParaAtualizarDado = async () => {
        if (botaoSalvar.classList.contains('aguarde') || !validarPerfil()) {
            return;
        }

        botaoSalvar.classList.add('aguarde');

        let body = new FormData();
        body.append('perfil', inputPerfil.value.replace(/\.{1,}$/, ''));
        body.append('nome', inputNome.value);
        body.append('data_nascimento', inputData.value);
        body.append('genero', inputGenero.value);
        body.append('email_pessoal', inputEmailPessoal.value);
        body.append('telefone_trabalho', inputTelefoneTrabalho.value);
        body.append('telefone_pessoal', inputTelefonePessoal.value);

        const resposta = await fetch(LINK + '/perfil/dado', {
            method: 'POST',
            body,
        });

        botaoSalvar.classList.remove('aguarde');
        if (resposta.status == 204) {
            Alerta.notificacao('Dados alterados com sucesso!', true);
            return;
        }

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Alerta.notificacao(
            json.erro != undefined && json.erro.mensagem != undefined
                ? json.erro.mensagem
                : 'Ocorreu um erro ao atualizar seus dados, por favor, tente novamente.',
            false
        );
    };
});
