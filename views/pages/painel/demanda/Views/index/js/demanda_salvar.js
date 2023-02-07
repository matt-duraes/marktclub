const demandaSalvar = () => {
    const inputTipo = document.getElementById('input_tipo');
    const inputTitulo = document.getElementById('input_titulo');
    const inputTexto = document.getElementById('input_texto');

    // Cliente
    const inputEmpresaCliente = document.getElementById('input_empresa_cliente');
    const inputDominioTipo = document.getElementById('input_dominio_tipo');
    const inputDominioSub = document.getElementById('input_dominio_sub');
    const inputDominioProprio = document.getElementById('input_dominio_proprio');
    const inputConfigurarCdn = document.getElementById('input_configurar_cdn');
    const inputLoginApi = document.getElementById('input_login_api');
    const inputDominioLogin = document.getElementById('input_dominio_login');
    const inputApp = document.getElementById('input_app');
    const inputWebView = document.getElementById('input_webview');
    // Bub
    const inputBugLocal = document.getElementById('input_bug_local');
    const inputEmpresaBug = document.getElementById('input_empresa_bug');
    const inputEmpresaEspecifica = document.getElementById('input_empresa_especifica');
    const inputBugCritico = document.getElementById('input_bug_critico');
    // Outro
    const inputEmpresaOutro = document.getElementById('input_empresa_outro');
    // Associacao
    const inputEmpresaAssociacao = document.getElementById('input_empresa_associacao');
    const inputDominioSite = document.getElementById('input_dominio_site');
    const inputSocialFacebook = document.getElementById('input_social_facebook');
    const inputSocialInstagram = document.getElementById('input_social_instagram');
    const inputSocialTwitter = document.getElementById('input_social_twitter');
    const inputEmail = document.getElementById('input_email');
    const inputTelefone = document.getElementById('input_telefone');
    const inputEndereco = document.getElementById('input_endereco');

    const botaoSalvar = document.getElementById('botao_salvar_demanda');

    /*
    |--------------------------------------------------------------------------
    | ESCOLHER TIPO
    |--------------------------------------------------------------------------
    */
    const blocoEscolherTipo = document.getElementById('bloco_tipo_demanda');
    const botaoTipo = blocoEscolherTipo.querySelectorAll('.botao');
    botaoTipo.forEach(botao => {
        botao.addEventListener('click', () => {
            const tipo = botao.getAttribute('data-tipo');
            mudarTipoDemanda(tipo);
        });
    });

    const blocoTipoAssociacao = document.getElementById('bloco_tipo_associacao');
    const blocoTipoCliente = document.getElementById('bloco_tipo_cliente');
    const blocoTipoBug = document.getElementById('bloco_tipo_bug');
    const blocoTipoOutro = document.getElementById('bloco_tipo_outro');
    const blocoHeader = document.getElementById('bloco_geral_header');
    const blocoFooter = document.getElementById('bloco_geral_footer');

    const mudarTipoDemanda = tipo => {
        inputTipo.value = tipo;

        blocoFooter.classList.remove('display_none');
        botaoSalvar.classList.remove('display_none');
        blocoEscolherTipo.classList.add('display_none');

        if (tipo == 'associacao') {
            blocoTipoAssociacao.classList.remove('display_none');
        } else if (tipo == 'cliente') {
            blocoTipoCliente.classList.remove('display_none');
        } else if (tipo == 'bug') {
            blocoHeader.classList.remove('display_none');
            blocoTipoBug.classList.remove('display_none');
        } else if (tipo == 'outro' || tipo == 'feature') {
            blocoHeader.classList.remove('display_none');
            blocoTipoOutro.classList.remove('display_none');
        }
    };

    /*
    |--------------------------------------------------------------------------
    | ACAO DE SELECT
    |--------------------------------------------------------------------------
    */
    formSelectChange = acao => {
        if (acao == 'mudarTipoDominio') {
            mudarTipoDominio();
        }
    };

    /*
    |--------------------------------------------------------------------------
    | HELPER DE AJUDA
    |--------------------------------------------------------------------------
    */
    const listaAjuda = document.querySelectorAll('#bloco_demanda_nova *[data-ajuda]');
    listaAjuda.forEach(bloco => {
        bloco.addEventListener('mouseover', () => {
            const texto = bloco.getAttribute('data-ajuda');
            Ajuda.show(bloco, texto);
        });
        bloco.addEventListener('mouseout', () => {
            Ajuda.hide();
        });
    });

    /*
    |--------------------------------------------------------------------------
    | FECHAR PÁGINA
    |--------------------------------------------------------------------------
    */
    document.querySelectorAll('#bloco_demanda_nova .botao_fechar').forEach(botao => {
        botao.addEventListener('click', () => {
            Pagina.staticFechar();
        });
    });

    /*
    |--------------------------------------------------------------------------
    | DOMINIO
    |--------------------------------------------------------------------------
    */
    const blocoDominioSub = document.getElementById('bloco_dominio_sub');
    const blocoDominioSubTexto = document.getElementById('bloco_dominio_sub_texto');
    const blocoDominioProprio = document.getElementById('bloco_dominio_proprio');
    const blocoObservacaoDominioProprio = document.getElementById('bloco_observacao_dominio_proprio');
    const blocoObservacaoSubDominioProprio = document.getElementById('bloco_observacao_sub_dominio_proprio');
    const blocoConfigurarCdn = document.getElementById('bloco_configurar_cdn');

    const mudarTipoDominio = () => {
        blocoDominioSub.classList.add('display_none');
        blocoDominioProprio.classList.add('display_none');
        blocoObservacaoDominioProprio.classList.add('display_none');
        blocoObservacaoSubDominioProprio.classList.add('display_none');
        blocoConfigurarCdn.classList.add('display_none');
        inputDominioProprio.value = '';
        inputDominioSub.value = '';
        inputConfigurarCdn.checked = false;

        const valor = inputDominioTipo.value;
        if (valor == '') {
            return;
        } else if (valor == 'dominio') {
            blocoDominioProprio.classList.remove('display_none');
            blocoObservacaoDominioProprio.classList.remove('display_none');
            blocoConfigurarCdn.classList.remove('display_none');
            inputDominioProprio.focus();
            return;
        } else if (valor == 'subdominio') {
            blocoDominioProprio.classList.remove('display_none');
            blocoObservacaoSubDominioProprio.classList.remove('display_none');
            inputDominioProprio.focus();
            return;
        }
        blocoDominioSub.classList.remove('display_none');
        blocoDominioSubTexto.innerText = '.' + valor + '.com.br';
        inputDominioSub.focus();
    };

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    const blocoDominioLogin = document.getElementById('bloco_dominio_login');
    inputLoginApi.addEventListener('change', () => {
        mostrarObservacaoApp();
        if (inputLoginApi.checked) {
            blocoDominioLogin.classList.remove('display_none');
            return;
        }
        blocoDominioLogin.classList.add('display_none');
        inputDominioLogin.value = '';
    });

    /*
    |--------------------------------------------------------------------------
    | APP
    |--------------------------------------------------------------------------
    */
    const blocoApp = document.getElementById('bloco_app');

    inputApp.addEventListener('click', () => {
        mostrarObservacaoApp();
    });
    const mostrarObservacaoApp = () => {
        if (inputApp.checked && inputLoginApi.checked) {
            blocoApp.classList.remove('display_none');
            return;
        }
        blocoApp.classList.add('display_none');
    };

    /*
    |--------------------------------------------------------------------------
    | EMPRESA ESPECIFICA
    |--------------------------------------------------------------------------
    */
    const blocoEmpresaEspecifica = document.getElementById('bloco_empresa_especifica');
    inputEmpresaEspecifica.addEventListener('change', () => {
        if (inputEmpresaEspecifica.checked) {
            blocoEmpresaEspecifica.classList.remove('display_none');
            return;
        }
        blocoEmpresaEspecifica.classList.add('display_none');
        selectValue(inputEmpresaBug, '');
    });

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    botaoSalvar.addEventListener('click', async () => {
        Loading.show();

        const tipo = inputTipo.value;
        let valido = false;
        let body;
        if (tipo == 'cliente') {
            valido = await validarDadoCliente();
            body = await montarDadoCliente();
        } else if (tipo == 'associacao') {
            valido = await validarDadoAssociacao();
            body = await montarDadoAssociacao();
        } else if (tipo == 'bug') {
            valido = await validarDadoBug();
            body = await montarDadoBug();
        } else if (tipo == 'outro' || tipo == 'feature') {
            valido = await validarDadoOutro();
            body = await montarDadoOutro();
        }

        if (!valido) {
            Loading.hide();
            return;
        }

        const resposta = await fetch(LINK + '/demanda/demanda-salvar', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Ocorreu um erro ao salvar, por favor, tente novamente!');
        if (false === json) {
            Loading.hide();
            return;
        }

        window.location.assign(LINK + '/demanda#demanda-' + json.dado.id);
        window.location.reload();
    });

    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA DE CLIENTE
    |--------------------------------------------------------------------------
    */
    const validarDadoCliente = () => {
        return new Promise(resolve => {
            let mensagem = '';
            if (inputEmpresaCliente.value == '') {
                mensagem = 'Escolha uma empresa para continuar.';
            } else if (inputDominioTipo.value == '') {
                mensagem = 'Escolha um tipo de domínio para continuar.';
            } else if (
                ((inputDominioTipo.value == 'dominio' || inputDominioTipo.value == 'subdominio') &&
                    inputDominioProprio.value == '') ||
                ((inputDominioTipo.value == 'temvantagens' || inputDominioTipo.value == 'temmaisvantagens') &&
                    inputDominioSub.value == '')
            ) {
                mensagem = 'Digite um domínio/subdomínio para o clube.';
            } else if (inputLoginApi.checked && inputDominioLogin.value == '') {
                mensagem = 'Digite o domínio de login do sistema do cliente.';
            } else if (document.querySelectorAll('#bloco_menu_clube input:checked').length == 0) {
                mensagem = 'Você deve escolher pelo menos um menu para o clube.';
            }
            if (mensagem != '') {
                Alerta.notificacao(mensagem, false);
                resolve(false);
            }
            resolve(true);
        });
    };

    const montarDadoCliente = () => {
        return new Promise(resolve => {
            let dominioLink = '';
            if (inputDominioTipo.value == 'dominio' || inputDominioTipo.value == 'subdominio') {
                dominioLink = inputDominioProprio.value;
            } else if (inputDominioTipo.value == 'temvantagens' || inputDominioTipo.value == 'temmaisvantagens') {
                dominioLink = inputDominioSub.value;
            }

            let texto = `
                <p>O clube deve ter os seguintes menus:</p>
                    <ul>
            `;
            document.querySelectorAll('#bloco_menu_clube input:checked').forEach(item => {
                texto += `<li>${item.value}</li>`;
            });
            texto += `</ul>`;
            if (inputWebView.checked) {
                texto += `<p>Deve tirar o botão de sair do Clube porque ele será usado apenas com WebView</p>`;
            }
            texto += inputTexto.value;

            const body = new FormData();
            body.append('tipo', inputTipo.value);
            body.append('empresa', inputEmpresaCliente.value);
            body.append('dominio_tipo', inputDominioTipo.value);
            body.append('dominio_link', dominioLink);
            body.append('login_api', inputLoginApi.value);
            body.append('login_link', inputDominioLogin.value);
            body.append('app', inputApp.value);
            body.append('cdn', inputConfigurarCdn.value);
            body.append('texto', texto);

            resolve(body);
        });
    };

    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA ASSOCIACAO
    |--------------------------------------------------------------------------
    */
    const validarDadoAssociacao = () => {
        return new Promise(resolve => {
            let mensagem = '';
            if (inputEmpresaAssociacao.value == '') {
                mensagem = 'Escolha uma empresa para continuar.';
            } else if (inputDominioSite.value == '') {
                mensagem = 'Digite a descrição da demanda.';
            }
            if (mensagem != '') {
                Alerta.notificacao(mensagem, false);
                resolve(false);
            }
            resolve(true);
        });
    };

    const montarDadoAssociacao = () => {
        return new Promise(resolve => {
            let texto = `<p><strong>Domínio:</strong> ${inputDominioSite.value}</p>`;
            if (inputSocialFacebook.value != '') {
                texto += `<p><strong>Facebook:</strong> ${inputSocialFacebook.value}</p>`;
            }
            if (inputSocialInstagram.value != '') {
                texto += `<p><strong>Instagram:</strong> ${inputSocialInstagram.value}</p>`;
            }
            if (inputSocialTwitter.value != '') {
                texto += `<p><strong>Twitter:</strong> ${inputSocialTwitter.value}</p>`;
            }
            if (inputEmail.value != '') {
                texto += `<p><strong>E-mail:</strong> ${inputEmail.value}</p>`;
            }
            if (inputTelefone.value != '') {
                texto += `<p><strong>Telefone:</strong> ${inputTelefone.value}</p>`;
            }
            if (inputEndereco.value != '') {
                texto += `<p><strong>Endereço:</strong> ${inputEndereco.value}</p>`;
            }
            texto += inputTexto.value;

            const body = new FormData();
            body.append('tipo', inputTipo.value);
            body.append('empresa', inputEmpresaAssociacao.value);
            body.append('texto', texto);

            resolve(body);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA OUTRO
    |--------------------------------------------------------------------------
    */
    const validarDadoOutro = () => {
        return new Promise(resolve => {
            let mensagem = '';
            if (inputTitulo.value == '') {
                mensagem = 'Digite um título para a demanda.';
            } else if (inputEmpresaOutro.value == '') {
                mensagem = 'Escolha uma empresa para continuar.';
            } else if (inputTexto.value == '') {
                mensagem = 'Digite a descrição da demanda.';
            }
            if (mensagem != '') {
                Alerta.notificacao(mensagem, false);
                resolve(false);
            }
            resolve(true);
        });
    };

    const montarDadoOutro = () => {
        return new Promise(resolve => {
            const body = new FormData();
            body.append('tipo', inputTipo.value);
            body.append('titulo', inputTitulo.value);
            body.append('empresa', inputEmpresaOutro.value);
            body.append('texto', inputTexto.value);

            resolve(body);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA BUG
    |--------------------------------------------------------------------------
    */
    const validarDadoBug = () => {
        return new Promise(resolve => {
            let mensagem = '';
            if (inputTitulo.value == '') {
                mensagem = 'Digite um título para a demanda.';
            } else if (inputBugLocal.value == '') {
                mensagem = 'Escolha o local que o BUG está acontecedo continuar.';
            } else if (inputEmpresaEspecifica.checked && inputEmpresaBug.value == '') {
                mensagem = 'Escolha uma empresa para continuar.';
            } else if (inputTexto.value == '') {
                mensagem = 'Digite a descrição da demanda.';
            }
            if (mensagem != '') {
                Alerta.notificacao(mensagem, false);
                resolve(false);
            }
            resolve(true);
        });
    };

    const montarDadoBug = () => {
        return new Promise(resolve => {
            const body = new FormData();
            body.append('tipo', inputTipo.value);
            body.append('titulo', inputTitulo.value);
            body.append('local', inputBugLocal.value);
            body.append('empresa', inputEmpresaBug.value);
            body.append('critico', inputBugCritico.value);
            body.append('texto', inputTexto.value);

            resolve(body);
        });
    };
};
