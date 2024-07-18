window.addEventListener('load', () => {
    const botaoAdd = $('#botao_add_tarefa');
    const PaginaAddTarefa = new Popup('demanda-salvar', 'bloco_demanda_nova', true, true);

    const inputTipo = $('#input_tipo');
    const inputTitulo = $('#input_titulo');
    const inputTexto = $('#input_texto');

    // Cliente
    const inputEmpresaCliente = $('#input_empresa_cliente');
    const inputDominioTipo = $('#input_dominio_tipo');
    const inputDominioSub = $('#input_dominio_sub');
    const inputDominioProprio = $('#input_dominio_proprio');
    const inputConfigurarCdn = $('#input_configurar_cdn');
    const inputLoginApi = $('#input_login_api');
    const inputDominioLogin = $('#input_dominio_login');
    const inputApp = $('#input_app');
    const inputWebView = $('#input_webview');
    // Bub
    const inputBugLocal = $('#input_bug_local');
    const inputEmpresaBug = $('#input_empresa_bug');
    const inputEmpresaEspecifica = $('#input_empresa_especifica');
    const inputBugCritico = $('#input_bug_critico');
    // Outro
    const inputEmpresaOutro = $('#input_empresa_outro');
    // Associacao
    const inputEmpresaAssociacao = $('#input_empresa_associacao');
    const inputDominioSite = $('#input_dominio_site');
    const inputSocialFacebook = $('#input_social_facebook');
    const inputSocialInstagram = $('#input_social_instagram');
    const inputSocialTwitter = $('#input_social_twitter');
    const inputEmail = $('#input_email');
    const inputTelefone = $('#input_telefone');
    const inputEndereco = $('#input_endereco');
    // Criacao
    const inputEmpresaCriacao = $('#input_empresa_criacao');
    const inputDataEntrega = $('#input_criacao_data_entrega');
    const inputCriacaoCategoriaSite = $('#input_criacao_categoria_site');
    const inputCriacaoCategoriaSocial = $('#input_criacao_categoria_social');
    const inputCriacaoCategoriaImpresso = $('#input_criacao_categoria_impresso');
    const inputCriacaoCategoriaKit = $('#input_criacao_categoria_kit');
    const inputCriacaoCategoriaVideo = $('#input_criacao_categoria_video');
    const inputCriacaoCategoriaOutro = $('#input_criacao_categoria_outro');
    const inputSiteLargura = $('#input_site_largura');
    const inputSiteAltura = $('#input_site_altura');
    const inputDigitalStories = $('#input_digital_stories');
    const inputDigitalFeed = $('#input_digital_feed');
    const inputDigitalBanner = $('#input_digital_banner');
    const inputFeedWhatsapp = $('#input_feed_whatsapp');
    const inputFeedInstagram = $('#input_feed_instagram');
    const inputFeedFacebook = $('#input_feed_facebook');
    const inputFeedLinkedin = $('#input_feed_linkedin');
    const inputFeedTwitter = $('#input_feed_twitter');
    const inputFeedYoutube = $('#input_feed_youtube');
    const inputFeedTiktok = $('#input_feed_tiktok');
    const inputImpressoVoucher = $('#input_impresso_voucher');
    const inputImpressoFolder = $('#input_impresso_folder');
    const inputImpressoBanner = $('#input_impresso_banner');
    const inputImpressoRevista = $('#input_impresso_revista');
    const inputImpressoOutro = $('#input_impresso_outro');
    const inputKitEmail = $('#input_kit_email');
    const inputKitStories = $('#input_kit_stories');
    const inputKitVideo = $('#input_kit_video');
    const inputKitFeed = $('#input_kit_feed');
    const inputKitComoAcessar = $('#input_kit_como_acessar');
    const inputKitBaixarApp = $('#input_kit_baixar_app');
    const inputKitPrevia = $('#input_kit_previa');
    const inputVideoFormato = $('#input_video_formato');
    const inputVideoLargura = $('#input_video_largura');
    const inputVideoAltura = $('#input_video_altura');
    const inputSiteTexto = $('#input_site_texto');
    const inputRedeSocialTexto = $('#input_rede_social_texto');
    const inputImpressoTexto = $('#input_impresso_texto');
    const inputKitTexto = $('#input_kit_texto');
    const inputVideoTexto = $('#input_video_texto');
    const inputOutroTexto = $('#input_outro_texto');
    // Sorteio
    const inputEmpresaSorteio = $('#input_empresa_sorteio');
    const inputDataEntregaSorteio = $('#input_sorteio_data_entrega');
    const inputSorteioDataInicio = $('#input_data_inicio');
    const inputSorteioDataFinal = $('#input_data_final');
    const inputSorteioDataSorteio = $('#input_data_sorteio');
    const inputSorteioComoParticipar = $('#input_como_participar');
    const inputSorteioMotivacao = $('#input_sorteio_motivacao');
    const inputSorteioMotivacaoOutro = $('#input_sorteio_motivacao_outro');
    const inputSorteioPremioItem = $('#input_premio_item');
    const inputSorteioPremioCompra = $('#input_premio_compra');
    const inputSorteioPremioEntrega = $('#input_premio_entrega');
    const inputSorteioPremioEntregaOutro = $('#input_premio_entrega_outro');
    // Evento
    const inputEmpresaEvento = $('#input_empresa_evento');
    // Brinde
    const inputEmpresaBrinde = $('#input_empresa_brinde');
    // Campanha
    const inputEmpresaCampanha = $('#input_empresa_campanha');
    // Indicacao
    const inputEmpresaIndicacao = $('#input_empresa_indicacao');
    // Autoindicacao
    const inputEmpresaAutoindicacao = $('#input_empresa_autoindicacao');

    // Cotação - Automovel
    const inputEmpresaCotacaoAutomovel = $('#input_empresa_cotacao_automovel');
    // Cotação - Produto
    const inputEmpresaCotacaoProduto = $('#input_empresa_cotacao_produto');
    // Auditoria
    const inputEmpresaAuditoria = $('#input_empresa_auditoria');

    const botaoSalvar = $('#botao_demanda_salvar');
    const botaoFechar = $('#botao_demanda_fechar');
    const botaoVoltar = $('#botao_demanda_voltar');

    const blocoEscolherTecnologia = $('#bloco_tipo_demanda_tecnologia');
    const blocoEscolherCriacao = $('#bloco_tipo_demanda_criacao');
    const blocoConvenio = $('#bloco_tipo_demanda_convenio');
    const botaoTipo = $$('#bloco_demanda_nova .botao_lista .botao');

    const blocoTipoAuditoria = $('#bloco_auditoria');
    const blocoTipoCotacaoProduto = $('#bloco_cotacao_produto');

    const blocoTipoCotacaoAutomovel = $('#bloco_cotacao_automovel');
    const blocoTipoAutoindicacao = $('#bloco_autoindicacao');
    const blocoTipoIndicacao = $('#bloco_indicacao');
    const blocoTipoBrinde = $('#bloco_brinde');
    const blocoTipoCampanha = $('#bloco_campanha');
    const blocoTipoEvento = $('#bloco_evento');
    const blocoTipoSorteio = $('#bloco_sorteio');
    const blocoTipoCriacao = $('#bloco_criacao');
    const blocoTipoAssociacao = $('#bloco_tipo_associacao');
    const blocoTipoCliente = $('#bloco_tipo_cliente');
    const blocoTipoBug = $('#bloco_tipo_bug');
    const blocoTipoOutro = $('#bloco_tipo_outro');
    const blocoHeader = $('#bloco_geral_header');
    const blocoFooter = $('#bloco_geral_footer');

    const blocoCriacaoOutro = $('#bloco_criacao_outro');
    const blocoCriacaoSite = $('#bloco_criacao_site');
    const blocoCriacaoSocial = $('#bloco_criacao_social');
    const blocoCriacaoFeed = $('#bloco_criacao_feed');
    const blocoCriacaoImpresso = $('#bloco_criacao_impresso');
    const blocoCriacaoKit = $('#bloco_criacao_kit');
    const blocoCriacaoVideo = $('#bloco_criacao_video');
    const blocoCriacaoVideoDimensao = $('#bloco_criacao_video_dimensao');

    const blocoSorteioMotivacaoOutro = $('#bloco_sorteio_motivacao_outro');
    const blocoSorteioEntregaOutro = $('#bloco_premio_entrega_outro');

    /*
    |--------------------------------------------------------------------------
    | ABRE POPUP
    |--------------------------------------------------------------------------
    */
    if (botaoAdd) {
        botaoAdd.addEventListener('click', () => {
            PaginaAddTarefa.abrir();
        });
    }

    const escolherTipoDemanda = () => {
        if (area == 'tecnologia') {
            blocoEscolherTecnologia.aparecer();
        } else if (area == 'criacao') {
            blocoEscolherCriacao.aparecer();
        }
    };
    escolherTipoDemanda();

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
        inputTipo.valor(tipo);

        blocoEscolherTecnologia.sumir();
        blocoEscolherCriacao.sumir();
        blocoConvenio.sumir();

        botaoSalvar.aparecer();
        botaoFechar.sumir();
        botaoVoltar.aparecer();
        blocoFooter.aparecer();

        switch (tipo) {
            case 'associacao':
                blocoTipoAssociacao.aparecer();
                break;
            case 'cliente':
                blocoTipoCliente.aparecer();
                break;
            case 'bug':
                blocoHeader.aparecer();
                blocoTipoBug.aparecer();
                break;
            case 'feature':
            case 'outro':
                blocoHeader.aparecer();
                blocoTipoOutro.aparecer();
                break;
            case 'criacao':
                blocoTipoCriacao.aparecer();
                botaoSalvar.sumir();
                blocoHeader.aparecer();
                break;
            case 'sorteio':
                blocoTipoSorteio.aparecer();
                blocoHeader.aparecer();
                break;
            case 'evento':
                blocoTipoEvento.aparecer();
                blocoHeader.aparecer();
                break;
            case 'campanha':
                blocoTipoCampanha.aparecer();
                blocoHeader.aparecer();
                break;
            case 'brinde':
                blocoTipoBrinde.aparecer();
                blocoHeader.aparecer();
                break;
            case 'indicacao':
                blocoTipoIndicacao.aparecer();
                blocoHeader.aparecer();
                break;
            case 'autoindicacao':
                blocoTipoAutoindicacao.aparecer();
                blocoHeader.aparecer();
                break;
            case 'cotacao_automovel':
                blocoTipoCotacaoAutomovel.aparecer();
                blocoHeader.aparecer();
                break;
            case 'cotacao_produto':
                blocoTipoCotacaoProduto.aparecer();
                blocoHeader.aparecer();
                break;
            case 'auditoria':
                blocoTipoAuditoria.aparecer();
                blocoHeader.aparecer();
                break;
        }
    };

    /*
    |--------------------------------------------------------------------------
    | ACAO DE SELECT
    |--------------------------------------------------------------------------
    */
    inputDominioTipo.addEventListener('formChange', () => {
        mudarTipoDominio();
    });
    inputVideoFormato.addEventListener('formChange', () => {
        monitorarFormatoVideo();
    });
    inputSorteioMotivacao.addEventListener('formChange', () => {
        motivacaoOutro();
    });
    inputSorteioPremioEntrega.addEventListener('formChange', () => {
        entregaOutro();
    });

    /*
    |--------------------------------------------------------------------------
    | SORTEIO
    |--------------------------------------------------------------------------
    */
    const motivacaoOutro = () => {
        if (inputSorteioMotivacao.valor() == 'outro') {
            blocoSorteioMotivacaoOutro.aparecer();
            inputSorteioMotivacaoOutro.focus();
            return;
        }
        blocoSorteioMotivacaoOutro.sumir();
    };
    const entregaOutro = () => {
        if (inputSorteioPremioEntrega.valor() == 'outro') {
            blocoSorteioEntregaOutro.aparecer();
            inputSorteioPremioEntregaOutro.focus();
            return;
        }
        blocoSorteioEntregaOutro.sumir();
    };

    /*
    |--------------------------------------------------------------------------
    | CRIACAO
    |--------------------------------------------------------------------------
    */
    inputDigitalFeed.addEventListener('change', () => {
        if (inputDigitalFeed.checked) {
            blocoCriacaoFeed.aparecer();
            return;
        }
        blocoCriacaoFeed.sumir();
    });

    inputCriacaoCategoriaSite.addEventListener('change', () => {
        adicionarBotaoSalvarCriacao();
        inputSiteLargura.valor('');
        inputSiteAltura.valor('');
        if (inputCriacaoCategoriaSite.checked) {
            blocoCriacaoSite.aparecer();
            return;
        }
        blocoCriacaoSite.sumir();
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
            blocoCriacaoSocial.aparecer();
            return;
        }
        blocoCriacaoSocial.sumir();
        blocoCriacaoFeed.sumir();
    });
    inputCriacaoCategoriaImpresso.addEventListener('change', () => {
        adicionarBotaoSalvarCriacao();
        inputImpressoVoucher.checked = false;
        inputImpressoFolder.checked = false;
        inputImpressoBanner.checked = false;
        inputImpressoRevista.checked = false;
        inputImpressoOutro.checked = false;
        inputImpressoTexto.valor('');
        if (inputCriacaoCategoriaImpresso.checked) {
            blocoCriacaoImpresso.aparecer();
            return;
        }
        blocoCriacaoImpresso.sumir();
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
            blocoCriacaoKit.aparecer();
            return;
        }
        blocoCriacaoKit.sumir();
    });
    inputCriacaoCategoriaVideo.addEventListener('change', () => {
        adicionarBotaoSalvarCriacao();
        inputVideoFormato.valor('');
        inputVideoLargura.valor('');
        inputVideoAltura.valor('');
        if (inputCriacaoCategoriaVideo.checked) {
            blocoCriacaoVideo.aparecer();
            return;
        }
        blocoCriacaoVideo.sumir();
        blocoCriacaoVideoDimensao.sumir();
    });
    inputCriacaoCategoriaOutro.addEventListener('change', () => {
        adicionarBotaoSalvarCriacao();
        inputOutroTexto.valor('');
        if (inputCriacaoCategoriaOutro.checked) {
            blocoCriacaoOutro.aparecer();
            return;
        }
        blocoCriacaoOutro.sumir();
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
            botaoSalvar.aparecer();
            return;
        }
        botaoSalvar.sumir();
    };

    const monitorarFormatoVideo = () => {
        const tipo = inputVideoFormato.valor();
        inputVideoLargura.valor('');
        inputVideoAltura.valor('');
        if (tipo == 'outro') {
            inputVideoLargura.focus();
            blocoCriacaoVideoDimensao.aparecer();
            return;
        }
        blocoCriacaoVideoDimensao.sumir();
    };

    /*
    |--------------------------------------------------------------------------
    | DOMINIO
    |--------------------------------------------------------------------------
    */
    const blocoDominioSub = $('#bloco_dominio_sub');
    const blocoDominioSubTexto = $('#bloco_dominio_sub_texto');
    const blocoDominioProprio = $('#bloco_dominio_proprio');
    const blocoObservacaoDominioProprio = $('#bloco_observacao_dominio_proprio');
    const blocoObservacaoSubDominioProprio = $('#bloco_observacao_sub_dominio_proprio');
    const blocoConfigurarCdn = $('#bloco_configurar_cdn');

    const mudarTipoDominio = () => {
        blocoDominioSub.sumir();
        blocoDominioProprio.sumir();
        blocoObservacaoDominioProprio.sumir();
        blocoObservacaoSubDominioProprio.sumir();
        blocoConfigurarCdn.sumir();
        inputDominioProprio.valor('');
        inputDominioSub.valor('');
        inputConfigurarCdn.checked = false;

        const valor = inputDominioTipo.valor();
        if (valor == '') {
            return;
        } else if (valor == 'dominio') {
            blocoDominioProprio.aparecer();
            blocoObservacaoDominioProprio.aparecer();
            blocoConfigurarCdn.aparecer();
            inputDominioProprio.focus();
            return;
        } else if (valor == 'subdominio') {
            blocoDominioProprio.aparecer();
            blocoObservacaoSubDominioProprio.aparecer();
            inputDominioProprio.focus();
            return;
        }
        blocoDominioSub.aparecer();
        blocoDominioSubTexto.innerText = '.' + valor + '.com.br';
        inputDominioSub.focus();
    };

    /*
    |--------------------------------------------------------------------------
    | LOGIN
    |--------------------------------------------------------------------------
    */
    const blocoDominioLogin = $('#bloco_dominio_login');
    inputLoginApi.addEventListener('change', () => {
        mostrarObservacaoApp();
        if (inputLoginApi.checked) {
            blocoDominioLogin.aparecer();
            return;
        }
        blocoDominioLogin.sumir();
        inputDominioLogin.valor('');
    });

    /*
    |--------------------------------------------------------------------------
    | APP
    |--------------------------------------------------------------------------
    */
    const blocoApp = $('#bloco_app');

    inputApp.addEventListener('click', () => {
        mostrarObservacaoApp();
    });
    const mostrarObservacaoApp = () => {
        if (inputApp.checked && inputLoginApi.checked) {
            blocoApp.aparecer();
            return;
        }
        blocoApp.sumir();
    };

    /*
    |--------------------------------------------------------------------------
    | EMPRESA ESPECIFICA
    |--------------------------------------------------------------------------
    */
    const blocoEmpresaEspecifica = $('#bloco_empresa_especifica');
    inputEmpresaEspecifica.addEventListener('change', () => {
        if (inputEmpresaEspecifica.checked) {
            blocoEmpresaEspecifica.aparecer();
            return;
        }
        blocoEmpresaEspecifica.sumir();
        inputEmpresaBug.valor('');
    });
    /*
    |--------------------------------------------------------------------------
    | EMPRESA FÍSICA INDICAÇÃO
    |--------------------------------------------------------------------------
    */
    const blocoIndicacaoEndereco = $('#bloco_indicacao_endereco');
    const botaoEmpresaLojaFisica = $('#input_indicacao_empresa_loja_fisica');
    botaoEmpresaLojaFisica.addEventListener('change', () => {
        if (botaoEmpresaLojaFisica.checked) {
            blocoIndicacaoEndereco.aparecer();
            return;
        }
        blocoIndicacaoEndereco.sumir();
    });
    /*
    |--------------------------------------------------------------------------
    | EMPRESA FÍSICA AUTOINDICAÇÃO
    |--------------------------------------------------------------------------
    */
    const blocoAutoIndicacaoEndereco = $('#bloco_autoindicacao_endereco');
    const botaoLojaFisica = $('#input_autoindicacao_loja_fisica');
    botaoLojaFisica.addEventListener('change', () => {
        if (botaoLojaFisica.checked) {
            blocoAutoIndicacaoEndereco.aparecer();
            return;
        }
        blocoAutoIndicacaoEndereco.sumir();
    });
    /*
    |--------------------------------------------------------------------------
    | EMPRESA FÍSICA AUDITORIA
    |--------------------------------------------------------------------------
    */
    const blocoAuditoriaEndereco = $('#bloco_auditoria_endereco');
    const botaoLojaFisicaAuditoria = $('#input_auditoria_loja_fisica');
    botaoLojaFisicaAuditoria.addEventListener('change', () => {
        if (botaoLojaFisicaAuditoria.checked) {
            blocoAuditoriaEndereco.aparecer();
            return;
        }
        blocoAuditoriaEndereco.sumir();
    });
    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    botaoSalvar.addEventListener('click', async () => {
        Loading.show();

        const tipo = inputTipo.valor();
        let valido = false;
        let body;

        switch (tipo) {
            case 'cliente':
                valido = await validarDadoCliente();
                body = await montarDadoCliente();
                break;
            case 'associacao':
                valido = await validarDadoAssociacao();
                body = await montarDadoAssociacao();
                break;
            case 'bug':
                valido = await validarDadoBug();
                body = await montarDadoBug();
                break;
            case 'outro':
            case 'feature':
                valido = await validarDadoOutro();
                body = await montarDadoOutro();
                break;
            case 'criacao':
                valido = await validarDadoCriacao();
                body = await montarDadoCriacao();
                break;
            case 'sorteio':
                valido = await validarDadoSorteio();
                body = await montarDadoSorteio();
                break;
            case 'evento':
                valido = await validarDadoEvento();
                body = await montarDadoEvento();
                break;
            case 'brinde':
                valido = await validarDadoBrinde();
                body = await montarDadoBrinde();
                break;
            case 'campanha':
                valido = await validarDadoCampanha();
                body = await montarDadoCampanha();
                break;
            case 'indicacao':
                valido = await validarDadoIndicacao();
                body = await montarDadoIndicacao();
                break;
            case 'autoindicacao':
                valido = await validarDadoAutoindicacao();
                body = await montarDadoAutoindicacao();
                break;
            case 'cotacao_automovel':
                valido = await validarDadoCotacaoAutomovel();
                body = await montarDadoCotacaoAutomovel();
                break;
            case 'cotacao_produto':
                valido = await validarDadoCotacaoProduto();
                body = await montarDadoCotacaoProduto();
                break;
            case 'auditoria':
                valido = await validarDadoAuditoria();
                body = await montarDadoAuditoria();
                break;
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
        Loading.hide();
        if (false === json) {
            return;
        }

        PopupTemp.fechar();
        setTimeout(() => {
            resetarDemanda();
        }, 300);

        const coluna = blocoSprintLista ? blocoSprintLista : primeiraColuna.querySelector('.conteudo');
        await adicionarNovaDemanda(coluna, json.dado, false);
        contarTarefaDemanda(coluna);
    });

    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA DE CLIENTE
    |--------------------------------------------------------------------------
    */
    const validarDadoCliente = () => {
        return new Promise(resolve => {
            let mensagem = '';
            if (inputEmpresaCliente.valor() == '') {
                mensagem = 'Escolha uma empresa para continuar.';
            } else if (inputDominioTipo.valor() == '') {
                mensagem = 'Escolha um tipo de domínio para continuar.';
            } else if (
                ((inputDominioTipo.valor() == 'dominio' || inputDominioTipo.valor() == 'subdominio') &&
                    inputDominioProprio.valor() == '') ||
                ((inputDominioTipo.valor() == 'temvantagens' || inputDominioTipo.valor() == 'temmaisvantagens') &&
                    inputDominioSub.valor() == '')
            ) {
                mensagem = 'Digite um domínio/subdomínio para o clube.';
            } else if (inputLoginApi.checked && inputDominioLogin.valor() == '') {
                mensagem = 'Digite o domínio de login do sistema do cliente.';
            } else if ($$('#bloco_menu_clube input:checked').length == 0) {
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
            if (inputDominioTipo.valor() == 'dominio' || inputDominioTipo.valor() == 'subdominio') {
                dominioLink = inputDominioProprio.valor();
            } else if (inputDominioTipo.valor() == 'temvantagens' || inputDominioTipo.valor() == 'temmaisvantagens') {
                dominioLink = inputDominioSub.valor();
            }

            let texto = `
                <p>O clube deve ter os seguintes menus:</p>
                    <ul>
            `;
            $$('#bloco_menu_clube input:checked').forEach(item => {
                texto += `<li>${item.valor()}</li>`;
            });
            texto += `</ul>`;
            if (inputWebView.checked) {
                texto += `<p>Deve tirar o botão de sair do Clube porque ele será usado apenas com WebView</p>`;
            }
            texto += inputTexto.valor();

            const body = new FormData();
            body.append('tipo', inputTipo.valor());
            body.append('titulo', 'Novo clube de vantagens');
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaCliente));
            body.append('empresa', inputEmpresaCliente.valor());
            body.append('dominio_tipo', inputDominioTipo.valor());
            body.append('dominio_link', dominioLink);
            body.append('login_api', inputLoginApi.valor());
            body.append('login_link', inputDominioLogin.valor());
            body.append('app', inputApp.valor());
            body.append('cdn', inputConfigurarCdn.valor());
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
            if (inputEmpresaAssociacao.valor() == '') {
                mensagem = 'Escolha uma empresa para continuar.';
            } else if (inputDominioSite.valor() == '') {
                mensagem = 'Digite o domínio do site para continuar.';
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
            let texto = `<p><strong>Domínio:</strong> ${inputDominioSite.valor()}</p>`;
            if (inputSocialFacebook.valor() != '') {
                texto += `<p><strong>Facebook:</strong> ${inputSocialFacebook.valor()}</p>`;
            }
            if (inputSocialInstagram.valor() != '') {
                texto += `<p><strong>Instagram:</strong> ${inputSocialInstagram.valor()}</p>`;
            }
            if (inputSocialTwitter.valor() != '') {
                texto += `<p><strong>Twitter:</strong> ${inputSocialTwitter.valor()}</p>`;
            }
            if (inputEmail.valor() != '') {
                texto += `<p><strong>E-mail:</strong> ${inputEmail.valor()}</p>`;
            }
            if (inputTelefone.valor() != '') {
                texto += `<p><strong>Telefone:</strong> ${inputTelefone.valor()}</p>`;
            }
            if (inputEndereco.valor() != '') {
                texto += `<p><strong>Endereço:</strong> ${inputEndereco.valor()}</p>`;
            }
            texto += inputTexto.valor();

            const body = new FormData();
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaAssociacao));
            body.append('titulo', 'Novo site para associação');
            body.append('tipo', inputTipo.valor());
            body.append('empresa', inputEmpresaAssociacao.valor());
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
            if (inputTitulo.valor() == '') {
                mensagem = 'Digite um título para a demanda.';
            } else if (inputEmpresaOutro.valor() == '') {
                mensagem = 'Escolha uma empresa para continuar.';
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
            body.append('tipo', inputTipo.valor());
            body.append('titulo', inputTitulo.valor());
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaOutro));
            body.append('empresa', inputEmpresaOutro.valor());
            body.append('texto', inputTexto.valor());

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
            if (inputTitulo.valor() == '') {
                mensagem = 'Digite um título para a demanda.';
            } else if (inputBugLocal.valor() == '') {
                mensagem = 'Escolha o local que o BUG está acontecedo continuar.';
            } else if (inputEmpresaEspecifica.checked && inputEmpresaBug.valor() == '') {
                mensagem = 'Escolha uma empresa para continuar.';
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
            body.append('tipo', inputTipo.valor());
            body.append('titulo', inputTitulo.valor());
            body.append('local', inputBugLocal.valor());
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaBug));
            body.append('empresa', inputEmpresaBug.valor());
            body.append('critico', inputBugCritico.valor());
            body.append('texto', inputTexto.valor());

            resolve(body);
        });
    };

    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA SORTEIO
    |--------------------------------------------------------------------------
    */
    const validarDadoSorteio = () => {
        return new Promise(resolve => {
            let mensagem = '';
            if (inputTitulo.valor() == '') {
                mensagem = 'Digite um título para a demanda.';
            } else if (inputEmpresaSorteio.valor() == '') {
                mensagem = 'Escolha uma empresa para continuar.';
            } else if (inputDataEntregaSorteio.valor() == '') {
                mensagem = 'Digite o prazo máximo.';
            } else if (inputSorteioDataInicio.valor() == '') {
                mensagem = 'Digite a data de início da sorteio.';
            } else if (inputSorteioDataFinal.valor() == '') {
                mensagem = 'Digite a data final do sorteio.';
            } else if (inputSorteioDataSorteio.valor() == '') {
                mensagem = 'Digite a data que será o sorteio.';
            } else if (inputSorteioComoParticipar.valor() == '') {
                mensagem = 'Digite as normas para o usuário participar do sorteio.';
            } else if (inputSorteioMotivacao.valor() == '') {
                mensagem = 'Escolha a motivação do sorteio.';
            } else if (inputSorteioMotivacao.valor() == 'outro' && inputSorteioMotivacaoOutro.valor() == '') {
                mensagem = 'Digite a motivação do sorteio.';
            } else if (inputSorteioPremioItem.valor() == '') {
                mensagem = 'Digite qual item vai ser sorteado.';
            } else if (inputSorteioPremioCompra.valor() == '') {
                mensagem = 'Escolha quem vai comprar o prémio.';
            } else if (inputSorteioPremioEntrega.valor() == '') {
                mensagem = 'Escolha a forma de entrega do prémio.';
            } else if (inputSorteioPremioEntrega.valor() == 'outro' && inputSorteioPremioEntregaOutro.valor() == '') {
                mensagem = 'Digite a forma de entrega do prémio.';
            }
            if (mensagem != '') {
                Alerta.notificacao(mensagem, false);
                resolve(false);
            }
            resolve(true);
        });
    };
    const montarDadoSorteio = () => {
        return new Promise(resolve => {
            const body = new FormData();
            body.append('tipo', inputTipo.valor());
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaSorteio));
            body.append('data_entrega', inputDataEntregaSorteio.valor());
            body.append('empresa', inputEmpresaSorteio.valor());
            body.append('titulo', inputTitulo.valor());
            body.append('sorteio_inicio', inputSorteioDataInicio.valor());
            body.append('sorteio_final', inputSorteioDataFinal.valor());
            body.append('sorteio_data', inputSorteioDataSorteio.valor());
            body.append('sorteio_como_participar', inputSorteioComoParticipar.valor());
            body.append('sorteio_motivacao', inputSorteioMotivacao.valor());
            body.append('sorteio_motivacao_outro', inputSorteioMotivacaoOutro.valor());
            body.append('sorteio_premio', inputSorteioPremioItem.valor());
            body.append('sorteio_premio_compra', inputSorteioPremioCompra.valor());
            body.append('sorteio_premio_entrega', inputSorteioPremioEntrega.valor());
            body.append('sorteio_premio_entrega_outro', inputSorteioPremioEntregaOutro.valor());
            body.append('texto', inputTexto.valor());

            resolve(body);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA EVENTO
    |--------------------------------------------------------------------------
    */
    const validarDadoEvento = () => {
        return new Promise(resolve => {
            const mensagemErro = {
                evento_data_inicio: 'Digite a data de início do evento.',
                evento_data_fim: 'Digite a data final do evento.',
                evento_metragem: 'Digite a metragem do evento.',
                evento_participantes_quantidade: 'Digite a quantidade de participantes do evento.',
                evento_publico_esperado: 'Digite o público esperado do evento.',
                evento_parceiros_quantidade: 'Digite a quantidade de parceiros do evento.',
                evento_esperado_da_empresa: 'Digite o que é esperado da empresa no evento.',
                evento_materiais: 'Digite os materiais que serão usados no evento.',
                evento_responsavel_nome: 'Digite o nome do responsável pelo evento.',
                evento_responsavel_email: 'Digite o e-mail do responsável pelo evento.',
                evento_responsavel_telefone: 'Digite o telefone do responsável pelo evento.',
            };

            resolve(testarCampos(mensagemErro) && testarDataInicio('input_evento_data_inicio', 30));
        });
    };

    const montarDadoEvento = () => {
        return new Promise(resolve => {
            const campos = {
                data_inicio: 'evento_data_inicio',
                data_fim: 'evento_data_fim',
                metragem: 'evento_metragem',
                participantes_quantidade: 'evento_participantes_quantidade',
                publico_esperado: 'evento_publico_esperado',
                parceiros_quantidade: 'evento_parceiros_quantidade',
                esperado_empresa: 'evento_esperado_da_empresa',
                materiais: 'evento_materiais',
                resposavel_nome: 'evento_responsavel_nome',
                responsavel_email: 'evento_responsavel_email',
                resposavel_telefone: 'evento_responsavel_telefone',
                cobertura: 'evento_cobertura',
                wifi: 'evento_wifi',
                energia: 'evento_energia',
                agua: 'evento_agua',
                alimentacao: 'evento_alimentacao',
                mesaCadeira: 'evento_mesa_cadeira',
                outros: 'evento_outros',
            };

            const body = montarBody(campos, inputEmpresaEvento);
            resolve(body);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA EVENTO
    |--------------------------------------------------------------------------
    */
    const validarDadoBrinde = () => {
        return new Promise(resolve => {
            const mensagemErro = {
                brinde_inicio_divulgacao: 'Digite a data de início da divulgação.',
                brinde_fim_divulgacao: 'Digite a data final da divulgação.',
                brinde_tema: 'Digite o tema da campanha.',
                brinde_segmento: 'Digite o segmento do brinde.',
                brinde_participantes: 'Digite a quantidade de participantes do brinde.',
            };

            resolve(testarCampos(mensagemErro) && testarDataInicio('input_brinde_inicio_divulgacao', 60));
        });
    };

    const montarDadoBrinde = () => {
        return new Promise(resolve => {
            const campos = {
                inicio_divulgacao: 'brinde_inicio_divulgacao',
                fim_divulgacao: 'brinde_fim_divulgacao',
                tema: 'brinde_tema',
                segmento: 'brinde_segmento',
                participantes: 'brinde_participantes',
                instagram: 'brinde_instagram',
                facebook: 'brinde_facebook',
                email: 'brinde_email',
                flyer: 'brinde_flyer',
                outros: 'brinde_divulgacao',
            };

            const body = montarBody(campos, inputEmpresaBrinde);
            resolve(body);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA CAMPANHA
    |--------------------------------------------------------------------------
    */
    const validarDadoCampanha = () => {
        return new Promise(resolve => {
            const mensagemErro = {
                campanha_inicio_divulgacao: 'Digite a data de início da divulgação.',
                campanha_fim_divulgacao: 'Digite a data final da divulgação.',
                campanha_tema: 'Digite o tema da campanha.',
                campanha_segmento: 'Digite o segmento da campanha.',
            };

            resolve(testarCampos(mensagemErro) && testarDataInicio('input_campanha_inicio_divulgacao', 30));
        });
    };

    const montarDadoCampanha = () => {
        return new Promise(resolve => {
            const campos = {
                inicio_divulgacao: 'campanha_inicio_divulgacao',
                fim_divulgacao: 'campanha_fim_divulgacao',
                tema: 'campanha_tema',
                segmento: 'campanha_segmento',
            };

            const body = montarBody(campos, inputEmpresaCampanha);
            resolve(body);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA INDICACAO
    |--------------------------------------------------------------------------
    */
    const validarDadoIndicacao = () => {
        return new Promise(resolve => {
            const mensagemErro = {
                indicacao_usuario_nome: 'Digite o nome de quem indicou.',
                indicacao_usuario_email: 'Digite o e-mail de quem indicou.',
                indicacao_usuario_telefone: 'Digite o telefone de quem indicou.',
                indicacao_empresa_nome: 'Digite o nome da empresa indicada.',
                indicacao_empresa_email: 'Digite o e-mail da empresa indicada.',
                indicacao_empresa_telefone: 'Digite o telefone da empresa indicada.',
            };

            resolve(testarCampos(mensagemErro));
        });
    };

    const montarDadoIndicacao = () => {
        return new Promise(resolve => {
            const campos = {
                usuario_nome: 'indicacao_usuario_nome',
                usuario_email: 'indicacao_usuario_email',
                usuario_telefone: 'indicacao_usuario_telefone',
                empresa_indicada_nome: 'indicacao_empresa_nome',
                empresa_email: 'indicacao_empresa_email',
                empresa_telefone: 'indicacao_empresa_telefone',
                empresa_loja_fisica: 'indicacao_empresa_loja_fisica',
                empresa_cep: 'indicacao_empresa_cep',
                empresa_logradouro: 'indicacao_empresa_logradouro',
                empresa_numero: 'indicacao_empresa_numero',
                empresa_complemento: 'indicacao_empresa_complemento',
                empresa_bairro: 'indicacao_empresa_bairro',
                empresa_cidade: 'indicacao_empresa_cidade',
                empresa_estado: 'indicacao_empresa_estado',
            };

            const body = montarBody(campos, inputEmpresaIndicacao);
            resolve(body);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA AUTOINDICACAO
    |--------------------------------------------------------------------------
    */
    const validarDadoAutoindicacao = () => {
        return new Promise(resolve => {
            const mensagemErro = {
                autoindicacao_nome: 'Digite o nome da empresa.',
                autoindicacao_ramo: 'Digite o ramo da empresa.',
                autoindicacao_email: 'Digite o e-mail da empresa.',
                autoindicacao_telefone: 'Digite o telefone da empresa.',
            };

            resolve(testarCampos(mensagemErro));
        });
    };

    const montarDadoAutoindicacao = () => {
        return new Promise(resolve => {
            const campos = {
                empresa_indicada_nome: 'autoindicacao_nome',
                ramo: 'autoindicacao_ramo',
                email: 'autoindicacao_email',
                telefone: 'autoindicacao_telefone',
                loja_fisica: 'autoindicacao_loja_fisica',
                cep: 'autoindicacao_cep',
                logradouro: 'autoindicacao_logradouro',
                numero: 'autoindicacao_numero',
                complemento: 'autoindicacao_complemento',
                bairro: 'autoindicacao_bairro',
                cidade: 'autoindicacao_cidade',
                estado: 'autoindicacao_estado',
            };

            const body = montarBody(campos, inputEmpresaAutoindicacao);
            resolve(body);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA AUTOINDICACAO
    |--------------------------------------------------------------------------
    */
    const validarDadoCotacaoAutomovel = () => {
        return new Promise(resolve => {
            const mensagemErro = {
                cotacao_automovel_nome: 'Digita o nome do usuário solicitante.',
                cotacao_automovel_cpf: 'Digita o CPF do usuário solicitante.',
                cotacao_automovel_email: 'Digite o E-mail do usuário solicitante.',
                cotacao_automovel_telefone: 'Digite o telefone do usuário solicitante.',
                cotacao_automovel_marca: 'Digite a marca do carro.',
                cotacao_automovel_modelo: 'Digite o modelo do carro.',
                cotacao_automovel_ano: 'Digite o ano do carro.',
                cotacao_automovel_cor: 'Digite a cor do carro.',
            };

            resolve(testarCampos(mensagemErro));
        });
    };

    const montarDadoCotacaoAutomovel = () => {
        return new Promise(resolve => {
            const campos = {
                nome: 'cotacao_automovel_nome',
                cpf: 'cotacao_automovel_cpf',
                email: 'cotacao_automovel_email',
                telefone: 'cotacao_automovel_telefone',
                marca: 'cotacao_automovel_marca',
                modelo: 'cotacao_automovel_modelo',
                ano: 'cotacao_automovel_ano',
                cor: 'cotacao_automovel_cor',
                extra: 'cotacao_automovel_extra',
            };

            const body = montarBody(campos, inputEmpresaCotacaoAutomovel);
            resolve(body);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA AUTOINDICACAO
    |--------------------------------------------------------------------------
    */
    const validarDadoCotacaoProduto = () => {
        return new Promise(resolve => {
            const mensagemErro = {
                cotacao_produto_nome: 'Digita o nome do usuário solicitante.',
                cotacao_produto_cpf: 'Digita o CPF do usuário solicitante.',
                cotacao_produto_email: 'Digite o E-mail do usuário solicitante.',
                cotacao_produto_telefone: 'Digite o telefone do usuário solicitante.',
                cotacao_produto_tipo: 'Digite o tipo do produto.',
                cotacao_produto_marca: 'Digite a marca do produto.',
                cotacao_produto_modelo: 'Digite o modelo do produto.',
                cotacao_produto_extra: 'Digite o extra do produto.',
            };

            resolve(testarCampos(mensagemErro));
        });
    };

    const montarDadoCotacaoProduto = () => {
        return new Promise(resolve => {
            const campos = {
                nome: 'cotacao_produto_nome',
                cpf: 'cotacao_produto_cpf',
                email: 'cotacao_produto_email',
                telefone: 'cotacao_produto_telefone',
                produto_tipo: 'cotacao_produto_tipo',
                marca: 'cotacao_produto_marca',
                modelo: 'cotacao_produto_modelo',
                extra: 'cotacao_produto_extra',
            };

            const body = montarBody(campos, inputEmpresaCotacaoProduto);
            resolve(body);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA AUTOINDICACAO
    |--------------------------------------------------------------------------
    */
    const validarDadoAuditoria = () => {
        return new Promise(resolve => {
            const mensagemErro = {
                auditoria_relatorio_problema: 'Digite o relatório do problema.',
            };
            resolve(testarCampos(mensagemErro));
        });
    };

    const montarDadoAuditoria = () => {
        return new Promise(resolve => {
            const campos = {
                relatorio: 'auditoria_relatorio_problema',
                loja_fisica: 'auditoria_loja_fisica',
                unidade: 'auditoria_empresa_unidade',
                atendente: 'auditoria_empresa_atendente',
                gerente: 'auditoria_empresa_gerente',
                email: 'auditoria_email',
                telefone: 'auditoria_telefone',
            };
            const body = montarBody(campos, inputEmpresaAuditoria);
            resolve(body);
        });
    };

    /*
    |--------------------------------------------------------------------------
    | FUNÇÕES PARA ABRIR TAREFA
    |--------------------------------------------------------------------------
    */
    const testarCampos = mensagemErro => {
        const campos = {
            titulo: 'Digite um título para a demanda.',
            ...mensagemErro,
        };
        for (const campo in campos) {
            const elemento = document.getElementById('input_' + campo);

            if (elemento.valor() === '') {
                Alerta.notificacao(campos[campo], false);
                return false;
            }
        }
        return true;
    };

    const montarBody = (campos, inputEmpresa) => {
        const body = new FormData();

        body.append('titulo', inputTitulo.valor());
        body.append('tipo', inputTipo.valor());
        body.append('empresa_nome', pegarEmpresaNome(inputEmpresa));
        body.append('empresa', inputEmpresa.valor());
        body.append('texto', inputTexto.valor());

        for (const key in campos) {
            const campo = campos[key];
            const elemento = document.getElementById('input_' + campo);
            if (elemento.type == 'checkbox') {
                body.append(key, elemento.checked ? 1 : 0);
                continue;
            }
            body.append(key, elemento.valor());
        }

        return body;
    };

    const testarDataInicio = async (input, periodo) => {
        const inputData = document.getElementById(input).valor();
        const dataInicio = new Date(formatarData(inputData));
        const hoje = new Date();

        const dataInicioMenosPeriodo = new Date(dataInicio.getTime() - periodo * 24 * 60 * 60 * 1000);

        if (dataInicio < hoje) {
            Alerta.notificacao(`A data de inicio não pode ser menor que hoje.`, false);
            return false;
        } else if (hoje > dataInicioMenosPeriodo) {
            return (await Alerta.confirmar(
                'Atenção!',
                `A demanda está sendo cadastrada antes do limite recomendado de ${periodo} dias.`,
                '!'
            ))
                ? true
                : false; // Apesar de estar após o limite não bloqueia o cadastro
        }

        return true;
    };

    const formatarData = dataOriginal => {
        var partes = dataOriginal.split(/[\s\/:]+/);
        var data = new Date(partes[2], partes[1] - 1, partes[0], partes[3], partes[4], partes[5]);
        var dataFormatada =
            data.getFullYear() +
            '-' +
            (data.getMonth() + 1).toString().padStart(2, '0') +
            '-' +
            data.getDate().toString().padStart(2, '0') +
            ' ' +
            data.getHours().toString().padStart(2, '0') +
            ':' +
            data.getMinutes().toString().padStart(2, '0') +
            ':' +
            data.getSeconds().toString().padStart(2, '0');
        return dataFormatada;
    };

    /*
    |--------------------------------------------------------------------------
    | ABRIR TAREFA CRIACAO
    |--------------------------------------------------------------------------
    */
    const validarDadoCriacao = () => {
        return new Promise(resolve => {
            let mensagem = '';
            if (inputTitulo.valor() == '') {
                mensagem = 'Digite um título para a demanda.';
            } else if (inputEmpresaCriacao.valor() == '') {
                mensagem = 'Escolha uma empresa para continuar.';
            } else if (inputDataEntrega.valor() == '') {
                mensagem = 'Digite a data de entrega da demanda.';
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
                (inputSiteLargura.valor() == '' || inputSiteAltura.valor() == '')
            ) {
                mensagem = 'Você deve passar a largura e altura da peça do site.';
            } else if (inputCriacaoCategoriaSite.checked && inputSiteTexto.valor() == '') {
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
            } else if (inputCriacaoCategoriaSocial.checked && inputRedeSocialTexto.valor() == '') {
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
            } else if (inputCriacaoCategoriaImpresso.checked && inputImpressoTexto.valor() == '') {
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
            } else if (inputCriacaoCategoriaKit.checked && inputKitTexto.valor() == '') {
                mensagem = 'Você deve passar a descrição da tarefa do kit de boa-vindas.';
            } else if (
                inputVideoFormato.valor() == 'outro' &&
                (inputVideoLargura.valor() == '' || inputVideoAltura == '')
            ) {
                mensagem = 'Você deve passar a largura e altura do vídeo.';
            } else if (inputCriacaoCategoriaVideo.checked && inputVideoTexto.valor() == '') {
                mensagem = 'Você deve passar a descrição da tarefa do vídeo.';
            } else if (inputCriacaoCategoriaOutro.checked && inputOutroTexto.valor() == '') {
                mensagem = 'É obrigado digitar uma descrição para o tipo de demanda outro.';
            } else if (inputCriacaoCategoriaOutro.checked && inputOutroTexto.valor() == '') {
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
            body.append('tipo', inputTipo.valor());
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaCriacao));
            body.append('data_entrega', inputDataEntrega.valor());
            body.append('empresa', inputEmpresaCriacao.valor());
            body.append('titulo', inputTitulo.valor());
            body.append('criacao_site', inputCriacaoCategoriaSite.checked ? 'sim' : 'nao');
            body.append('criacao_social', inputCriacaoCategoriaSocial.checked ? 'sim' : 'nao');
            body.append('criacao_impresso', inputCriacaoCategoriaImpresso.checked ? 'sim' : 'nao');
            body.append('criacao_kit', inputCriacaoCategoriaKit.checked ? 'sim' : 'nao');
            body.append('criacao_video', inputCriacaoCategoriaVideo.checked ? 'sim' : 'nao');
            body.append('criacao_outro', inputCriacaoCategoriaOutro.checked ? 'sim' : 'nao');
            body.append('site_largura', inputSiteLargura.valor());
            body.append('site_altura', inputSiteAltura.valor());
            body.append('site_texto', inputSiteTexto.valor());
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
            body.append('digital_texto', inputRedeSocialTexto.valor());
            body.append('impresso_voucher', inputImpressoVoucher.checked ? 'sim' : 'nao');
            body.append('impresso_folder', inputImpressoFolder.checked ? 'sim' : 'nao');
            body.append('impresso_banner', inputImpressoBanner.checked ? 'sim' : 'nao');
            body.append('impresso_revista', inputImpressoRevista.checked ? 'sim' : 'nao');
            body.append('impresso_outro', inputImpressoOutro.checked ? 'sim' : 'nao');
            body.append('impresso_texto', inputImpressoTexto.valor());
            body.append('kit_email', inputKitEmail.checked ? 'sim' : 'nao');
            body.append('kit_stories', inputKitStories.checked ? 'sim' : 'nao');
            body.append('kit_video', inputKitVideo.checked ? 'sim' : 'nao');
            body.append('kit_feed', inputKitFeed.checked ? 'sim' : 'nao');
            body.append('kit_como_acessar', inputKitComoAcessar.checked ? 'sim' : 'nao');
            body.append('kit_baixar_app', inputKitBaixarApp.checked ? 'sim' : 'nao');
            body.append('kit_previa', inputKitPrevia.checked ? 'sim' : 'nao');
            body.append('kit_texto', inputKitTexto.valor());
            body.append('video_formato', inputVideoFormato.valor());
            body.append('video_largura', inputVideoLargura.valor());
            body.append('video_altura', inputVideoAltura.valor());
            body.append('video_texto', inputVideoTexto.valor());
            body.append('outro_texto', inputOutroTexto.valor());
            body.append('texto', inputTexto.valor());

            resolve(body);
        });
    };

    const pegarEmpresaNome = empresa => {
        if (empresa.valor() == '') {
            return '';
        }
        const bloco = empresa.closest('.bloco_input');
        const input = bloco.querySelector('.input_select_texto');
        if (!input) {
            return '';
        }
        return input.valor() + ' - ';
    };

    /*
    |--------------------------------------------------------------------------
    | RESETAR
    |--------------------------------------------------------------------------
    */
    const resetarDemanda = () => {
        escolherTipoDemanda();

        switch (area) {
            case 'tecnologia':
                limparTecnologia();
                break;
            case 'criacao':
                limparCriacao();
                break;
            case 'convenio':
                limparConvenio();
                break;
        }
    };

    botaoVoltar.addEventListener('click', () => {
        resetarDemanda();
    });
    const limparTecnologia = () => {
        blocoTipoAssociacao.sumir();
        blocoTipoCliente.sumir();
        blocoTipoBug.sumir();
        blocoTipoOutro.sumir();
        blocoHeader.sumir();
        blocoFooter.sumir();

        botaoSalvar.sumir();
        botaoVoltar.sumir();
        botaoFechar.aparecer();

        inputTipo.valor('');
        inputTitulo.valor('');
        inputTexto.valor('');

        // Cliente
        inputEmpresaCliente.valor('');
        inputDominioTipo.valor('');
        inputDominioSub.valor('');
        inputDominioProprio.valor('');
        inputConfigurarCdn.checked = false;
        inputLoginApi.checked = false;
        inputDominioLogin.valor('');
        inputApp.checked = false;
        inputWebView.checked = false;

        for (const input of $$('#bloco_menu_clube input:checked')) {
            input.checked = false;
        }

        blocoDominioLogin.sumir();
        blocoApp.sumir();
        blocoDominioProprio.sumir();
        blocoObservacaoDominioProprio.sumir();
        blocoObservacaoSubDominioProprio.sumir();

        // Bub
        inputBugLocal.valor('');
        inputEmpresaBug.valor('');
        inputEmpresaEspecifica.checked = false;
        inputBugCritico.checked = false;
        blocoEmpresaEspecifica.sumir();
        // Outro
        inputEmpresaOutro.valor('');
        // Associacao
        inputEmpresaAssociacao.valor('');
        inputDominioSite.valor('');
        inputSocialFacebook.valor('');
        inputSocialInstagram.valor('');
        inputSocialTwitter.valor('');
        inputEmail.valor('');
        inputTelefone.valor('');
        inputEndereco.valor('');
    };
    const limparCriacao = () => {
        blocoTipoCriacao.sumir();
        blocoTipoSorteio.sumir();
        blocoHeader.sumir();
        blocoFooter.sumir();

        botaoSalvar.sumir();
        botaoVoltar.sumir();
        botaoFechar.aparecer();

        inputTipo.valor('');
        inputTitulo.valor('');

        // Criacao
        inputEmpresaCriacao.valor('');
        inputTexto.valor('');
        inputDataEntregaSorteio.valor('');
        inputDataEntrega.valor('');
        inputCriacaoCategoriaSite.checked = false;
        inputCriacaoCategoriaSocial.checked = false;
        inputCriacaoCategoriaImpresso.checked = false;
        inputCriacaoCategoriaKit.checked = false;
        inputCriacaoCategoriaVideo.checked = false;
        inputCriacaoCategoriaOutro.checked = false;
        inputSiteLargura.valor('');
        inputSiteAltura.valor('');
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
        inputImpressoVoucher.checked = false;
        inputImpressoFolder.checked = false;
        inputImpressoBanner.checked = false;
        inputImpressoRevista.checked = false;
        inputImpressoOutro.checked = false;
        inputKitEmail.checked = false;
        inputKitStories.checked = false;
        inputKitVideo.checked = false;
        inputKitFeed.checked = false;
        inputKitComoAcessar.checked = false;
        inputKitBaixarApp.checked = false;
        inputKitPrevia.checked = false;
        inputVideoFormato.valor('');
        inputVideoLargura.valor('');
        inputVideoAltura.valor('');
        inputSiteTexto.valor('');
        inputRedeSocialTexto.valor('');
        inputImpressoTexto.valor('');
        inputKitTexto.valor('');
        inputVideoTexto.valor('');
        inputOutroTexto.valor('');
        blocoCriacaoSite.sumir();
        blocoCriacaoFeed.sumir();
        blocoCriacaoImpresso.sumir();
        blocoCriacaoKit.sumir();
        blocoCriacaoOutro.sumir();
        blocoCriacaoSocial.sumir();
        blocoCriacaoVideo.sumir();
        blocoCriacaoVideoDimensao.sumir();

        // Sorteio
        inputEmpresaSorteio.valor('');
        inputSorteioDataInicio.valor('');
        inputSorteioDataFinal.valor('');
        inputSorteioDataSorteio.valor('');
        inputSorteioComoParticipar.valor('');
        inputSorteioMotivacao.valor('');
        inputSorteioMotivacaoOutro.valor('');
        inputSorteioPremioItem.valor('');
        inputSorteioPremioCompra.valor('');
        inputSorteioPremioEntrega.valor('');
        inputSorteioPremioEntregaOutro.valor('');
        blocoSorteioMotivacaoOutro.sumir();
        blocoSorteioEntregaOutro.sumir();
    };

    const limparConvenio = () => {
        const blocos = [
            blocoTipoEvento,
            blocoTipoCampanha,
            blocoTipoBrinde,
            blocoTipoIndicacao,
            blocoTipoAutoindicacao,
            blocoTipoCotacaoAutomovel,
            blocoTipoCotacaoProduto,
            blocoTipoAuditoria,
        ];

        blocos.forEach(bloco => {
            resetarInputs(bloco);
        });

        inputTipo.valor('');
        inputTitulo.valor('');
        inputTexto.valor('');

        blocoConvenio.aparecer();
        blocoHeader.sumir();
        blocoFooter.sumir();

        botaoSalvar.sumir();
        botaoVoltar.sumir();
        botaoFechar.aparecer();
    };

    const resetarInputs = bloco => {
        bloco.sumir();

        bloco.querySelectorAll('input').forEach(item => {
            if (item.type == 'checkbox') {
                item.checked = false;
            }
            if (item.type == 'text' || item.type == 'email') {
                item.valor('');
            }
        });
    };
});
