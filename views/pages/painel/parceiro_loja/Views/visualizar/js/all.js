// @template "painel"
// @painel "app_geral_visualizar"

class GoogleMaps {
    /**
     * Inicia um novo mapa
     *
     * @param {element} bloco Elemento que será usado para carregar o mapa
     * @param {float} latitude Latitude
     * @param {float} longitude Longitude
     * @param {int} zoom Qual o zoom inicial do mapa
     * @param {bool} liberaScroll Se vai liberar o scroll do mapa
     * @param {bool} controleScroll Se vai aparecer os botões de controle do scroll
     * @param {bool} UIPadrao Se vai aparecer os a UI padrão do mapa
     */
    constructor(bloco, latitude, longitude, zoom, liberaScroll, controleScroll, UIPadrao) {
        this._MARKER = [];
        this._MAPA = {};

        this._init(bloco, latitude, longitude, zoom, liberaScroll, controleScroll, UIPadrao);
    }

    _init(bloco, latitude, longitude, zoom, liberaScroll, controleScroll, UIPadrao) {
        latitude = parseFloat(latitude);
        longitude = parseFloat(longitude);

        const posicao = { lat: latitude, lng: longitude };
        this._MAPA = new google.maps.Map(bloco, {
            zoom: zoom == undefined ? 17 : zoom,
            center: posicao,
            scrollwheel: liberaScroll == undefined ? false : liberaScroll,
            disableDefaultUI: UIPadrao == undefined ? false : UIPadrao,
            zoomControl: controleScroll == undefined ? true : controleScroll,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });
    }

    /**
     * Ícone que será usado nos pontos
     *
     * @param {string} icone Ícone que será usado
     */
    icone(icone) {
        this._icone = icone;
    }

    /**
     *
     * @param {float} latitude Latitude do ponto
     * @param {float} longitude Longitude do ponto
     * @param {object} moverCallback Callback para quando mover o ponto
     * @param {object} clickCallback Callback para quando clicar no ponto
     * @param {string} id Id para ser usado nos Callback
     */
    ponto(latitude, longitude, moverCallback, clickCallback, id) {
        latitude = parseFloat(latitude);
        longitude = parseFloat(longitude);

        const marker = new google.maps.Marker({
            position: { lat: latitude, lng: longitude },
            map: this._MAPA,
            draggable: moverCallback !== undefined,
            icon: this._icone != undefined ? this._icone : undefined,
        });
        const numeroAtual = this._MARKER.length == 0 ? 0 : this._MARKER.length + 1;
        id = id == undefined ? numeroAtual : id;
        this._MARKER[id] = marker;

        if (moverCallback !== undefined) {
            google.maps.event.addListener(marker, 'dragend', function (e) {
                moverCallback(this, id);
            });
        } else if (clickCallback !== undefined) {
            google.maps.event.addListener(marker, 'click', function (e) {
                clickCallback(this, id);
            });
        }
    }

