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
    const inputSorteioTexto = $('#input_sorteio_texto');
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
    const blocoCriacaoImpressoOutro = $('#bloco_criacao_impresso_outro');
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
    botaoAdd.addEventListener('click', () => {
        PaginaAddTarefa.abrir();
    });

    const escolherTipoDemanda = () => {
        if (area == 'tecnologia') {
            blocoEscolherTecnologia.classList.remove('display_none');
        } else if (area == 'criacao') {
            blocoEscolherCriacao.classList.remove('display_none');
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
        inputTipo.value = tipo;

        blocoEscolherTecnologia.classList.add('display_none');
        blocoEscolherCriacao.classList.add('display_none');
        blocoConvenio.classList.add('display_none');

        botaoSalvar.classList.remove('display_none');
        botaoFechar.classList.add('display_none');
        botaoVoltar.classList.remove('display_none');

        switch (tipo) {
            case 'associacao':
                blocoTipoAssociacao.classList.remove('display_none');
                break;
            case 'cliente':
                blocoTipoCliente.classList.remove('display_none');
                break;
            case 'bug':
                blocoHeader.classList.remove('display_none');
                blocoTipoBug.classList.remove('display_none');
                break;
            case 'feature':
            case 'outro':
                blocoHeader.classList.remove('display_none');
                blocoTipoOutro.classList.remove('display_none');
                blocoFooter.classList.add('display_none');
                break;
            case 'criacao':
                blocoTipoCriacao.classList.remove('display_none');
                botaoSalvar.classList.add('display_none');
                blocoFooter.classList.add('display_none');
                blocoHeader.classList.remove('display_none');
                break;
            case 'sorteio':
                blocoTipoSorteio.classList.remove('display_none');
                blocoFooter.classList.add('display_none');
                blocoHeader.classList.remove('display_none');
                break;
            case 'evento':
                blocoTipoEvento.classList.remove('display_none');
                blocoHeader.classList.remove('display_none');
                break;
            case 'campanha':
                blocoTipoCampanha.classList.remove('display_none');
                blocoHeader.classList.remove('display_none');
                break;
            case 'brinde':
                blocoTipoBrinde.classList.remove('display_none');
                blocoHeader.classList.remove('display_none');
                break;
            case 'indicacao':
                blocoTipoIndicacao.classList.remove('display_none');
                blocoHeader.classList.remove('display_none');
                break;
            case 'autoindicacao':
                blocoTipoAutoindicacao.classList.remove('display_none');
                blocoHeader.classList.remove('display_none');
                break;
            case 'cotacao_automovel':
                blocoTipoCotacaoAutomovel.classList.remove('display_none');
                blocoHeader.classList.remove('display_none');
                break;
            case 'cotacao_produto':
                blocoTipoCotacaoProduto.classList.remove('display_none');
                blocoHeader.classList.remove('display_none');
                break;
            case 'auditoria':
                blocoTipoAuditoria.classList.remove('display_none');
                blocoHeader.classList.remove('display_none');
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
        if (inputSorteioMotivacao.value == 'outro') {
            blocoSorteioMotivacaoOutro.classList.remove('display_none');
            inputSorteioMotivacaoOutro.focus();
            return;
        }
        blocoSorteioMotivacaoOutro.classList.add('display_none');
    };
    const entregaOutro = () => {
        if (inputSorteioPremioEntrega.value == 'outro') {
            blocoSorteioEntregaOutro.classList.remove('display_none');
            inputSorteioPremioEntregaOutro.focus();
            return;
        }
        blocoSorteioEntregaOutro.classList.add('display_none');
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
    const blocoDominioLogin = $('#bloco_dominio_login');
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
    const blocoApp = $('#bloco_app');

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
    const blocoEmpresaEspecifica = $('#bloco_empresa_especifica');
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
    | EMPRESA FÍSICA INDICAÇÃO
    |--------------------------------------------------------------------------
    */
    const blocoIndicacaoEndereco = $('#bloco_indicacao_endereco');
    const botaoEmpresaLojaFisica = $('#input_indicacao_empresa_loja_fisica');
    botaoEmpresaLojaFisica.addEventListener('change', () => {
        if (botaoEmpresaLojaFisica.checked) {
            blocoIndicacaoEndereco.classList.remove('display_none');
            return;
        }
        blocoIndicacaoEndereco.classList.add('display_none');
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
            blocoAutoIndicacaoEndereco.classList.remove('display_none');
            return;
        }
        blocoAutoIndicacaoEndereco.classList.add('display_none');
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
            blocoAuditoriaEndereco.classList.remove('display_none');
            return;
        }
        blocoAuditoriaEndereco.classList.add('display_none');
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
        let abrir = false;

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
                abrir = true;
                break;
            case 'outro':
            case 'feature':
                valido = await validarDadoOutro();
                body = await montarDadoOutro();
                abrir = true;
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

        await adicionarNovaDemanda(primeiraColuna.querySelector('.conteudo'), json.dado, abrir);
        contarTarefaDemanda(primeiraColuna);
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
            if (inputDominioTipo.value == 'dominio' || inputDominioTipo.value == 'subdominio') {
                dominioLink = inputDominioProprio.value;
            } else if (inputDominioTipo.value == 'temvantagens' || inputDominioTipo.value == 'temmaisvantagens') {
                dominioLink = inputDominioSub.value;
            }

            let texto = `
                <p>O clube deve ter os seguintes menus:</p>
                    <ul>
            `;
            $$('#bloco_menu_clube input:checked').forEach(item => {
                texto += `<li>${item.value}</li>`;
            });
            texto += `</ul>`;
            if (inputWebView.checked) {
                texto += `<p>Deve tirar o botão de sair do Clube porque ele será usado apenas com WebView</p>`;
            }
            texto += inputTexto.value;

            const body = new FormData();
            body.append('tipo', inputTipo.value);
            body.append('titulo', 'Novo clube de vantagens');
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaCliente));
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
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaAssociacao));
            body.append('titulo', 'Novo site para associação');
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
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaOutro));
            body.append('empresa', inputEmpresaOutro.value);

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
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaBug));
            body.append('empresa', inputEmpresaBug.value);
            body.append('critico', inputBugCritico.value);
            body.append('texto', inputTexto.value);

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
            if (inputTitulo.value == '') {
                mensagem = 'Digite um título para a demanda.';
            } else if (inputEmpresaSorteio.value == '') {
                mensagem = 'Escolha uma empresa para continuar.';
            } else if (inputSorteioDataInicio.value == '') {
                mensagem = 'Digite a data de início da sorteio.';
            } else if (inputSorteioDataFinal.value == '') {
                mensagem = 'Digite a data final do sorteio.';
            } else if (inputSorteioDataSorteio.value == '') {
                mensagem = 'Digite a data que será o sorteio.';
            } else if (inputSorteioComoParticipar.value == '') {
                mensagem = 'Digite as normas para o usuário participar do sorteio.';
            } else if (inputSorteioMotivacao.value == '') {
                mensagem = 'Escolha a motivação do sorteio.';
            } else if (inputSorteioMotivacao.value == 'outro' && inputSorteioMotivacaoOutro.value == '') {
                mensagem = 'Digite a motivação do sorteio.';
            } else if (inputSorteioPremioItem.value == '') {
                mensagem = 'Digite qual item vai ser sorteado.';
            } else if (inputSorteioPremioCompra.value == '') {
                mensagem = 'Escolha quem vai comprar o prémio.';
            } else if (inputSorteioPremioEntrega.value == '') {
                mensagem = 'Escolha a forma de entrega do prémio.';
            } else if (inputSorteioPremioEntrega.value == 'outro' && inputSorteioPremioEntregaOutro.value == '') {
                mensagem = 'Digite a forma de entrega do prémio.';
            } else if (inputSorteioTexto.value == '') {
                mensagem = 'Digite a descrição do sorteio';
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
            body.append('tipo', inputTipo.value);
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaSorteio));
            body.append('empresa', inputEmpresaSorteio.value);
            body.append('titulo', inputTitulo.value);
            body.append('sorteio_inicio', inputSorteioDataInicio.value);
            body.append('sorteio_final', inputSorteioDataFinal.value);
            body.append('sorteio_data', inputSorteioDataSorteio.value);
            body.append('sorteio_como_participar', inputSorteioComoParticipar.value);
            body.append('sorteio_motivacao', inputSorteioMotivacao.value);
            body.append('sorteio_motivacao_outro', inputSorteioMotivacaoOutro.value);
            body.append('sorteio_premio', inputSorteioPremioItem.value);
            body.append('sorteio_premio_compra', inputSorteioPremioCompra.value);
            body.append('sorteio_premio_entrega', inputSorteioPremioEntrega.value);
            body.append('sorteio_premio_entrega_outro', inputSorteioPremioEntregaOutro.value);
            body.append('sorteio_texto', inputSorteioTexto.value);

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

            resolve(testarCampos(mensagemErro));
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
                observacao: 'evento_observacao',
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

            resolve(testarCampos(mensagemErro));
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
                observacao: 'brinde_observacao',
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

            resolve(testarCampos(mensagemErro));
        });
    };

    const montarDadoCampanha = () => {
        return new Promise(resolve => {
            const campos = {
                inicio_divulgacao: 'campanha_inicio_divulgacao',
                fim_divulgacao: 'campanha_fim_divulgacao',
                tema: 'campanha_tema',
                segmento: 'campanha_segmento',
                observacao: 'campanha_observacao',
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
                observacao: 'indicacao_observacao',
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
                observacao: 'autoindicacao_observacao',
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
                observacao: 'cotacao_automovel_observacao',
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
                cpf: 'cotacao_produto_cpf',
                email: 'cotacao_produto_email',
                telefone: 'cotacao_produto_telefone',
                produto_tipo: 'cotacao_produto_tipo',
                marca: 'cotacao_produto_marca',
                modelo: 'cotacao_produto_modelo',
                extra: 'cotacao_produto_extra',
                observacao: 'cotacao_produto_observacao',
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
                observacao: 'auditoria_observacao',
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

            if (elemento.value === '') {
                Alerta.notificacao(campos[campo], false);
                return false;
            }
        }
        return true;
    };

    const montarBody = (campos, inputEmpresa) => {
        const body = new FormData();

        body.append('titulo', inputTitulo.value);
        body.append('tipo', inputTipo.value);
        body.append('empresa_nome', pegarEmpresaNome(inputEmpresa));
        body.append('empresa', inputEmpresa.value);

        for (const key in campos) {
            const campo = campos[key];
            const elemento = document.getElementById('input_' + campo);
            if (elemento.type == 'checkbox') {
                body.append(key, elemento.checked ? 1 : 0);
                continue;
            }
            body.append(key, elemento.value);
        }

        return body;
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
            body.append('tipo', inputTipo.value);
            body.append('empresa_nome', pegarEmpresaNome(inputEmpresaCriacao));
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

    const pegarEmpresaNome = empresa => {
        if (empresa.value == '') {
            return '';
        }
        const bloco = empresa.closest('.bloco_input');
        const input = bloco.querySelector('.input_select_texto');
        if (!input) {
            return '';
        }
        return input.value + ' - ';
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
        blocoTipoAssociacao.classList.add('display_none');
        blocoTipoCliente.classList.add('display_none');
        blocoTipoBug.classList.add('display_none');
        blocoTipoOutro.classList.add('display_none');
        blocoHeader.classList.add('display_none');
        blocoFooter.classList.add('display_none');

        botaoSalvar.classList.add('display_none');
        botaoVoltar.classList.add('display_none');
        botaoFechar.classList.remove('display_none');

        inputTipo.value = '';
        inputTitulo.value = '';
        formValue(inputTexto, '');

        // Cliente
        formValue(inputEmpresaCliente, '');
        formValue(inputDominioTipo, '');
        inputDominioSub.value = '';
        inputDominioProprio.value = '';
        inputConfigurarCdn.checked = false;
        inputLoginApi.checked = false;
        inputDominioLogin.value = '';
        inputApp.checked = false;
        inputWebView.checked = false;

        for (const input of $$('#bloco_menu_clube input:checked')) {
            input.checked = false;
        }

        blocoDominioLogin.classList.add('display_none');
        blocoApp.classList.add('display_none');
        blocoDominioProprio.classList.add('display_none');
        blocoObservacaoDominioProprio.classList.add('display_none');
        blocoObservacaoSubDominioProprio.classList.add('display_none');

        // Bub
        formValue(inputBugLocal, '');
        formValue(inputEmpresaBug, '');
        inputEmpresaEspecifica.checked = false;
        inputBugCritico.checked = false;
        blocoEmpresaEspecifica.classList.add('display_none');
        // Outro
        formValue(inputEmpresaOutro, '');
        // Associacao
        formValue(inputEmpresaAssociacao, '');
        inputDominioSite.value = '';
        inputSocialFacebook.value = '';
        inputSocialInstagram.value = '';
        inputSocialTwitter.value = '';
        inputEmail.value = '';
        inputTelefone.value = '';
        inputEndereco.value = '';
    };
    const limparCriacao = () => {
        blocoTipoCriacao.classList.add('display_none');
        blocoTipoSorteio.classList.add('display_none');
        blocoHeader.classList.add('display_none');
        blocoFooter.classList.add('display_none');

        botaoSalvar.classList.add('display_none');
        botaoVoltar.classList.add('display_none');
        botaoFechar.classList.remove('display_none');

        inputTipo.value = '';
        inputTitulo.value = '';

        // Criacao
        formValue(inputEmpresaCriacao, '');
        inputCriacaoCategoriaSite.checked = false;
        inputCriacaoCategoriaSocial.checked = false;
        inputCriacaoCategoriaImpresso.checked = false;
        inputCriacaoCategoriaKit.checked = false;
        inputCriacaoCategoriaVideo.checked = false;
        inputCriacaoCategoriaOutro.checked = false;
        inputSiteLargura.value = '';
        inputSiteAltura.value = '';
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
        formValue(inputVideoFormato, '');
        inputVideoLargura.value = '';
        inputVideoAltura.value = '';
        formValue(inputSiteTexto, '');
        formValue(inputRedeSocialTexto, '');
        formValue(inputImpressoTexto, '');
        formValue(inputKitTexto, '');
        formValue(inputVideoTexto, '');
        formValue(inputOutroTexto, '');
        blocoCriacaoSite.classList.add('display_none');
        blocoCriacaoFeed.classList.add('display_none');
        blocoCriacaoImpresso.classList.add('display_none');
        blocoCriacaoImpressoOutro.classList.add('display_none');
        blocoCriacaoKit.classList.add('display_none');
        blocoCriacaoOutro.classList.add('display_none');
        blocoCriacaoSocial.classList.add('display_none');
        blocoCriacaoVideo.classList.add('display_none');
        blocoCriacaoVideoDimensao.classList.add('display_none');

        // Sorteio
        formValue(inputEmpresaSorteio, '');
        inputSorteioDataInicio.value = '';
        inputSorteioDataFinal.value = '';
        inputSorteioDataSorteio.value = '';
        inputSorteioComoParticipar.value = '';
        formValue(inputSorteioMotivacao, '');
        inputSorteioMotivacaoOutro.value = '';
        inputSorteioPremioItem.value = '';
        formValue(inputSorteioPremioCompra, '');
        formValue(inputSorteioPremioEntrega, '');
        inputSorteioPremioEntregaOutro.value = '';
        formValue(inputSorteioTexto, '');
        blocoSorteioMotivacaoOutro.classList.add('display_none');
        blocoSorteioEntregaOutro.classList.add('display_none');
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

        inputTipo.value = '';
        inputTitulo.value = '';

        blocoConvenio.classList.remove('display_none');
        blocoHeader.classList.add('display_none');
        blocoFooter.classList.add('display_none');

        botaoSalvar.classList.add('display_none');
        botaoVoltar.classList.add('display_none');
        botaoFechar.classList.remove('display_none');
    };

    const resetarInputs = bloco => {
        bloco.classList.add('display_none');

        bloco.querySelectorAll('input').forEach(item => {
            if (item.type == 'checkbox') {
                item.checked = false;
            }

            if (item.type == 'text') {
                item.value = '';
            }
        });
    };
});
