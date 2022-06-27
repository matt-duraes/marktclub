// @template "documentacao"

window.addEventListener('load', () => {
    /*
    |--------------------------------------------------------------------------
    | RESETAR SECRET ID
    |--------------------------------------------------------------------------
    */
    const botaoResetarSecretIdLista = document.querySelectorAll('.botao_resetar_secret_id');
    const blocoResetarSecretId = document.querySelector('#bloco_resetar_secret_id');
    const botaoResetarSecretId = document.querySelector('#botao_resetar_secret_id');
    const inputResetarSecretIdSenha = document.querySelector('#input_resetar_secret_id_senha');

    let idResetarSecretId;
    botaoResetarSecretIdLista.forEach(botao => {
        botao.addEventListener('click', () => {
            idResetarSecretId = botao.closest('.app').getAttribute('data-id');

            blocoResetarSecretId.classList.add('display_flex');
            setTimeout(() => {
                blocoResetarSecretId.classList.add('abrir');
                inputResetarSecretIdSenha.focus();
            }, 20);
        });
    });

    const linkResetarSecretId = document.querySelector('#form_resetar_secret_id').getAttribute('action');
    const hashResetarSecretId = document.querySelector('#form_resetar_secret_id input[name=form_system_hash]').value;

    inputResetarSecretIdSenha.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            resetarSecretId();
        }
    });
    botaoResetarSecretId.addEventListener('click', () => {
        resetarSecretId();
    });
    const resetarSecretId = async () => {
        if (inputResetarSecretIdSenha.value == '') {
            Alerta.notificacao('Digite sua senha atual para continuar.', false);
            return;
        }

        Loading.show();
        const body = new FormData();
        body.append('senha', inputResetarSecretIdSenha.value);
        body.append('id', idResetarSecretId);
        body.append('form_system_hash', hashResetarSecretId);
        body.append('form_system_validacao', '');

        const resposta = await fetch(linkResetarSecretId, {
            method: 'POST',
            body,
        });

        if (resposta.status == 201) {
            window.location.reload();
            return;
        }

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();
        Alerta.notificacao(
            json.erro.mensagem != undefined ? json.erro.mensagem : 'Erro ao tentar pegar o secret id.',
            false
        );
    };

    /*
    |--------------------------------------------------------------------------
    | RESETAR CHAVE PÚBLICA
    |--------------------------------------------------------------------------
    */
    const botaoResetarChavePublicaLista = document.querySelectorAll('.botao_resetar_chave_publica');
    const blocoResetarChavePublica = document.querySelector('#bloco_resetar_chave_publica');
    const botaoResetarChavePublica = document.querySelector('#botao_resetar_chave_publica');
    const inputResetarChavePublicaSenha = document.querySelector('#input_resetar_chave_publica_senha');

    let idResetarChavePublica;
    botaoResetarChavePublicaLista.forEach(botao => {
        botao.addEventListener('click', () => {
            idResetarChavePublica = botao.closest('.app').getAttribute('data-id');

            blocoResetarChavePublica.classList.add('display_flex');
            setTimeout(() => {
                blocoResetarChavePublica.classList.add('abrir');
                inputResetarChavePublicaSenha.focus();
            }, 20);
        });
    });

    const linkResetarChavePublica = document.querySelector('#form_resetar_chave_publica').getAttribute('action');
    const hashResetarChavePublica = document.querySelector(
        '#form_resetar_chave_publica input[name=form_system_hash]'
    ).value;

    inputResetarChavePublicaSenha.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            resetarChavePublica();
        }
    });
    botaoResetarChavePublica.addEventListener('click', () => {
        resetarChavePublica();
    });
    const resetarChavePublica = async () => {
        if (inputResetarChavePublicaSenha.value == '') {
            Alerta.notificacao('Digite sua senha atual para continuar.', false);
            return;
        }

        Loading.show();
        const body = new FormData();
        body.append('senha', inputResetarChavePublicaSenha.value);
        body.append('id', idResetarChavePublica);
        body.append('form_system_hash', hashResetarChavePublica);
        body.append('form_system_validacao', '');

        const resposta = await fetch(linkResetarChavePublica, {
            method: 'POST',
            body,
        });

        if (resposta.status == 201) {
            window.location.reload();
            return;
        }

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();
        Alerta.notificacao(
            json.erro.mensagem != undefined ? json.erro.mensagem : 'Erro ao tentar resetar sua chave pública.',
            false
        );
    };

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR SECRET ID
    |--------------------------------------------------------------------------
    */
    const botaoMostrarSecretIdLista = document.querySelectorAll('.botao_mostrar_secret_id');
    const blocoMostrarSecretId = document.querySelector('#bloco_mostrar_secret_id');
    const botaoMostrarSecretId = document.querySelector('#botao_mostrar_secret_id');
    const inputSecretIdSenha = document.querySelector('#input_secret_id_senha');

    let ItemSecretId, idSecretId, tipoSecretId;
    botaoMostrarSecretIdLista.forEach(botao => {
        botao.addEventListener('click', () => {
            ItemSecretId = botao.closest('.item');
            idSecretId = botao.closest('.app').getAttribute('data-id');
            tipoSecretId = 'producao';
            if (botao.classList.contains('homologacao')) {
                tipoSecretId = 'homologacao';
            }

            blocoMostrarSecretId.classList.add('display_flex');
            setTimeout(() => {
                blocoMostrarSecretId.classList.add('abrir');
                inputSecretIdSenha.focus();
            }, 20);
        });
    });

    const linkMudarSecretId = document.querySelector('#form_mostrar_secret_id').getAttribute('action');
    const hashMudarSecretId = document.querySelector('#form_mostrar_secret_id input[name=form_system_hash]').value;

    inputSecretIdSenha.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            mostrarSecretId();
        }
    });
    botaoMostrarSecretId.addEventListener('click', () => {
        mostrarSecretId();
    });
    const mostrarSecretId = async () => {
        if (inputSecretIdSenha.value == '') {
            Alerta.notificacao('Digite sua senha atual para continuar.', false);
            return;
        }

        Loading.show();
        const body = new FormData();
        body.append('senha', inputSecretIdSenha.value);
        body.append('id', idSecretId);
        body.append('tipo', tipoSecretId);
        body.append('form_system_hash', hashMudarSecretId);
        body.append('form_system_validacao', '');

        const resposta = await fetch(linkMudarSecretId, {
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

        if (json.status == 'sucesso') {
            popupFechar(blocoMostrarSecretId);
            ItemSecretId.querySelector('pre').innerHTML = json.dado.secret_id;
            ItemSecretId.classList.add('copiar');
            return;
        }
        Alerta.notificacao(
            json.erro.mensagem != undefined ? json.erro.mensagem : 'Erro ao tentar pegar o secret id.',
            false
        );
    };

    /*
    |--------------------------------------------------------------------------
    | MOSTRAR CHAVE PÚBLICA
    |--------------------------------------------------------------------------
    */
    const botaoMostrarChavePublicaLista = document.querySelectorAll('.botao_mostrar_chave_publica');
    const blocoMostrarChavePublica = document.querySelector('#bloco_mostrar_chave_publica');
    const botaoMostrarChavePublica = document.querySelector('#botao_mostrar_chave_publica');
    const inputChavePublicaSenha = document.querySelector('#input_chave_publica_senha');

    let ItemChavePublica, idChavePublica, tipoChavePublica;
    botaoMostrarChavePublicaLista.forEach(botao => {
        botao.addEventListener('click', () => {
            ItemChavePublica = botao.closest('.item');
            idChavePublica = botao.closest('.app').getAttribute('data-id');

            tipoChavePublica = 'producao';
            if (botao.classList.contains('homologacao')) {
                tipoChavePublica = 'homologacao';
            }

            blocoMostrarChavePublica.classList.add('display_flex');
            setTimeout(() => {
                blocoMostrarChavePublica.classList.add('abrir');
                inputChavePublicaSenha.focus();
            }, 20);
        });
    });

    const linkMudarChavePublica = document.querySelector('#form_mostrar_chave_publica').getAttribute('action');
    const hashMudarChavePublica = document.querySelector(
        '#form_mostrar_chave_publica input[name=form_system_hash]'
    ).value;

    inputChavePublicaSenha.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            mostrarChavePublica();
        }
    });
    botaoMostrarChavePublica.addEventListener('click', () => {
        mostrarChavePublica();
    });
    const mostrarChavePublica = async () => {
        if (inputChavePublicaSenha.value == '') {
            Alerta.notificacao('Digite sua senha atual para continuar.', false);
            return;
        }

        Loading.show();
        const body = new FormData();
        body.append('senha', inputChavePublicaSenha.value);
        body.append('id', idChavePublica);
        body.append('tipo', tipoChavePublica);
        body.append('form_system_hash', hashMudarChavePublica);
        body.append('form_system_validacao', '');

        const resposta = await fetch(linkMudarChavePublica, {
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

        if (json.status == 'sucesso') {
            popupFechar(blocoMostrarChavePublica);
            ItemChavePublica.querySelector('pre').innerHTML = json.dado.chave_publica;
            ItemChavePublica.classList.add('copiar');
            return;
        }
        Alerta.notificacao(
            json.erro.mensagem != undefined ? json.erro.mensagem : 'Erro ao tentar pegar sua chave pública.',
            false
        );
    };

    /*
    |--------------------------------------------------------------------------
    | GERAL
    |--------------------------------------------------------------------------
    */
    const copiarLista = document.querySelectorAll('#app .app .botao_copiar');
    copiarLista.forEach(botao => {
        botao.addEventListener('click', () => {
            const pre = botao.closest('.item').querySelector('pre');
            navigator.clipboard.writeText(pre.innerText);
            Alerta.notificacao('Código copiado com sucesso!', true);
        });
    });

    const popupFechar = bloco => {
        bloco.classList.remove('abrir');
        setTimeout(() => {
            tipoSecretId = '';
            tipoChavePublica = '';
            inputSecretIdSenha.value = '';
            inputChavePublicaSenha.value = '';
            bloco.classList.remove('display_flex');
        }, 300);
    };
});