    removerPonto(indice) {
        if (this._MARKER.length == 0) {
            return;
        } else if (indice != undefined) {
            this._MARKER[indice].setMap(null);
            this._MARKER.splice(indice, 1);
            return;
        }
        this._MARKER.forEach(icone => {
            icone.setMap(null);
        });
        this._MARKER = [];
    }
}
window.addEventListener('load', () => {
    const parceiro = document.querySelector('#input_visualizar_id').value;
    const listaMapa = $$('.bloco_endereco_geral');
    listaMapa.forEach(bloco => {
        carregarBlocoEndereco(bloco, parceiro);
    });
});
const carregarBlocoEndereco = (bloco, parceiro) => {
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
    const inputGeolocalizacaoTitulo = bloco.querySelector('.input_geolocalizacao_titulo');
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

    // Mapa
    const blocoMapa = bloco.querySelector('.mapa');
    const MAPA = {
        mapa: null,
        latitude: parseFloat('-15.7861882'),
        longitude: parseFloat('-47.9239443'),
    };

    const zerarFormulario = async () => {
        blocoCepBrasil.classList.remove('display_none');
        blocoEstadoBrasil.classList.remove('display_none');
        blocoCidadeBrasil.classList.remove('display_none');
        botaoBuscarEndereco.classList.remove('display_none');
        blocoCepEstrangeiro.classList.add('display_none');
        blocoCidadeEstrangeiro.classList.add('display_none');
        blocoEstadoEstrangeiro.classList.add('display_none');
        inputZerar.valor('');

        inputPais.valor('BR');

        const { Map } = await google.maps.importLibrary('maps');
        const option = {
            scrollwheel: false,
            zoom: 13,
            center: { lat: MAPA.latitude, lng: MAPA.longitude },
            disableDefaultUI: true,
            panControl: false,
            zoomControl: false,
        };
        MAPA.mapa = new Map(blocoMapa, option);
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
        buscarCidadePeloEstado(inputCidadeBrasil, inputEstadoBrasil.valor());
    });
    const estadoInicial = inputEstadoBrasil.valor();
    const paisInicial = inputPais.valor();
    if (paisInicial == 'BR' && estadoInicial != '') {
        buscarCidadePeloEstado(inputCidadeBrasil, estadoInicial, inputCidadeBrasil.valor());
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR PELA LAT/LONG
    |--------------------------------------------------------------------------
    */
    inputLatLong.evento('change', () => {
        const latitude = inputLatitude.valor();
        const longitude = inputLongitude.valor();
        if (latitude == '' && longitude == '') {
            return;
        }
        adicionarPontoMapa(latitude, longitude);
    });

    const adicionarPontoMapa = (latitude, longitude) => {
        //
    };
};
const carregarBlocoEnderecoOld = bloco => {
    // const blocoMapa = bloco.querySelector('.mapa');
    // const idTabela = bloco.querySelector('input[name=tabela]').value;
    // const idLocal = bloco.querySelector('input[name=local]').value;
    // const blocoCepBrasil = bloco.querySelector('.bloco_endereco_cep_brasil');
    // const blocoCepEstrangeiro = bloco.querySelector('.bloco_endereco_cep_estrangeiro');
    // const blocoCidadeBrasil = bloco.querySelector('.bloco_endereco_cidade_brasil');
    // const blocoCidadeEstrangeiro = bloco.querySelector('.bloco_endereco_cidade_estrangeiro');
    // const blocoEstadoBrasil = bloco.querySelector('.bloco_endereco_estado_brasil');
    // const blocoEstadoEstrangeiro = bloco.querySelector('.bloco_endereco_estado_estrangeiro');
    // const inputTitulo = bloco.querySelector('.bloco_endereco_titulo input');
    // const inputTelefone = bloco.querySelector('.bloco_endereco_telefone input');
    // const inputPais = bloco.querySelector('.bloco_endereco_pais input');
    // const inputCepBrasil = bloco.querySelector('.bloco_endereco_cep_brasil input');
    // const inputCepEstrangeiro = bloco.querySelector('.bloco_endereco_cep_estrangeiro input');
    // const inputLogradouro = bloco.querySelector('.bloco_endereco_logradouro input');
    // const inputNumero = bloco.querySelector('.bloco_endereco_numero input');
    // const inputComplemento = bloco.querySelector('.bloco_endereco_complemento input');
    // const inputReferencia = bloco.querySelector('.bloco_endereco_referencia input');
    // const inputBairro = bloco.querySelector('.bloco_endereco_bairro input');
    // const inputEstadoBrasil = bloco.querySelector('.bloco_endereco_estado_brasil input.input_select_value');
    // const inputEstadoEstrangeiro = bloco.querySelector('.bloco_endereco_estado_estrangeiro input');
    // const inputCidadeBrasil = bloco.querySelector('.bloco_endereco_cidade_brasil input.input_select_value');
    // const inputCidadeEstrangeiro = bloco.querySelector('.bloco_endereco_cidade_estrangeiro input');
    // const inputGeolocalizacaoTitulo = bloco.querySelector('.input_geolocalizacao_titulo');
    // const inputLatitude = bloco.querySelector('.bloco_endereco_latitude input');
    // const inputLongitude = bloco.querySelector('.bloco_endereco_longitude input');
    // const botaoComoChegarInativo = bloco.querySelector('.botao.inativo');
    // const botaoComoChegarAtivo = bloco.querySelector('.botao.ativo');
    // const botaoColocarMarcado = bloco.querySelector('.botao_colocar_marcado');
    // const botaoBuscarGeolocalizadorEndereco = bloco.querySelector('.botao_buscar_geolocalizacao_endereco');
    // const botaoBuscarEndereco = bloco.querySelector('.botao_buscar_endereco');
    // const botaoSalvarEndereco = bloco.querySelector('.botao_salvar_endereco');
    // const blocoListaPrevia = bloco.querySelector('.bloco_lista_endereco_previa');
    // const botaoFecharAdd = bloco.querySelector('.bloco_endereco_add .fechar');
    // const botaoAbrirAdd = bloco.querySelector('.botao_adicionar_endereco');
    // let mapaAtivo = false;
    // let MAPA;
    // const zerarFormulario = () => {
    //     blocoCepBrasil.classList.remove('display_none');
    //     blocoEstadoBrasil.classList.remove('display_none');
    //     blocoCidadeBrasil.classList.remove('display_none');
    //     botaoBuscarEndereco.classList.remove('display_none');
    //     blocoCepEstrangeiro.classList.add('display_none');
    //     blocoCidadeEstrangeiro.classList.add('display_none');
    //     blocoEstadoEstrangeiro.classList.add('display_none');
    //     formValue(inputTitulo, '');
    //     formValue(inputTelefone, '');
    //     formValue(inputPais, 'BR');
    //     formValue(inputCepBrasil, '');
    //     formValue(inputCepEstrangeiro, '');
    //     formValue(inputLogradouro, '');
    //     formValue(inputNumero, '');
    //     formValue(inputComplemento, '');
    //     formValue(inputReferencia, '');
    //     formValue(inputBairro, '');
    //     formValue(inputEstadoBrasil, '');
    //     formValue(inputEstadoEstrangeiro, '');
    //     formValue(inputCidadeEstrangeiro, '');
    //     formValue(inputGeolocalizacaoTitulo, '');
    //     formValue(inputLatitude, '');
    //     formValue(inputLongitude, '');
    //     formSelectOption(inputCidadeBrasil, { '': 'Escolha uma cidade' });
    //     MAPA = new GoogleMaps(blocoMapa, -15.7861882, -47.9239443);
    //     MAPA.icone(LINK + '/images/painel/icone_mapa.png');
    // };
    // /*
    // |--------------------------------------------------------------------------
    // | CONTROLANDO BLOCO DE ADD
    // |--------------------------------------------------------------------------
    // */
    // botaoAbrirAdd.addEventListener('click', () => {
    //     zerarFormulario();
    //     blocoAdd.classList.remove('display_none');
    //     setTimeout(() => {
    //         blocoAdd.classList.add('ativo');
    //     }, 40);
    // });
    // botaoFecharAdd.addEventListener('click', () => {
    //     blocoAdd.classList.remove('ativo');
    //     setTimeout(() => {
    //         blocoAdd.classList.add('display_none');
    //     }, 300);
    // });
    // botaoBuscarEndereco.addEventListener('click', async () => {
    //     const cep = inputCepBrasil.value;
    //     if (!/^[0-9]{5}\-[0-9]{3}$/.test(cep) || inputPais.value != 'BR') {
    //         return;
    //     }
    //     Loading.show();
    //     const body = new FormData();
    //     body.append('cep', cep);
    //     const resposta = await fetch(LINK + '/sistema-endereco/buscar-endereco-pelo-cep', {
    //         method: 'POST',
    //         body,
    //     });
    //     const json = await respostaJson(
    //         resposta,
    //         'Ocorre um erro ao buscar o endereço pelo CEP, por favor, tente novamente.'
    //     );
    //     if (false === json) {
    //         Loading.hide();
    //         return;
    //     }
    //     if (json.dado.logradouro == '') {
    //         inputLogradouro.focus();
    //     } else {
    //         inputNumero.focus();
    //     }
    //     formValue(inputLogradouro, json.dado.logradouro);
    //     formValue(inputBairro, json.dado.bairro);
    //     formValue(inputEstadoBrasil, json.dado.estado);
    //     buscarListaCidade(json.dado.estado, json.dado.cidade);
    // });
    // /*
    // |--------------------------------------------------------------------------
    // | COMANDOS PARA O GOOGLE MAPS
    // |--------------------------------------------------------------------------
    // */
    // const adicionarGeoLocalizacaoNoInput = (e, id) => {
    //     const latitude = e.getPosition().lat();
    //     const longitude = e.getPosition().lng();
    //     inputLatitude.value = latitude;
    //     inputLongitude.value = longitude;
    //     if (latitude == '' || longitude == '') {
    //         return;
    //     }
    //     liberarBotaoComoChegar(latitude, longitude);
    // };
    // // Salvar nova latitude e longitude ao mover ponto
    // inputLatitude.addEventListener('change', () => {
    //     if (inputLatitude.value == '' && mapaAtivo) {
    //         MAPA = new GoogleMaps(blocoMapa, -15.7861882, -47.9239443);
    //         mapaAtivo = false;
    //     } else if (inputLatitude.value != '' && inputLongitude.value != '') {
    //         adicionarNovaPosicaoNoMapa(inputLatitude.value, inputLongitude.value);
    //     }
    // });
    // inputLongitude.addEventListener('change', () => {
    //     if (inputLongitude.value == '' && mapaAtivo) {
    //         MAPA = new GoogleMaps(blocoMapa, -15.7861882, -47.9239443);
    //         mapaAtivo = false;
    //     } else if (inputLatitude.value != '' && inputLongitude.value != '') {
    //         adicionarNovaPosicaoNoMapa(inputLatitude.value, inputLongitude.value);
    //     }
    // });
    // const adicionarNovaPosicaoNoMapa = (latitude, longitude) => {
    //     liberarBotaoComoChegar(latitude, longitude);
    //     MAPA.ponto(latitude, longitude, adicionarGeoLocalizacaoNoInput);
    //     mapaAtivo = true;
    // };
    // const liberarBotaoComoChegar = (latitude, longitude) => {
    //     const link = 'https://www.google.com.br/maps/dir//' + latitude + ', ' + longitude;
    //     botaoComoChegarInativo.classList.add('display_none');
    //     botaoComoChegarAtivo.classList.remove('display_none');
    //     botaoComoChegarAtivo.setAttribute('href', link);
    // };
    // // Coloca o ponto atual no mapa
    // botaoColocarMarcado.addEventListener('click', () => {
    //     MAPA.removerPonto();
    //     if (navigator.geolocation) {
    //         navigator.geolocation.getCurrentPosition(
    //             position => {
    //                 this.ponto(position.coords.latitude, position.coords.longitude, adicionarGeoLocalizacaoNoInput);
    //             },
    //             erro => {}
    //         );
    //     } else {
    //         //
    //     }
    // });
    // // Busca a gelocalização pelo endereco ou titulo
    // inputGeolocalizacaoTitulo.addEventListener('keydown', e => {
    //     if (e.key == 'Enter') {
    //         e.preventDefault();
    //         const body = new FormData();
    //         body.append('pais', inputPais.value);
    //         body.append('titulo', inputGeolocalizacaoTitulo.value);
    //         buscarGeolocalizacao(body);
    //     }
    // });
    // botaoBuscarGeolocalizadorEndereco.addEventListener('click', async () => {
    //     const body = new FormData();
    //     const pais = inputPais.value;
    //     body.append('pais', pais);
    //     body.append('cep', pais == 'BR' ? inputCepBrasil.value : inputCepEstrangeiro.value);
    //     body.append('logradouro', inputLogradouro.value);
    //     body.append('numero', inputNumero.value);
    //     body.append('bairro', inputBairro.value);
    //     body.append('cidade', pais == 'BR' ? inputCidadeBrasil.value : inputCidadeEstrangeiro.value);
    //     body.append('estado', pais == 'BR' ? inputEstadoBrasil.value : inputEstadoEstrangeiro.value);
    //     buscarGeolocalizacao(body);
    // });
    // const buscarGeolocalizacao = async body => {
    //     const resposta = await fetch(LINK + '/sistema-endereco/buscar-geolocalizacao', {
    //         method: 'POST',
    //         body,
    //     });
    //     const json = await respostaJson(
    //         resposta,
    //         'Ocorreu um erro ao buscar as cordenadas, por favor, tente novamente.'
    //     );
    //     if (false === json) {
    //         return;
    //     }
    //     const latitude = json.dado.latitude;
    //     const longitude = json.dado.longitude;
    //     inputLatitude.value = latitude;
    //     inputLongitude.value = longitude;
    //     MAPA = new GoogleMaps(blocoMapa, latitude, longitude);
    //     MAPA.ponto(latitude, longitude, adicionarGeoLocalizacaoNoInput);
    //     liberarBotaoComoChegar(latitude, longitude);
    // };
};
