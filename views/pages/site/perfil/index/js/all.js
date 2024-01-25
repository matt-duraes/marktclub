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
    const inputBlocoFoto = document.querySelector('#input_foto_perfil');
    const iconeEditarFoto = document.querySelector('.botao_editar_foto');

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
        body.append('foto_perfil', inputBlocoFoto.value);
        Loading.show();

        const resposta = await fetch(LINK + '/perfil/salvar-dados', {
            method: 'POST',
            body,
        });
        Loading.hide();

        if (resposta.status == 204) {
            Alerta.notificacao('Dados alterados com sucesso!', true);

            const h1Nome = document.querySelector('.legenda h1');
            const pEmail = document.querySelector('.legenda p');
            const divNome = document.querySelector('#bloco_perfil .nome');
            const divEmail = document.querySelector('#bloco_perfil .email');

            const email = inputEmailPessoal.value != '' ? inputEmailPessoal.value : inputEmailTrabalho.value;

            h1Nome.innerHTML = inputNome.value;
            pEmail.innerHTML = email;
            divNome.innerHTML = inputNome.value;
            divEmail.innerHTML = email;

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
    | ALTERAR FOTO
    |--------------------------------------------------------------------------
    */
    const setarNovaFoto = imagem => {
        fotoPerfil.style.backgroundImage = `url(${imagem})`;
        blocoPerfil.style.backgroundImage = `url(${imagem})`;
    };

    const salvarFoto = async imagem => {
        Loading.show();

        let body = new FormData();
        body.append('imagem', imagem);

        const resposta = await fetch(LINK + '/perfil/vincular-google', {
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
        if (resposta.status != 204) {
            Alerta.notificacao(json.erro.mensagem != undefined ? json.erro.mensagem : 'Erro ao salvar imagem.', false);
            return;
        }

        Alerta.notificacao('Foto Alterada com sucesso!', true);
    };
    const arquivoInput = document.querySelector('#fileInput');

    arquivoInput.addEventListener('change', e => {
        const inputTarget = e.target;
        const arquivo = inputTarget.files[0];

        if (!arquivo) {
            Alerta.mensagem('Erro', 'Não é possível salvar foto', false);
            return;
        }
        const leitor = new FileReader();
        leitor.addEventListener('load', e => {
            const leitorTarget = e.target.result;
            setarNovaFoto(leitorTarget);
            salvarFoto(arquivo);
        });
        leitor.readAsDataURL(arquivo);
    });

    iconeEditarFoto.addEventListener('click', () => {
        arquivoInput.click();
    });
});
