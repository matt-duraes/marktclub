// @template "painel"
// @painel "app_geral_visualizar"

window.addEventListener('load', () => {
    const parceiro = document.querySelector('#input_visualizar_id').value;
    const listaMapa = $$('.bloco_endereco_geral');
    listaMapa.forEach(bloco => {
        carregarBlocoEndereco(bloco, parceiro);
    });
});
const carregarBlocoEndereco = (bloco, parceiro) => {
    const localPrincipal = $('input[name="local_principal"]', bloco).valor();
    const localSecundario = $('input[name="local_secundario"]', bloco).valor();
    const listaId = [];

    // Endereço
    const botaoEnderecoAbrir = $('.botao_adicionar_endereco', bloco);
    const botaoEnderecoFechar = $('.bloco_endereco_add .fechar', bloco);
    const blocoEndereco = $('.bloco_endereco_add', bloco);

    // Input
    const inputIdEndereco = $('.input_id_endereco', bloco);
    const inputTitulo = $('.bloco_endereco_titulo input', bloco);
    const inputPais = $('.bloco_endereco_pais input', bloco);
    const inputCepBrasil = $('.bloco_endereco_cep_brasil input', bloco);
    const inputCepEstrangeiro = $('.bloco_endereco_cep_estrangeiro input', bloco);
    const inputLogradouro = $('.bloco_endereco_logradouro input', bloco);
    const inputNumero = $('.bloco_endereco_numero input', bloco);
    const inputComplemento = $('.bloco_endereco_complemento input', bloco);
    const inputReferencia = $('.bloco_endereco_referencia input', bloco);
    const inputBairro = $('.bloco_endereco_bairro input', bloco);
    const inputEstadoBrasil = $('.bloco_endereco_estado_brasil input.input_select_value', bloco);
    const inputEstadoEstrangeiro = $('.bloco_endereco_estado_estrangeiro input', bloco);
    const inputCidadeBrasil = $('.bloco_endereco_cidade_brasil input.input_select_value', bloco);
    const inputCidadeEstrangeiro = $('.bloco_endereco_cidade_estrangeiro input', bloco);
    const inputPrincipal = $('.bloco_endereco_principal input', bloco);
    const inputLatitude = $('.bloco_endereco_latitude input', bloco);
    const inputLongitude = $('.bloco_endereco_longitude input', bloco);
    const inputLatLong = $$('.bloco_endereco_latitude input, .bloco_endereco_longitude input', bloco);
    const inputZerar = $$(
        `
            .bloco_endereco_titulo input, .bloco_endereco_cep_brasil input,
            .bloco_endereco_cep_estrangeiro input, .bloco_endereco_logradouro input, .bloco_endereco_numero input,
            .bloco_endereco_complemento input, .bloco_endereco_referencia input, .bloco_endereco_bairro input,
            .bloco_endereco_estado_brasil input, .bloco_endereco_estado_estrangeiro input,
            .bloco_endereco_cidade_brasil input, .bloco_endereco_cidade_estrangeiro input,
            .bloco_endereco_latitude input, .bloco_endereco_longitude input
        `,
        bloco
    );

    // Bloco brasil/estrangeiro
    const blocoBrasil = $$(
        `
            .bloco_endereco_cep_brasil, .bloco_endereco_cidade_brasil,
            .bloco_endereco_estado_brasil, .botao_buscar_endereco_cep
        `,
        bloco
    );
    const blocoEstrangeiro = $$(
        `
            .bloco_endereco_cep_estrangeiro,
            .bloco_endereco_cidade_estrangeiro, .bloco_endereco_estado_estrangeiro
        `,
        bloco
    );

    // Buscar LatLong
    const buscarLatLongTitulo = $('.botao_buscar_latlong_titulo', bloco);
    const buscarLatLongEndereco = $('.botao_buscar_latlong_endereco', bloco);
    const buscarLatLongCentro = $('.botao_buscar_latlong_centro', bloco);

    // Mapa
    const blocoMapa = $('.mapa', bloco);
    const MAPA = {
        mapa: null,
        marker: null,
        latitude: parseFloat('-15.7861882'),
        longitude: parseFloat('-47.9239443'),
    };

    // Cep
    const botaoBuscarEnderecoCep = $('.botao_buscar_endereco_cep', bloco);

    // Salvar/Listar
    const blocoEnderecoErro = $('.bloco_visualizar_erro', bloco);
    const blocoEnderecoZero = $('.bloco_visualizar_zero', bloco);
    const blocoEnderecoLoading = $('.bloco_visualizar_loading', bloco);
    const blocoEnderecoLista = $('.bloco_endereco_lista', bloco);
    const blocoEnderecoPadrao = $('.bloco_visualizar_linha_padrao', bloco);
    const blocoCarregarMais = $('.bloco_visualizar_carregar_mais', bloco);
    const botaoCarregarMais = $('.botao_visualizar_carregar_mais', bloco);
    const botaoSalvar = $('.botao_salvar_endereco', bloco);
    const blocoSalvarOutro = $('.bloco_salvar_outro', bloco);
    const inputSalvarOutro = $('.bloco_salvar_outro input', bloco);

    const zerarFormulario = () => {
        blocoBrasil.aparecer();
        blocoEstrangeiro.sumir();
        blocoSalvarOutro.aparecer();
        inputZerar.valor('');
        inputPrincipal.checked = false;
        inputPais.valor('BR');
        iniciarMapa();
    };

    /*
    |--------------------------------------------------------------------------
    | MAPA
    |--------------------------------------------------------------------------
    */
    const iniciarMapa = async () => {
        const { Map } = await google.maps.importLibrary('maps');
        const option = {
            scrollwheel: false,
            zoom: 13,
            center: { lat: MAPA.latitude, lng: MAPA.longitude },
            disableDefaultUI: false,
            panControl: false,
            zoomControl: false,
            mapId: gerarId('id_mapa'),
        };
        MAPA.mapa = new Map(blocoMapa, option);
    };
    const adicionarMarker = async (latitude, longitude) => {
        MAPA.latitude = latitude;
        MAPA.longitude = longitude;
        inputLatitude.valor(latitude);
        inputLongitude.valor(longitude);

        const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');

        if (MAPA.marker) {
            MAPA.marker.setMap(null);
        }
        MAPA.marker = new AdvancedMarkerElement({
            map: MAPA.mapa,
            position: { lat: MAPA.latitude, lng: MAPA.longitude },
            title: 'Local no mapa',
            gmpDraggable: true,
        });

        MAPA.marker.addListener('dragend', () => {
            const posicao = MAPA.marker.position;
            inputLatitude.valor(posicao.lat);
            inputLongitude.valor(posicao.lng);
            MAPA.latitude = posicao.lat;
            MAPA.longitude = posicao.lng;
        });
    };
    const novaPosicaoMapa = () => {
        const posicao = new google.maps.LatLng(MAPA.latitude, MAPA.longitude);
        MAPA.mapa.panTo(posicao);
    };

    /*
    |--------------------------------------------------------------------------
    | ABRIR/FECHAR POPUP
    |--------------------------------------------------------------------------
    */
    botaoEnderecoAbrir.addEventListener('click', () => {
        zerarFormulario();
        abrirBlocoEndereco();
    });
    const abrirBlocoEndereco = () => {
        blocoEndereco.aparecer();
        setTimeout(() => {
            blocoEndereco.classe('ativo', true);
        }, 40);
        setTimeout(() => {
            inputTitulo.focus();
        }, 300);
    };

    botaoEnderecoFechar.addEventListener('click', () => {
        fecharBlocoEndereco();
    });
    const fecharBlocoEndereco = () => {
        blocoEndereco.classe('ativo', false);
        setTimeout(() => {
            blocoEndereco.sumir();
            zerarFormulario();
        }, 300);
    };

    /*
    |--------------------------------------------------------------------------
    | PAIS/CEP/ESTADO/CIDADE
    |--------------------------------------------------------------------------
    */
    inputPais.evento('formChange', () => {
        inputTitulo.focus();
        setarDadosPorPais();
    });
    const setarDadosPorPais = () => {
        const pais = inputPais.valor();
        if (pais == 'BR') {
            blocoBrasil.aparecer();
            blocoEstrangeiro.sumir();
            return;
        }
        blocoBrasil.sumir();
        blocoEstrangeiro.aparecer();
    };
    buscarEnderecoPeloCep(
        inputCepBrasil,
        inputLogradouro,
        inputNumero,
        inputBairro,
        inputCidadeBrasil,
        inputEstadoBrasil,
        undefined,
        botaoBuscarEnderecoCep
    );
    inputEstadoBrasil.evento('formChange', () => {
        buscarCidadePeloEstado(inputCidadeBrasil, inputEstadoBrasil.valor(), '', 'Escolha uma cidade');
    });

    /*
    |--------------------------------------------------------------------------
    | BUSCAR LAT/LONG
    |--------------------------------------------------------------------------
    */
    inputLatLong.evento('change', () => {
        const latitude = parseFloat(inputLatitude.valor());
        const longitude = parseFloat(inputLongitude.valor());
        if (vazio(latitude) && vazio(longitude)) {
            return;
        }
        adicionarMarker(latitude, longitude);
        novaPosicaoMapa();
    });
    buscarLatLongTitulo.evento('click', async () => {
        const titulo = inputTitulo.valor();
        const pais = inputPais.valor();
        if (vazio(titulo) || vazio(pais)) {
            Alerta.notificacao('Você deve selecionar o país e passar um título para continuar.', false);
            return;
        }
        buscarLatLongBackend({
            titulo,
            pais,
        });
    });
    buscarLatLongEndereco.evento('click', () => {
        const pais = inputPais.valor();
        const brasil = pais == 'BR';
        const cep = brasil ? inputCepBrasil.valor() : inputCepEstrangeiro.valor();
        const logradouro = inputLogradouro.valor();
        const numero = inputNumero.valor();
        const bairro = inputBairro.valor();
        const cidade = brasil ? inputCidadeBrasil.valor() : inputCidadeEstrangeiro.valor();
        const estado = brasil ? inputEstadoBrasil.valor() : inputEstadoEstrangeiro.valor();
        if (vazio(pais) || vazio(cidade) || vazio(estado)) {
            Alerta.notificacao('Você deve selecionar o país, cidade e estado para continuar.', false);
            return;
        }
        buscarLatLongBackend({
            pais,
            cep,
            logradouro,
            numero,
            bairro,
            cidade,
            estado,
        });
    });
    const buscarLatLongBackend = async body => {
        const latitude = inputLatitude.valor();
        const longitude = inputLongitude.valor();
        if (latitude != '' && longitude != '') {
            if (
                !(await Alerta.confirmar('Mudar localização', 'Tem certeza que deseja mudar a localização atual?', '!'))
            ) {
                return;
            }
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/sistema-endereco/buscar-geolocalizacao',
            body,
            'Erro ao buscar a localização, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        adicionarMarker(parseFloat(resposta.dado.latitude), parseFloat(resposta.dado.longitude));
        novaPosicaoMapa();
    };

    buscarLatLongCentro.evento('click', async () => {
        const latitude = inputLatitude.valor();
        const longitude = inputLongitude.valor();
        if (latitude != '' && longitude != '') {
            if (
                !(await Alerta.confirmar('Mudar localização', 'Tem certeza que deseja mudar a localização atual?', '!'))
            ) {
                return;
            }
        }
        const latitudeNova = MAPA.mapa.getCenter().lat();
        const longitudeNova = MAPA.mapa.getCenter().lng();
        adicionarMarker(latitudeNova, longitudeNova);
    });

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    let paginaAtual;
    const listarEndereco = async (pagina, estado, cidade, titulo) => {
        paginaAtual = pagina;
        blocoEnderecoLoading.aparecer();
        blocoEnderecoZero.sumir();
        blocoEnderecoErro.sumir();
        blocoCarregarMais.sumir();

        if (pagina == 1) {
            $$('.bloco_visualizar_linha', bloco).remover();
        }

        const resposta = await ajaxPost(
            LINK + '/sistema-endereco/buscar-lista',
            {
                pagina,
                /* eslint-disable */
                local_principal: localPrincipal,
                local_secundario: localSecundario,
                /* eslint-enable */
                vinculo: parceiro,
                titulo: titulo == undefined ? '' : titulo,
                estado: estado == undefined ? '' : estado,
                cidade: cidade == undefined ? '' : cidade,
            },
            'Erro ao buscar lista de endereço'
        );
        blocoEnderecoLoading.sumir();
        if (false === resposta) {
            blocoEnderecoErro.aparecer();
            return;
        } else if (resposta.dado.lista.length == 0) {
            blocoEnderecoZero.aparecer();
            return;
        }
        if (resposta.dado.pagina.total <= 1 || resposta.dado.pagina.total == pagina) {
            blocoCarregarMais.sumir();
        } else {
            blocoCarregarMais.aparecer();
        }
        for (const endereco of resposta.dado.lista) {
            adicionarNovoEndereco(endereco);
        }
        rolarScroolParaTopo();
    };
    listarEndereco(1);

    const botaoBuscar = $('.botao_buscar_endereco', bloco);
    const inputBuscarPesquisa = $('.input_buscar_pesquisa', bloco);
    const inputBuscarEstado = $('.input_buscar_estado', bloco);
    const inputBuscarCidade = $('.input_buscar_cidade', bloco);
    const inputBuscarTodos = $$('.input_buscar_pesquisa, .input_buscar_estado, .input_buscar_cidade', bloco);
    botaoBuscar.evento('click', () => {
        listarEndereco(1, inputBuscarEstado.valor(), inputBuscarCidade.valor(), inputBuscarPesquisa.valor());
    });
    inputBuscarTodos.evento('enter', () => {
        listarEndereco(1, inputBuscarEstado.valor(), inputBuscarCidade.valor(), inputBuscarPesquisa.valor());
    });
    botaoCarregarMais.evento('click', () => {
        listarEndereco(
            paginaAtual + 1,
            inputBuscarEstado.valor(),
            inputBuscarCidade.valor(),
            inputBuscarPesquisa.valor()
        );
    });

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    botaoSalvar.evento('click', async () => {
        const id = inputIdEndereco.valor();
        const body = montarBodyEndereco(id);
        if (!(await validarEndereco(body))) {
            return;
        }

        const uri = id != '' ? '/sistema-endereco/atualizar-endereco/' + id : '/sistema-endereco/salvar-endereco';
        Loading.show();
        const resposta = await ajaxPost(LINK + uri, body, 'Ocorreu um erro ao salvar o endereço.');
        Loading.hide();
        if (false === resposta) {
            return;
        }
        if (vazio(id)) {
            Alerta.notificacao('Endereço salvo com sucesso.', true);
            acaoSalvarEndereco(resposta.dado);
            return;
        }
        Alerta.notificacao('Endereço atualizado com sucesso.', true);
        acaoAtualizarEndereco(id);
    });
    const acaoSalvarEndereco = dado => {
        blocoEnderecoZero.sumir();
        adicionarNovoEndereco(dado);
        rolarScroolParaTopo();
        if (!inputSalvarOutro.checked) {
            fecharBlocoEndereco();
            return;
        }
        zerarFormulario();
    };
    const acaoAtualizarEndereco = id => {
        const linha = $('#id_endereco_' + id);
        const brasil = inputPais.valor() == 'BR';

        const cidade = brasil ? inputCidadeBrasil.valor() : inputCidadeEstrangeiro.valor();
        $('.cidade', linha).texto(cidade);

        $('.barra', linha).texto('');
        const estado = brasil ? inputEstadoBrasil.valor() : inputEstadoEstrangeiro.valor();
        if (estado != '') {
            $('.barra', linha).texto('/');
            $('.estado', linha).texto(estado);
        }
        const bairro = inputBairro.valor();
        if (bairro != '') {
            $('.traco', linha).texto(' - ');
            $('.bairro', linha).texto(bairro);
        }

        $('h1', linha).texto(inputTitulo.valor());
    };

    const montarBodyEndereco = id => {
        const pais = inputPais.valor();
        const brasil = pais == 'BR';
        const body = {
            pais,
            titulo: inputTitulo.valor(),
            cep: brasil ? inputCepBrasil.valor() : inputCepEstrangeiro.valor(),
            logradouro: inputLogradouro.valor(),
            numero: inputNumero.valor(),
            complemento: inputComplemento.valor(),
            referencia: inputReferencia.valor(),
            bairro: inputBairro.valor(),
            estado: brasil ? inputEstadoBrasil.valor() : inputEstadoEstrangeiro.valor(),
            cidade: brasil ? inputCidadeBrasil.valor() : inputCidadeEstrangeiro.valor(),
            latitude: inputLatitude.valor(),
            longitude: inputLongitude.valor(),
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
    const validarEndereco = async body => {
        return new Promise(resolve => {
            let retorno = true;
            if (vazio(body.pais)) {
                Alerta.notificacao('Escolha um país para continuar.', false);
                retorno = false;
            } else if (vazio(body.titulo)) {
                Alerta.notificacao('Digite um título para continuar.', false);
                retorno = false;
            } else if (vazio(body.cep)) {
                Alerta.notificacao('Digite um CEP para continuar.', false);
                retorno = false;
            } else if (vazio(body.logradouro)) {
                Alerta.notificacao('Digite um logradouro para continuar.', false);
                retorno = false;
            } else if (vazio(body.bairro)) {
                Alerta.notificacao('Digite um bairro para continuar.', false);
                retorno = false;
            } else if (vazio(body.estado) && body.pais == 'BR') {
                Alerta.notificacao('Escolha um estado para continuar.', false);
                retorno = false;
            } else if (vazio(body.cidade) && body.pais == 'BR') {
                Alerta.notificacao('Escolha uma cidade para continuar.', false);
                retorno = false;
            } else if (vazio(body.cidade)) {
                Alerta.notificacao('Digite uma cidade para continuar.', false);
                retorno = false;
            } else if (vazio(body.latitude) || vazio(body.longitude)) {
                Alerta.notificacao('Defina o local do endereço no mapa para continuar.', false);
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
            Alerta.notificacao('ID do endereço não existe.', false);
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/sistema-endereco/buscar-unico/' + id,
            undefined,
            'Erro ao buscar endereço, por favor, tente novamente.'
        );
        Loading.hide();

        if (false === resposta) {
            return;
        }
        abrirBlocoEndereco();
        blocoSalvarOutro.sumir();
        const brasil = resposta.dado.pais == 'BR';
        inputIdEndereco.valor(resposta.dado.id);
        inputTitulo.valor(resposta.dado.titulo);
        inputPais.valor(resposta.dado.pais);

        if (brasil) {
            blocoBrasil.aparecer();
            blocoEstrangeiro.sumir();
            inputCepBrasil.valor(resposta.dado.cep);
            inputEstadoBrasil.valor(resposta.dado.estado);
            buscarCidadePeloEstado(inputCidadeBrasil, resposta.dado.estado, resposta.dado.cidade, 'Escolha uma cidade');
        } else {
            blocoBrasil.sumir();
            blocoEstrangeiro.aparecer();
            inputCepEstrangeiro.valor(resposta.dado.cep);
            inputCidadeEstrangeiro.valor(resposta.dado.cidade);
            inputEstadoEstrangeiro.valor(resposta.dado.estado);
        }
        inputLogradouro.valor(resposta.dado.logradouro);
        inputNumero.valor(resposta.dado.numero);
        inputComplemento.valor(resposta.dado.complemento);
        inputReferencia.valor(resposta.dado.referencia);
        inputBairro.valor(resposta.dado.bairro);
        inputPrincipal.valor(resposta.dado.principal == 'sim');
        inputLatitude.valor(resposta.dado.latitude);
        inputLongitude.valor(resposta.dado.longitude);

        iniciarMapa();
        adicionarMarker(resposta.dado.latitude, resposta.dado.longitude);
    };
    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    */
    const deletarEndereco = linha => {
        const id = linha.attr('data-id');
        if (!inArray(id, listaId)) {
            Alerta.notificacao('ID do endereço não existe.', false);
            return;
        }
        linha.sumir();
        const resposta = ajaxPost(
            LINK + '/sistema-endereco/deletar-endereco/' + id,
            undefined,
            'Erro ao deletar endereço, por favor, tente novamente.'
        );
        if (false === resposta) {
            linha.aparecer();
        }
        linha.remover();
        const quantidade = $$('.bloco_visualizar_linha', blocoEnderecoLista).length;
        if (quantidade == 0) {
            blocoEnderecoZero.aparecer();
        }
    };

    /*
    |--------------------------------------------------------------------------
    | GERAL
    |--------------------------------------------------------------------------
    */
    blocoEnderecoLista.evento('click', async e => {
        const target = e.target;
        if (target.classe('botao_visualizar_editar', '?') || target.closest('.botao_visualizar_editar')) {
            abrirBlocoParaEditar(target.closest('.bloco_visualizar_linha').attr('data-id'));
        } else if (target.classe('botao_visualizar_deletar', '?') || target.closest('.botao_visualizar_deletar')) {
            if (
                !(await Alerta.confirmar(
                    'Deletar endereço',
                    'Tem certeza que deseja deletar esse endereço? Essa ação não poderá ser desfeito.',
                    false
                ))
            ) {
                return;
            }
            deletarEndereco(target.closest('.bloco_visualizar_linha'));
        }
    });

    const rolarScroolParaTopo = () => {
        blocoEnderecoLista.scrollTop = 0;
    };
    const adicionarNovoEndereco = dado => {
        const clone = blocoEnderecoPadrao.clonar();
        clone.attr('data-id', dado.id);
        clone.attr('id', 'id_endereco_' + dado.id);
        listaId.push(dado.id);
        $('h1', clone).texto(dado.titulo);
        $('.cidade', clone).texto(dado.cidade);
        if (!vazio(dado.estado)) {
            $('.barra', clone).texto('/');
            $('.estado', clone).texto(dado.estado);
        }
        if (!vazio(dado.bairro)) {
            $('.traco', clone).texto(' - ');
            $('.bairro', clone).texto(dado.bairro);
        }
        blocoEnderecoLista.inicio(clone);
    };
};
