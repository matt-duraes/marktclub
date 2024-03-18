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
    const tipo = bloco.querySelector('input[name="tipo"]').valor();
    const local = bloco.querySelector('input[name="local"]').valor();

    // Endereço
    const botaoEnderecoAbrir = bloco.querySelector('.botao_adicionar_endereco');
    const botaoEnderecoFechar = bloco.querySelector('.bloco_endereco_add .fechar');
    const blocoEndereco = bloco.querySelector('.bloco_endereco_add');

    // Input
    const inputTitulo = bloco.querySelector('.bloco_endereco_titulo input');
    const inputTelefone = bloco.querySelector('.bloco_endereco_telefone input');
    const inputPais = bloco.querySelector('.bloco_endereco_pais input');
    const inputCepBrasil = bloco.querySelector('.bloco_endereco_cep_brasil input');
    const inputCepEstrangeiro = bloco.querySelector('.bloco_endereco_cep_estrangeiro input');
    const inputLogradouro = bloco.querySelector('.bloco_endereco_logradouro input');
    const inputNumero = bloco.querySelector('.bloco_endereco_numero input');
    const inputComplemento = bloco.querySelector('.bloco_endereco_complemento input');
    const inputReferencia = bloco.querySelector('.bloco_endereco_referencia input');
    const inputBairro = bloco.querySelector('.bloco_endereco_bairro input');
    const inputEstadoBrasil = bloco.querySelector('.bloco_endereco_estado_brasil input.input_select_value');
    const inputEstadoEstrangeiro = bloco.querySelector('.bloco_endereco_estado_estrangeiro input');
    const inputCidadeBrasil = bloco.querySelector('.bloco_endereco_cidade_brasil input.input_select_value');
    const inputCidadeEstrangeiro = bloco.querySelector('.bloco_endereco_cidade_estrangeiro input');
    const inputLatitude = bloco.querySelector('.bloco_endereco_latitude input');
    const inputLongitude = bloco.querySelector('.bloco_endereco_longitude input');
    const inputLatLong = bloco.querySelectorAll('.bloco_endereco_latitude input, .bloco_endereco_longitude input');
    const inputZerar = bloco.querySelectorAll(`
        .bloco_endereco_titulo input, .bloco_endereco_telefone input, .bloco_endereco_cep_brasil input,
        .bloco_endereco_cep_estrangeiro input, .bloco_endereco_logradouro input, .bloco_endereco_numero input,
        .bloco_endereco_complemento input, .bloco_endereco_referencia input, .bloco_endereco_bairro input,
        .bloco_endereco_estado_brasil inpu, .input_select_value, .bloco_endereco_estado_estrangeiro input,
        .bloco_endereco_cidade_brasil inpu, .input_select_value, .bloco_endereco_cidade_estrangeiro input,
        .input_geolocalizacao_titulo, .bloco_endereco_latitude input, .bloco_endereco_longitude input
    `);

    // Bloco input
    const blocoCepBrasil = bloco.querySelector('.bloco_endereco_cep_brasil');
    const blocoCepEstrangeiro = bloco.querySelector('.bloco_endereco_cep_estrangeiro');
    const blocoCidadeBrasil = bloco.querySelector('.bloco_endereco_cidade_brasil');
    const blocoCidadeEstrangeiro = bloco.querySelector('.bloco_endereco_cidade_estrangeiro');
    const blocoEstadoBrasil = bloco.querySelector('.bloco_endereco_estado_brasil');
    const blocoEstadoEstrangeiro = bloco.querySelector('.bloco_endereco_estado_estrangeiro');
    const botaoBuscarEndereco = bloco.querySelector('.botao_buscar_endereco');

    // Buscar LatLong
    const buscarLatLongTitulo = bloco.querySelector('.botao_buscar_latlong_titulo');
    const buscarLatLongEndereco = bloco.querySelector('.botao_buscar_latlong_endereco');
    const buscarLatLongCentro = bloco.querySelector('.botao_buscar_latlong_centro');

    // Mapa
    const blocoMapa = bloco.querySelector('.mapa');
    const latitudeInicial = inputLatitude.valor();
    const longitudeInicial = inputLongitude.valor();
    const MAPA = {
        mapa: null,
        marker: null,
        latitude: latitudeInicial != '' ? parseFloat(latitudeInicial) : parseFloat('-15.7861882'),
        longitude: longitudeInicial != '' ? parseFloat(longitudeInicial) : parseFloat('-47.9239443'),
    };

    // Salvar/Listar
    const blocoEnderecoErro = bloco.querySelector('.bloco_visualizar_erro');
    const blocoEnderecoZero = bloco.querySelector('.bloco_visualizar_zero');
    const blocoEnderecoLoading = bloco.querySelector('.bloco_visualizar_loading');
    const blocoEnderecoLista = bloco.querySelector('.bloco_endereco_lista');
    const blocoEnderecoPadrao = bloco.querySelector('.bloco_visualizar_linha_padrao');
    const blocoCarregarMais = bloco.querySelector('.bloco_visualizar_carregar_mais');
    const botaoCarregarMais = bloco.querySelector('.botao_visualizar_carregar_mais');
    const botaoSalvar = bloco.querySelector('.botao_salvar_endereco');

    const zerarFormulario = () => {
        blocoCepBrasil.classList.remove('display_none');
        blocoEstadoBrasil.classList.remove('display_none');
        blocoCidadeBrasil.classList.remove('display_none');
        botaoBuscarEndereco.classList.remove('display_none');
        blocoCepEstrangeiro.classList.add('display_none');
        blocoCidadeEstrangeiro.classList.add('display_none');
        blocoEstadoEstrangeiro.classList.add('display_none');
        inputZerar.valor('');
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
        blocoEndereco.aparecer();
        setTimeout(() => {
            blocoEndereco.classe('ativo', true);
        }, 40);
        setTimeout(() => {
            inputTitulo.focus();
        }, 300);
    });
    botaoEnderecoFechar.addEventListener('click', () => {
        blocoEndereco.classe('ativo', false);
        setTimeout(() => {
            blocoEndereco.sumir();
        }, 300);
    });

    /*
    |--------------------------------------------------------------------------
    | PAIS/CEP/ESTADO/CIDADE
    |--------------------------------------------------------------------------
    */
    inputPais.evento('formChange', () => {
        setarDadosPorPais();
    });
    const setarDadosPorPais = () => {
        const pais = inputPais.valor();
        if (pais == 'BR') {
            blocoCepBrasil.aparecer();
            blocoCepEstrangeiro.sumir();
            blocoEstadoBrasil.aparecer();
            blocoEstadoEstrangeiro.sumir();
            blocoCidadeBrasil.aparecer();
            blocoCidadeEstrangeiro.sumir();
            botaoBuscarEndereco.aparecer();
            return;
        }
        blocoCepBrasil.sumir();
        blocoCepEstrangeiro.aparecer();
        blocoEstadoBrasil.sumir();
        blocoEstadoEstrangeiro.aparecer();
        blocoCidadeBrasil.sumir();
        blocoCidadeEstrangeiro.aparecer();
        botaoBuscarEndereco.sumir();
    };
    buscarEnderecoPeloCep(
        inputCepBrasil,
        inputLogradouro,
        inputNumero,
        inputBairro,
        inputCidadeBrasil,
        inputEstadoBrasil,
        undefined,
        botaoBuscarEndereco
    );
    inputEstadoBrasil.evento('formChange', () => {
        buscarCidadePeloEstado(inputCidadeBrasil, inputEstadoBrasil.valor(), '', 'Escolha uma cidade');
    });
    const estadoInicial = inputEstadoBrasil.valor();
    const paisInicial = inputPais.valor();
    if (paisInicial == 'BR' && estadoInicial != '') {
        buscarCidadePeloEstado(inputCidadeBrasil, estadoInicial, inputCidadeBrasil.valor(), 'Escolha uma cidade');
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR LAT/LONG
    |--------------------------------------------------------------------------
    */
    inputLatLong.evento('change', () => {
        const latitude = parseFloat(inputLatitude.valor());
        const longitude = parseFloat(inputLongitude.valor());
        if (latitude == '' && longitude == '') {
            return;
        }
        adicionarMarker(latitude, longitude);
        novaPosicaoMapa();
    });
    buscarLatLongTitulo.evento('click', async () => {
        const titulo = inputTitulo.valor();
        const pais = inputPais.valor();
        if (titulo == '' || pais == '') {
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
        if (pais == '' || cidade == '' || estado == '') {
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
    const listarEndereco = async (pagina, pais, estado, titulo) => {
        paginaAtual = pagina;
        blocoEnderecoLoading.aparecer();
        blocoEnderecoZero.sumir();
        blocoEnderecoErro.sumir();
        const resposta = await ajaxPost(
            LINK + '/sistema-endereco/buscar-lista',
            {
                pagina,
                tipo,
                local,
                vinculo: parceiro,
                pais: pais == undefined ? '' : pais,
                estado: estado == undefined ? '' : estado,
                titulo: titulo == undefined ? '' : titulo,
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
        for (endereco of resposta.dado.lista) {
            adicionarNovoEndereco(endereco);
        }
    };
    listarEndereco(1);

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    botaoSalvar.evento('click', async () => {
        const body = montarBodyEndereco();
        if (!(await validarEndereco(body))) {
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(LINK + '/', body, 'Ocorreu um erro ao salvar o endereço.');
        if (false === resposta) {
            return;
        }
        blocoEnderecoZero.sumir();
        adicionarNovoEndereco(resposta.dado);
    });

    const montarBodyEndereco = () => {
        const pais = inputPais.valor();
        const brasil = pais == 'BR';
        return {
            tipo,
            local,
            titulo: inputTitulo.valor(),
            telefone: inputTelefone.valor(),
            pais,
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
        };
    };
    const validarEndereco = async body => {
        return new Promise(resolve => {
            let retorno = true;
            if (body.titulo == '') {
                Alerta.notificacao('Digite um título para continuar.', false);
                retorno = false;
            } else if (body.pais == '') {
                Alerta.notificacao('Escolha um país para continuar.', false);
                retorno = false;
            } else if (body.cep == '') {
                Alerta.notificacao('Digite um CEP para continuar.', false);
                retorno = false;
            } else if (body.logradouro == '') {
                Alerta.notificacao('Digite um logradouro para continuar.', false);
                retorno = false;
            } else if (body.bairro == '') {
                Alerta.notificacao('Digite um bairro para continuar.', false);
                retorno = false;
            } else if (body.estado == '' && body.pais == 'BR') {
                Alerta.notificacao('Escolha um estado para continuar.', false);
                retorno = false;
            } else if (body.cidade == '' && body.pais == 'BR') {
                Alerta.notificacao('Escolha uma cidade para continuar.', false);
                retorno = false;
            } else if (body.cidade == '') {
                Alerta.notificacao('Digite uma cidade para continuar.', false);
                retorno = false;
            } else if (body.latitude == '' || body.longitude == '') {
                Alerta.notificacao('Defina o local do endereço no mapa para continuar.', false);
                retorno = false;
            }
            resolve(retorno);
        });
    };

    const adicionarNovoEndereco = dado => {
        const clone = blocoEnderecoPadrao.clonar();
        $('h1', clone).texto(dado.titulo);
        $('.cidade', clone).texto(dado.cidade);
        if (dado.estado != '') {
            $('.barra', clone).texto('/');
            $('.estado', clone).texto(dado.estado);
        }
        if (dado.bairro != '') {
            $('.traco', clone).texto(' - ');
            $('.bairro', clone).texto(dado.bairro);
        }
        blocoEnderecoLista.inicio(clone);
    };
};
