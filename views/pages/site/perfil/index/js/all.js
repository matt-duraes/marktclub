// @import "form"
// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Form"
// @system "Loading"
// @system "Mascara"

window.addEventListener('load', () => {
    const googleAppId = document.getElementById('GOOGLE_CLIENT_ID').value;

    const botaoSalvar = document.getElementById('botao_salvar_dados_perfil');

    const inputNome = document.querySelector('#bloco_pagina_perfil form input[name=nome]');
    const inputData = document.querySelector('#bloco_pagina_perfil form input[name=data_nascimento]');
    const inputGenero = document.querySelector('#bloco_pagina_perfil form input[name=genero]');
    const inputEstadoCivil = document.querySelector('#bloco_pagina_perfil form input[name=estado_civil]');
    const inputEmailPessoal = document.querySelector('#bloco_pagina_perfil form input[name=email_pessoal]');
    const inputEmailTrabalho = document.querySelector('#bloco_pagina_perfil form input[name=email_trabalho]');
    const inputTelefoneTrabalho = document.querySelector('#bloco_pagina_perfil form input[name=telefone_trabalho]');
    const inputTelefonePessoal = document.querySelector('#bloco_pagina_perfil form input[name=telefone_pessoal]');
    const inputEstado = document.querySelector('#bloco_pagina_perfil form input[name=estado]');
    const inputCep = document.querySelector('#bloco_pagina_perfil form input[name=cep]');
    const inputLogradouro = document.querySelector('#bloco_pagina_perfil form input[name=logradouro]');
    const inputBairro = document.querySelector('#bloco_pagina_perfil form input[name=bairro]');
    const inputNumero = document.querySelector('#bloco_pagina_perfil form input[name=numero]');
    const inputComplemento = document.querySelector('#bloco_pagina_perfil form input[name=complemento]');
    const inputCidade = document.querySelector('#bloco_pagina_perfil form input[name=cidade]');
    const fotoPerfil = document.querySelector('#imagem_fundo_perfil');
    const blocoPerfil = document.querySelector('#bloco_perfil figure');

    buscarEnderecoPeloCep(inputCep, inputLogradouro, inputNumero, inputBairro, inputCidade, inputEstado, true);
    const buscarCidade = () => {
        buscarCidadePeloEstado(inputCidade, inputEstado.value, inputCidade.value, 'Escolha uma cidade');
    };
    inputEstado.addEventListener('formChange', () => {
        buscarCidade();
    });
    if (inputEstado.value != '') {
        buscarCidade();
    }

    botaoSalvar.addEventListener('click', e => {
        e.preventDefault();
        acaoParaAtualizarDado();
    });

    const acaoParaAtualizarDado = async () => {
        let body = new FormData();
        body.append('nome', inputNome.value);
        body.append('data_nascimento', inputData.value);
        body.append('genero', inputGenero.value);
        body.append('estado_civil', inputEstadoCivil.value);
        body.append('email_pessoal', inputEmailPessoal.value);
        body.append('email_trabalho', inputEmailTrabalho.value);
        body.append('telefone_trabalho', inputTelefoneTrabalho.value);
        body.append('telefone_pessoal', inputTelefonePessoal.value);

        body.append('endereco_estado', inputEstado.value);
        body.append('endereco_cep', inputCep.value.replace(/[^0-9]/g, ''));
        body.append('endereco_logradouro', inputLogradouro.value);
        body.append('endereco_bairro', inputBairro.value);
        body.append('endereco_numero', inputNumero.value);
        body.append('endereco_complemento', inputComplemento.value);
        body.append('endereco_cidade', inputCidade.value);

        Loading.show();
        const resposta = await fetch(LINK + '/perfil/salvar-dados', {
            method: 'POST',
            body,
        });
        Loading.hide();

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

    /*
    |--------------------------------------------------------------------------
    | GOOGLE
    |--------------------------------------------------------------------------
    */
    document.getElementById('botao_vincular_google').addEventListener('click', async () => {
        oauth2Google('imagem');
    });

    const setarNovaImagem = imagem => {
        fotoPerfil.style.backgroundImage = `url(${imagem})`;
        blocoPerfil.style.backgroundImage = `url(${imagem})`;
    };

    const oauth2Google = acao => {
        const client = google.accounts.oauth2.initCodeClient({
            // eslint-disable-next-line camelcase
            client_id: googleAppId,
            scope: 'email profile',
            // eslint-disable-next-line camelcase
            ux_mode: 'popup',
            callback: response => {
                vincularContaSocial(response.code);
            },
        });
        client.requestCode();
    };

    /*
    |--------------------------------------------------------------------------
    | VINCULAR REDE SOCIAL
    |--------------------------------------------------------------------------
    */
    const vincularContaSocial = async code => {
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/perfil/vincular-google',
            {
                code,
            },
            'Erro ao vincular imagem, por favor, tente novamente.'
        );

        Loading.hide();

        if (false === resposta) {
            return;
        }
        setarNovaImagem(resposta.dado.imagem);
        Alerta.notificacao('Foto vinculada com sucesso!', true);
        return;
    };
});
