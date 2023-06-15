const demandaSalvar = () => {
    const area = document.querySelector('#input_area').value || '';
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
    // Criacao
    const inputEmpresaCriacao = document.getElementById('input_empresa_criacao');

    const inputCriacaoCategoriaSite = document.getElementById('input_criacao_categoria_site');
    const inputCriacaoCategoriaSocial = document.getElementById('input_criacao_categoria_social');
    const inputCriacaoCategoriaImpresso = document.getElementById('input_criacao_categoria_impresso');
    const inputCriacaoCategoriaKit = document.getElementById('input_criacao_categoria_kit');
    const inputCriacaoCategoriaVideo = document.getElementById('input_criacao_categoria_video');
    const inputCriacaoCategoriaOutro = document.getElementById('input_criacao_categoria_outro');
    const inputSiteLargura = document.getElementById('input_site_largura');
    const inputSiteAltura = document.getElementById('input_site_altura');
    const inputDigitalStories = document.getElementById('input_digital_stories');
    const inputDigitalFeed = document.getElementById('input_digital_feed');
    const inputDigitalBanner = document.getElementById('input_digital_banner');
    const inputFeedWhatsapp = document.getElementById('input_feed_whatsapp');
    const inputFeedInstagram = document.getElementById('input_feed_instagram');
    const inputFeedFacebook = document.getElementById('input_feed_facebook');
    const inputFeedLinkedin = document.getElementById('input_feed_linkedin');
    const inputFeedTwitter = document.getElementById('input_feed_twitter');
    const inputFeedYoutube = document.getElementById('input_feed_youtube');
    const inputFeedTiktok = document.getElementById('input_feed_tiktok');
    const inputImpressoVoucher = document.getElementById('input_impresso_voucher');
    const inputImpressoFolder = document.getElementById('input_impresso_folder');
    const inputImpressoBanner = document.getElementById('input_impresso_banner');
    const inputImpressoRevista = document.getElementById('input_impresso_revista');
    const inputImpressoOutro = document.getElementById('input_impresso_outro');
    const inputKitEmail = document.getElementById('input_kit_email');
    const inputKitStories = document.getElementById('input_kit_stories');
    const inputKitVideo = document.getElementById('input_kit_video');
    const inputKitFeed = document.getElementById('input_kit_feed');
    const inputKitComoAcessar = document.getElementById('input_kit_como_acessar');
    const inputKitBaixarApp = document.getElementById('input_kit_baixar_app');
    const inputKitPrevia = document.getElementById('input_kit_previa');
    const inputVideoFormato = document.getElementById('input_video_formato');
    const inputVideoLargura = document.getElementById('input_video_largura');
    const inputVideoAltura = document.getElementById('input_video_altura');
    const inputSiteTexto = document.getElementById('input_site_texto');
    const inputRedeSocialTexto = document.getElementById('input_rede_social_texto');
    const inputImpressoTexto = document.getElementById('input_impresso_texto');
    const inputKitTexto = document.getElementById('input_kit_texto');
    const inputVideoTexto = document.getElementById('input_video_texto');
    const inputOutroTexto = document.getElementById('input_outro_texto');

    const botaoSalvar = document.getElementById('botao_salvar_demanda');

    const blocoEscolherTecnologia = document.getElementById('bloco_tipo_demanda_tecnologia');
    const blocoEscolherCriacao = document.getElementById('bloco_tipo_demanda_criacao');
    const botaoTipo = document.querySelectorAll('#bloco_demanda_nova .botao_lista .botao');

    const blocoCriacao = document.getElementById('bloco_criacao');
    const blocoTipoAssociacao = document.getElementById('bloco_tipo_associacao');
    const blocoTipoCliente = document.getElementById('bloco_tipo_cliente');
    const blocoTipoBug = document.getElementById('bloco_tipo_bug');
    const blocoTipoOutro = document.getElementById('bloco_tipo_outro');
    const blocoHeader = document.getElementById('bloco_geral_header');
    const blocoFooter = document.getElementById('bloco_geral_footer');

    const blocoCriacaoOutro = document.getElementById('bloco_criacao_outro');
    const blocoCriacaoSite = document.getElementById('bloco_criacao_site');
    const blocoCriacaoSocial = document.getElementById('bloco_criacao_social');
    const blocoCriacaoFeed = document.getElementById('bloco_criacao_feed');
    const blocoCriacaoImpresso = document.getElementById('bloco_criacao_impresso');
    const blocoCriacaoImpressoOutro = document.getElementById('bloco_criacao_impresso_outro');
    const blocoCriacaoKit = document.getElementById('bloco_criacao_kit');
    const blocoCriacaoVideo = document.getElementById('bloco_criacao_video');
    const blocoCriacaoVideoDimensao = document.getElementById('bloco_criacao_video_dimensao');

    if (area == 'tecnologia') {
        blocoEscolherTecnologia.classList.remove('display_none');
    } else if (area == 'criacao') {
        blocoEscolherCriacao.classList.remove('display_none');
    }

    /*
    |--------------------------------------------------------------------------
    | ESCOLHER TIPO
    |--------------------------------------------------------------------------
    */
    botaoTipo.forEach(botao => {
        botao.addEventListener('click', () => {
            const tipo = botao.getAttribute('data-tipo');
            mudarTipoDemanda(tipo);
        });
    });

    const mudarTipoDemanda = tipo => {
        inputTipo.value = tipo;

        blocoEscolherTecnologia.classList.add('display_none');
        blocoEscolherCriacao.classList.add('display_none');
        botaoSalvar.classList.remove('display_none');

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
        } else if (tipo == 'criacao') {
            blocoCriacao.classList.remove('display_none');
            botaoSalvar.classList.add('display_none');
            blocoFooter.classList.add('display_none');
            blocoHeader.classList.remove('display_none');
        } else if (tipo == 'sorteio') {
            blocoFooter.classList.add('display_none');
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
        } else if (acao == 'criacao_video_formato') {
            monitorarFormatoVideo();
        }
    };

    /*
    |--------------------------------------------------------------------------
    | CRIACAO
    |--------------------------------------------------------------------------
    */
    inputDigitalFeed.addEventListener('change', () => {
        if (inputDigitalFeed.checked) {
            blocoCriacaoFeed.classList.remove('display_none');
            return;
        }
        blocoCriacaoFeed.classList.add('display_none');
    });

    inputCriacaoCategoriaSite.addEventListener('change', () => {
        adicionarBotaoSalvarCriacao();
        inputSiteLargura.value = '';
        inputSiteAltura.value = '';
        if (inputCriacaoCategoriaSite.checked) {
            blocoCriacaoSite.classList.remove('display_none');
            return;
        }
        blocoCriacaoSite.classList.add('display_none');
    });
    inputCriacaoCategoriaSocial.addEventListener('change', () => {
        adicionarBotaoSalvarCriacao();
        inputDigitalStories.checked = false;
        inputDigitalFeed.checked = false;
        inputDigitalBanner.checked = false;
        inputFeedWhatsapp.checked = false;
        inputFeedInstagram.checked = false;
        inputFeedFacebook.checked = false;
        inputFeedLinkedin.checked = false;
        inputFeedTwitter.checked = false;
        inputFeedYoutube.checked = false;
        inputFeedTiktok.checked = false;
        if (inputCriacaoCategoriaSocial.checked) {
            blocoCriacaoSocial.classList.remove('display_none');
            return;
        }
        blocoCriacaoSocial.classList.add('display_none');
        blocoCriacaoFeed.classList.add('display_none');
    });
    inputCriacaoCategoriaImpresso.addEventListener('change', () => {
        adicionarBotaoSalvarCriacao();
        inputImpressoVoucher.checked = false;
        inputImpressoFolder.checked = false;
        inputImpressoBanner.checked = false;
        inputImpressoRevista.checked = false;
        inputImpressoOutro.checked = false;
        formValue(inputImpressoTexto, '');
        if (inputCriacaoCategoriaImpresso.checked) {
            blocoCriacaoImpresso.classList.remove('display_none');
            return;
        }
        blocoCriacaoImpresso.classList.add('display_none');
        blocoCriacaoImpressoOutro.classList.add('display_none');
    });

    inputCriacaoCategoriaKit.addEventListener('change', () => {
        adicionarBotaoSalvarCriacao();
        inputKitEmail.checked = false;
        inputKitStories.checked = false;
        inputKitVideo.checked = false;
        inputKitFeed.checked = false;
        inputKitComoAcessar.checked = false;
        inputKitBaixarApp.checked = false;
        inputKitPrevia.checked = false;
        if (inputCriacaoCategoriaKit.checked) {
            blocoCriacaoKit.classList.remove('display_none');
            return;
        }
        blocoCriacaoKit.classList.add('display_none');
    });
    inputCriacaoCategoriaVideo.addEventListener('change', () => {
        adicionarBotaoSalvarCriacao();
        formValue(inputVideoFormato, '');
        inputVideoLargura.value = '';
        inputVideoAltura.value = '';
        if (inputCriacaoCategoriaVideo.checked) {
            blocoCriacaoVideo.classList.remove('display_none');
            return;
        }
        blocoCriacaoVideo.classList.add('display_none');
        blocoCriacaoVideoDimensao.classList.add('display_none');
    });
    inputCriacaoCategoriaOutro.addEventListener('change', () => {
        adicionarBotaoSalvarCriacao();
        inputOutroTexto.value = '';
        if (inputCriacaoCategoriaOutro.checked) {
            blocoCriacaoOutro.classList.remove('display_none');
            return;
        }
        blocoCriacaoOutro.classList.add('display_none');
    });

    const adicionarBotaoSalvarCriacao = () => {
        if (
            inputCriacaoCategoriaSite.checked ||
            inputCriacaoCategoriaSocial.checked ||
            inputCriacaoCategoriaImpresso.checked ||
            inputCriacaoCategoriaKit.checked ||
            inputCriacaoCategoriaVideo.checked ||
            inputCriacaoCategoriaOutro.checked
        ) {
            botaoSalvar.classList.remove('display_none');
            return;
        }
        botaoSalvar.classList.add('display_none');
    };

    const monitorarFormatoVideo = () => {
        const tipo = inputVideoFormato.value;
        inputVideoLargura.value = '';
        inputVideoAltura.value = '';
        if (tipo == 'outro') {
            inputVideoLargura.focus();
            blocoCriacaoVideoDimensao.classList.remove('display_none');
            return;
        }
        blocoCriacaoVideoDimensao.classList.add('display_none');
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
        formSelectValue(inputEmpresaBug, '');
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
        } else if (area == 'criacao') {
            valido = await validarDadoCriacao();
            body = await montarDadoCriacao();
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

        window.location.assign(LINK + '/demanda/' + area + '#demanda-' + json.dado.id);
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

    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA CRIACAO
    |--------------------------------------------------------------------------
    */
    const validarDadoCriacao = () => {
        return new Promise(resolve => {
            let mensagem = '';
            if (inputTitulo.value == '') {
                mensagem = 'Digite um título para a demanda.';
            } else if (inputEmpresaCriacao.value == '') {
                mensagem = 'Escolha uma empresa para continuar.';
            } else if (
                !inputCriacaoCategoriaImpresso.checked &&
                !inputCriacaoCategoriaKit.checked &&
                !inputCriacaoCategoriaOutro.checked &&
                !inputCriacaoCategoriaSite.checked &&
                !inputCriacaoCategoriaSocial.checked &&
                !inputCriacaoCategoriaVideo.checked
            ) {
                mensagem = 'Você tem que escolher pelo menos um tipo de tarefa para a demanda.';
            } else if (
                inputCriacaoCategoriaSite.checked &&
                (inputSiteLargura.value == '' || inputSiteAltura.value == '')
            ) {
                mensagem = 'Você deve passar a largura e altura da peça do site.';
            } else if (inputCriacaoCategoriaSite.checked && inputSiteTexto.value == '') {
                mensagem = 'Você deve passar a descrição da tarefa do site.';
            } else if (
                inputCriacaoCategoriaSocial.checked &&
                !inputDigitalStories.checked &&
                !inputDigitalFeed.checked &&
                !inputDigitalBanner.checked
            ) {
                mensagem = 'Você deve marcar pelo menos uma rede social.';
            } else if (
                inputDigitalFeed.checked &&
                !inputFeedFacebook.checked &&
                !inputFeedWhatsapp.checked &&
                !inputFeedInstagram.checked &&
                !inputFeedLinkedin.checked &&
                !inputFeedTwitter.checked &&
                !inputFeedYoutube.checked &&
                !inputFeedTiktok.checked
            ) {
                mensagem = 'Você deve marcar em quais redes sociais irão aparecer as artes do feed.';
            } else if (inputCriacaoCategoriaSocial.checked && inputRedeSocialTexto.value == '') {
                mensagem = 'Você deve passar a descrição da tarefa da rede social.';
            } else if (
                inputCriacaoCategoriaImpresso.checked &&
                !inputImpressoVoucher.checked &&
                !inputImpressoFolder.checked &&
                !inputImpressoBanner.checked &&
                !inputImpressoRevista.checked &&
                !inputImpressoOutro.checked
            ) {
                mensagem = 'Você deve marcar quais peças impressas devem ser criadas.';
            } else if (inputCriacaoCategoriaImpresso.checked && inputImpressoTexto.value == '') {
                mensagem = 'Você deve passar a descrição da tarefa de imprenso.';
            } else if (
                inputCriacaoCategoriaKit.checked &&
                !inputKitEmail.checked &&
                !inputKitStories.checked &&
                !inputKitFeed.checked &&
                !inputKitComoAcessar.checked &&
                !inputKitBaixarApp.checked &&
                !inputKitPrevia.checked &&
                !inputKitVideo.checked
            ) {
                mensagem = 'Você deve marcar quais peças do kit de boas-vindas devem ser criadas.';
            } else if (inputCriacaoCategoriaKit.checked && inputKitTexto.value == '') {
                mensagem = 'Você deve passar a descrição da tarefa do kit de boa-vindas.';
            } else if (
                inputVideoFormato.value == 'outro' &&
                (inputVideoLargura.value == '' || inputVideoAltura == '')
            ) {
                mensagem = 'Você deve passar a largura e altura do vídeo.';
            } else if (inputCriacaoCategoriaVideo.checked && inputVideoTexto.value == '') {
                mensagem = 'Você deve passar a descrição da tarefa do vídeo.';
            } else if (inputCriacaoCategoriaOutro.checked && inputOutroTexto.value == '') {
                mensagem = 'É obrigado digitar uma descrição para o tipo de demanda outro.';
            } else if (inputCriacaoCategoriaOutro.checked && inputOutroTexto.value == '') {
                mensagem = 'Você deve passar a descrição da tarefa de outro.';
            }
            if (mensagem != '') {
                Alerta.notificacao(mensagem, false);
                resolve(false);
            }
            resolve(true);
        });
    };

    const montarDadoCriacao = () => {
        return new Promise(resolve => {
            const body = new FormData();
            body.append('tipo', 'criacao');
            body.append('empresa', inputEmpresaCriacao.value);
            body.append('titulo', inputTitulo.value);
            body.append('criacao_site', inputCriacaoCategoriaSite.checked ? 'sim' : 'nao');
            body.append('criacao_social', inputCriacaoCategoriaSocial.checked ? 'sim' : 'nao');
            body.append('criacao_impresso', inputCriacaoCategoriaImpresso.checked ? 'sim' : 'nao');
            body.append('criacao_kit', inputCriacaoCategoriaKit.checked ? 'sim' : 'nao');
            body.append('criacao_video', inputCriacaoCategoriaVideo.checked ? 'sim' : 'nao');
            body.append('criacao_outro', inputCriacaoCategoriaOutro.checked ? 'sim' : 'nao');
            body.append('site_largura', inputSiteLargura.value);
            body.append('site_altura', inputSiteAltura.value);
            body.append('site_texto', inputSiteTexto.value);
            body.append('digital_stories', inputDigitalStories.checked ? 'sim' : 'nao');
            body.append('digital_feed', inputDigitalFeed.checked ? 'sim' : 'nao');
            body.append('digital_banner', inputDigitalBanner.checked ? 'sim' : 'nao');
            body.append('feed_whatsapp', inputFeedWhatsapp.checked ? 'sim' : 'nao');
            body.append('feed_instagram', inputFeedInstagram.checked ? 'sim' : 'nao');
            body.append('feed_facebook', inputFeedFacebook.checked ? 'sim' : 'nao');
            body.append('feed_linkedin', inputFeedLinkedin.checked ? 'sim' : 'nao');
            body.append('feed_twitter', inputFeedTwitter.checked ? 'sim' : 'nao');
            body.append('feed_youtube', inputFeedYoutube.checked ? 'sim' : 'nao');
            body.append('feed_tiktop', inputFeedTiktok.checked ? 'sim' : 'nao');
            body.append('digital_texto', inputRedeSocialTexto.value);
            body.append('impresso_voucher', inputImpressoVoucher.checked ? 'sim' : 'nao');
            body.append('impresso_folder', inputImpressoFolder.checked ? 'sim' : 'nao');
            body.append('impresso_banner', inputImpressoBanner.checked ? 'sim' : 'nao');
            body.append('impresso_revista', inputImpressoRevista.checked ? 'sim' : 'nao');
            body.append('impresso_outro', inputImpressoOutro.checked ? 'sim' : 'nao');
            body.append('impresso_texto', inputImpressoTexto.value);
            body.append('kit_email', inputKitEmail.checked ? 'sim' : 'nao');
            body.append('kit_stories', inputKitStories.checked ? 'sim' : 'nao');
            body.append('kit_video', inputKitVideo.checked ? 'sim' : 'nao');
            body.append('kit_feed', inputKitFeed.checked ? 'sim' : 'nao');
            body.append('kit_como_acessar', inputKitComoAcessar.checked ? 'sim' : 'nao');
            body.append('kit_baixar_app', inputKitBaixarApp.checked ? 'sim' : 'nao');
            body.append('kit_previa', inputKitPrevia.checked ? 'sim' : 'nao');
            body.append('kit_texto', inputKitTexto.value);
            body.append('video_formato', inputVideoFormato.value);
            body.append('video_largura', inputVideoLargura.value);
            body.append('video_altura', inputVideoAltura.value);
            body.append('video_texto', inputVideoTexto.value);
            body.append('outro_texto', inputOutroTexto.value);

            resolve(body);
        });
    };
};
