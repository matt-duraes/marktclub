// @system "Form"
// @system "Alerta"
// @system "Loading"

window.addEventListener('load', () => {
    /*
    |--------------------------------------------------------------------------
    | CONTROLAR MENU
    |--------------------------------------------------------------------------
    */
    const botaoMenu = document.querySelector('#menu_mobile');
    const blocoMenu = document.querySelector('#bloco_menu');

    botaoMenu.addEventListener('click', () => {
        blocoMenu.classList.add('display_block');
        setTimeout(() => {
            blocoMenu.classList.add('aberto');
        }, 20);
    });

    blocoMenu.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_menu') {
            fecharMenu();
        }
    });
    const fecharMenu = () => {
        blocoMenu.classList.remove('aberto');
        setTimeout(() => {
            blocoMenu.classList.remove('display_block');
        }, 300);
    };

    /*
    |--------------------------------------------------------------------------
    | POPUP
    |--------------------------------------------------------------------------
    */
    const botaoPopupCancelar = document.querySelectorAll('.bloco_popup');
    botaoPopupCancelar.forEach(bloco => {
        bloco.addEventListener('click', e => {
            if (e.target.classList.contains('bloco_popup')) {
                fecharPopup(bloco);
            }
        });
        bloco.querySelector('.cancelar').addEventListener('click', () => {
            fecharPopup(bloco);
        });
    });

    const inputSecretIdSenha = document.querySelector('#input_secret_id_senha');
    const inputChavePublicaSenha = document.querySelector('#input_chave_publica_senha');
    const fecharPopup = bloco => {
        bloco.classList.remove('abrir');
        setTimeout(() => {
            bloco.classList.remove('display_flex');

            if (inputSenhaAtual) {
                inputSenhaAtual.value = '';
            }
            if (inputSenhaNova) {
                inputSenhaNova.value = '';
            }
            if (inputSenhaRepetir) {
                inputSenhaRepetir.value = '';
            }
            if (inputSecretIdSenha) {
                inputSecretIdSenha.value = '';
            }
            if (inputChavePublicaSenha) {
                inputChavePublicaSenha.value = '';
            }
        }, 300);
        limparSenha();
    };

    /*
    |--------------------------------------------------------------------------
    | SENHA
    |--------------------------------------------------------------------------
    */
    const botaoSenha = document.querySelector('#botao_mudar_senha');
    const blocoMudarSenha = document.querySelector('#bloco_mudar_senha');
    botaoSenha.addEventListener('click', () => {
        fecharMenu();
        blocoMudarSenha.classList.add('display_flex');
        setTimeout(() => {
            inputSenhaAtual.focus();
            blocoMudarSenha.classList.add('abrir');
        }, 20);
    });

    const botaoSalvarSenha = document.querySelector('#botao_salvar_senha');
    const hash = document.querySelector('#nova_senha_hash').value;
    const inputSenhaAtual = document.querySelector('#input_senha_atual');
    const inputSenhaNova = document.querySelector('#input_senha_nova');
    const inputSenhaRepetir = document.querySelector('#input_senha_repetir');
    const senhaLink = document.querySelector('#form_mudar_senha').getAttribute('action');

    const limparSenha = () => {
        inputSenhaAtual.value = '';
        inputSenhaNova.value = '';
        inputSenhaRepetir.value = '';
    };

    inputSenhaAtual.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            salvarNovaSenha();
        }
    });
    inputSenhaNova.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            salvarNovaSenha();
        }
    });
    inputSenhaRepetir.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            salvarNovaSenha();
        }
    });
    botaoSalvarSenha.addEventListener('click', () => {
        salvarNovaSenha();
    });

    const salvarNovaSenha = async () => {
        const senhaAtual = inputSenhaAtual.value;
        const senhaNova = inputSenhaNova.value;
        const senhaRepetir = inputSenhaRepetir.value;

        if (senhaAtual == '') {
            Alerta.notificacao('Digite sua senha atual para continuar.', false);
            return;
        } else if (senhaNova == '') {
            Alerta.notificacao('Digite sua nova senha para continuar.', false);
            return;
        } else if (senhaNova != senhaRepetir) {
            Alerta.notificacao('Sua nova senha e o campo repetir senha não estão iguais.', false);
            return;
        }

        Loading.show();
        const body = new FormData();
        body.append('senha_atual', senhaAtual);
        body.append('senha_nova', senhaNova);
        body.append('senha_repetir', senhaRepetir);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');

        const resposta = await fetch(senhaLink, {
            method: 'POST',
            body,
        });

        Loading.hide();

        if (resposta.status == 204) {
            fecharPopup(blocoMudarSenha);
            Alerta.notificacao('Senha alterada com sucesso!', true);
            return;
        }

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Alerta.notificacao(
            json.erro.mensagem != undefined
                ? json.erro.mensagem
                : 'Ocorreu um erro ao tentar mudar sua senha, por favor, tente novamente.',
            false
        );
    };
});
