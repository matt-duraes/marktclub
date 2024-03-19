window.addEventListener('load', () => {
    const parceiro = document.querySelector('#input_visualizar_id').value;
    const listaContato = $$('.bloco_contato_geral');
    listaContato.forEach(bloco => {
        carregarBlocoContato(bloco, parceiro);
    });
});
const carregarBlocoContato = (bloco, parceiro) => {
    const localPrincipal = $('input[name="local_principal"]', bloco).valor();
    const localSecundario = $('input[name="local_secundario"]', bloco).valor();
    const listaId = [];

    // Popup
    const botaoContatoAbrir = $('.botao_adicionar_contato', bloco);
    const botaoContatoFechar = $('.bloco_contato_add .fechar', bloco);
    const blocoContato = $('.bloco_contato_add', bloco);

    // Input
    const inputIdContato = $('.input_id_contato', bloco);
    const inputTitulo = $('.bloco_contato_titulo input', bloco);
    const inputNome = $('.bloco_contato_nome input', bloco);
    const inputCpf = $('.bloco_contato_cpf input', bloco);
    const inputTipo = $('.bloco_contato_tipo input.input_select_value', bloco);
    const inputTelefoneDdi = $('.bloco_contato_telefone_ddi input', bloco);
    const inputTelefoneNumero = $('.bloco_contato_telefone_numero input', bloco);
    const inputEmail = $('.bloco_contato_email input', bloco);
    const inputWhatsapp = $('.bloco_contato_whatsapp input', bloco);
    const inputPrincipal = $('.bloco_contato_principal input', bloco);
    const inputZerar = $$(
        `
            .bloco_contato_titulo input, .bloco_contato_nome input, .bloco_contato_cpf input,
            .bloco_contato_tipo input.input_select_value, .bloco_contato_telefone_ddi input,
            .bloco_contato_telefone_numero input, .bloco_contato_email input,
            .bloco_contato_whatsapp input, .bloco_contato_principal input
        `,
        bloco
    );

    // Bloco telefone/email
    const blocoTelefone = $$('.bloco_contato_telefone_ddi, bloco_contato_telefone_numero', bloco);
    const blocoEmail = $$('.bloco_contato_email input', bloco);

    // Salvar/Listar
    const blocoContatoErro = $('.bloco_visualizar_erro', bloco);
    const blocoContatoZero = $('.bloco_visualizar_zero', bloco);
    const blocoContatoLoading = $('.bloco_visualizar_loading', bloco);
    const blocoContatoLista = $('.bloco_contato_lista', bloco);
    const blocoContatoPadrao = $('.bloco_visualizar_linha_padrao', bloco);
    const blocoCarregarMais = $('.bloco_visualizar_carregar_mais', bloco);
    const botaoCarregarMais = $('.botao_visualizar_carregar_mais', bloco);
    const botaoSalvar = $('.botao_salvar_contato', bloco);
    const blocoSalvarOutro = $('.bloco_salvar_outro', bloco);
    const inputSalvarOutro = $('.bloco_salvar_outro input', bloco);

    const zerarFormulario = () => {
        blocoTelefone.sumir();
        blocoEmail.sumir();
        blocoSalvarOutro.aparecer();
        inputZerar.valor('');
    };

    /*
    |--------------------------------------------------------------------------
    | ABRIR/FECHAR POPUP
    |--------------------------------------------------------------------------
    */
    botaoContatoAbrir.addEventListener('click', () => {
        zerarFormulario();
        abrirBlocoContato();
    });
    const abrirBlocoContato = () => {
        blocoContato.aparecer();
        setTimeout(() => {
            blocoContato.classe('ativo', true);
        }, 40);
        setTimeout(() => {
            inputTitulo.focus();
        }, 300);
    };

    botaoContatoFechar.addEventListener('click', () => {
        fecharBlocoContato();
    });
    const fecharBlocoContato = () => {
        blocoContato.classe('ativo', false);
        setTimeout(() => {
            blocoContato.sumir();
            zerarFormulario();
        }, 300);
    };

    /*
    |--------------------------------------------------------------------------
    | MUDAR TIPO
    |--------------------------------------------------------------------------
    */
    inputTipo.evento('formChange', () => {
        const tipo = inputTipo.valor();
        if (tipo == 'telefone') {
            blocoTelefone.aparecer();
            blocoEmail.sumir();
            return;
        }
        blocoTelefone.sumir();
        blocoEmail.aparecer();
    });

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    let paginaAtual;
    const listarContato = async (pagina, pesquisa, tipo) => {
        paginaAtual = pagina;
        blocoContatoLoading.aparecer();
        blocoContatoZero.sumir();
        blocoContatoErro.sumir();
        blocoCarregarMais.sumir();

        if (pagina == 1) {
            $$('.bloco_visualizar_linha', bloco).remover();
        }

        const resposta = await ajaxPost(
            LINK + '/sistema-contato/buscar-lista',
            {
                pagina,
                /* eslint-disable */
                local_principal: localPrincipal,
                local_secundario: localSecundario,
                /* eslint-enable */
                vinculo: parceiro,
                pesquisa: pesquisa == undefined ? '' : pesquisa,
                tipo: tipo == undefined ? '' : tipo,
            },
            'Erro ao buscar lista de contato'
        );
        blocoContatoLoading.sumir();
        if (false === resposta) {
            blocoContatoErro.aparecer();
            return;
        } else if (resposta.dado.lista.length == 0) {
            blocoContatoZero.aparecer();
            return;
        }
        if (resposta.dado.pagina.total <= 1 || resposta.dado.pagina.total == pagina) {
            blocoCarregarMais.sumir();
        } else {
            blocoCarregarMais.aparecer();
        }
        for (const contato of resposta.dado.lista) {
            adicionarNovoContato(contato);
        }
        rolarScroolParaTopo();
    };
    listarContato(1);

    const botaoBuscar = $('.botao_buscar_contato', bloco);
    const inputBuscarPesquisa = $('.input_buscar_pesquisa', bloco);
    botaoBuscar.evento('click', () => {
        listarContato(1, inputBuscarPesquisa.valor());
    });
    inputBuscarPesquisa.evento('enter', () => {
        listarContato(1, inputBuscarPesquisa.valor());
    });
    botaoCarregarMais.evento('click', () => {
        listarContato(paginaAtual + 1, inputBuscarPesquisa.valor());
    });

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    botaoSalvar.evento('click', async () => {
        const id = inputIdContato.valor();
        const body = montarBodyContato(id);
        if (!(await validarContato(body))) {
            return;
        }

        const uri = id != '' ? '/sistema-contato/atualizar-contato/' + id : '/sistema-contato/salvar-contato';
        Loading.show();
        const resposta = await ajaxPost(LINK + uri, body, 'Ocorreu um erro ao salvar o contato.');
        Loading.hide();
        if (false === resposta) {
            return;
        }
        if (vazio(id)) {
            Alerta.notificacao('Contato salvo com sucesso.', true);
            acaoSalvarContato(resposta.dado);
            return;
        }
        Alerta.notificacao('Contato atualizado com sucesso.', true);
        acaoAtualizarContato(id);
    });
    const acaoSalvarContato = dado => {
        blocoContatoZero.sumir();
        adicionarNovoContato(dado);
        rolarScroolParaTopo();
        if (!inputSalvarOutro.checked) {
            fecharBlocoContato();
            return;
        }
        zerarFormulario();
    };
    const acaoAtualizarContato = id => {
        const linha = $('#id_contato_' + id);
        $('h1', linha).texto(inputTitulo.valor());
    };

    const montarBodyContato = id => {
        const tipo = inputTipo.valor();
        let valor = '';
        if (tipo == 'telefone') {
            valor = '+' + inputTelefoneDdi.valor() + ' ' + inputTelefoneNumero.valor();
        } else if (tipo == 'email') {
            valor = inputEmail.valor();
        }
        const body = {
            titulo: inputTitulo.valor(),
            nome: inputNome.valor(),
            cpf: inputCpf.valor(),
            tipo: inputTipo.valor(),
            valor,
            whatsapp: inputWhatsapp.checked ? 'sim' : 'nao',
            principal: inputPrincipal.checked ? 'sim' : 'nao',
        };
        if (vazio(id)) {
            /* eslint-disable */
            body.local_principal = localPrincipal;
            body.local_secundario = localSecundario;
            /* eslint-enable */
            body.vinculo = parceiro;
        }
        return body;
    };
    const validarContato = async body => {
        return new Promise(resolve => {
            let retorno = true;
            if (vazio(body.titulo)) {
                Alerta.notificacao('Digite um título para continuar.', false);
                retorno = false;
            } else if (vazio(body.tipo)) {
                Alerta.notificacao('Escolha um tipo para continuar.', false);
                retorno = false;
            } else if (tipo == 'telefone' && vazio(body.valor)) {
                Alerta.notificacao('Digite um telefone para continuar.', false);
                retorno = false;
            } else if (tipo == 'email' && vazio(body.valor)) {
                Alerta.notificacao('Digite um e-mail para continuar.', false);
                retorno = false;
            }
            resolve(retorno);
        });
    };
    /*
    |--------------------------------------------------------------------------
    | EDITAR
    |--------------------------------------------------------------------------
    */
    const abrirBlocoParaEditar = async id => {
        if (!inArray(id, listaId)) {
            Alerta.notificacao('ID do contato não existe.', false);
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/sistema-contato/buscar-unico/' + id,
            undefined,
            'Erro ao buscar contato, por favor, tente novamente.'
        );
        Loading.hide();

        if (false === resposta) {
            return;
        }
        abrirBlocoContato();
        blocoSalvarOutro.sumir();
        const tipo = resposta.dado.tipo;
        inputIdContato.valor(resposta.dado.id);
        inputTitulo.valor(resposta.dado.titulo);
        inputNome.valor(resposta.dado.nome);
        inputCpf.valor(resposta.dado.cpf);
        inputTipo.valor(tipo);
        if (tipo == 'telefone') {
            inputTelefoneDdi.valor(resposta.dado.valor[0]);
            inputTelefoneNumero.valor(resposta.dado.valor[1]);
        } else {
            inputEmail.valor(resposta.dado.valor);
        }
        inputWhatsapp.valor(resposta.dado.whatsapp == 'sim');
        inputPrincipal.valor(resposta.dado.principal == 'sim');
    };
    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    */
    const deletarContato = linha => {
        const id = linha.attr('data-id');
        if (!inArray(id, listaId)) {
            Alerta.notificacao('ID do contato não existe.', false);
            return;
        }
        linha.sumir();
        const resposta = ajaxPost(
            LINK + '/sistema-contato/deletar-contato/' + id,
            undefined,
            'Erro ao deletar contato, por favor, tente novamente.'
        );
        if (false === resposta) {
            linha.aparecer();
        }
        linha.remover();
        const quantidade = $$('.bloco_visualizar_linha', blocoContatoLista).length;
        if (quantidade == 0) {
            blocoContatoZero.aparecer();
        }
    };

    /*
    |--------------------------------------------------------------------------
    | GERAL
    |--------------------------------------------------------------------------
    */
    blocoContatoLista.evento('click', async e => {
        const target = e.target;
        if (target.classe('botao_visualizar_editar', '?') || target.closest('.botao_visualizar_editar')) {
            abrirBlocoParaEditar(target.closest('.bloco_visualizar_linha').attr('data-id'));
        } else if (target.classe('botao_visualizar_deletar', '?') || target.closest('.botao_visualizar_deletar')) {
            if (
                !(await Alerta.confirmar(
                    'Deletar contato',
                    'Tem certeza que deseja deletar esse contato? Essa ação não poderá ser desfeito.',
                    false
                ))
            ) {
                return;
            }
            deletarContato(target.closest('.bloco_visualizar_linha'));
        }
    });

    const rolarScroolParaTopo = () => {
        blocoContatoLista.scrollTop = 0;
    };
    const adicionarNovoContato = dado => {
        const clone = blocoContatoPadrao.clonar();
        clone.attr('data-id', dado.id);
        clone.attr('id', 'id_contato_' + dado.id);
        listaId.push(dado.id);
        $('h1', clone).texto(dado.titulo);
        blocoContatoLista.inicio(clone);
    };
};
