// @template "painel"

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

const idVinculo = document.querySelector('#input_visualizar_id').value;

formSelectChange = funcao => {
    const bloco = document.querySelector('.bloco_endereco_add.ativo');
    if (funcao == 'mudarPais') {
        setarEnderecoPais(bloco);
    } else if (funcao == 'buscarCidade') {
        Loading.show();

        const estado = bloco.querySelector('.bloco_endereco_estado_brasil input.input_select_value').value;
        buscarListaCidade(estado);
    }
};

const setarEnderecoPais = bloco => {
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

    formValue(inputCepBrasil, '');
    formValue(inputCepEstrangeiro, '');
    formValue(inputLogradouro, '');
    formValue(inputNumero, '');
    formValue(inputComplemento, '');
    formValue(inputReferencia, '');
    formValue(inputBairro, '');
    formValue(inputEstadoBrasil, '');
    formValue(inputEstadoEstrangeiro, '');
    formValue(inputCidadeBrasil, '');
    formValue(inputCidadeEstrangeiro, '');
    formValue(inputGeolocalizacaoTitulo, '');

    const blocoCepBrasil = bloco.querySelector('.bloco_endereco_cep_brasil');
    const blocoCepEstrangeiro = bloco.querySelector('.bloco_endereco_cep_estrangeiro');
    const blocoEstadoBrasil = bloco.querySelector('.bloco_endereco_estado_brasil');
    const blocoEstadoEstrangeiro = bloco.querySelector('.bloco_endereco_estado_estrangeiro');
    const blocoCidadeBrasil = bloco.querySelector('.bloco_endereco_cidade_brasil');
    const blocoCidadeEstrangeiro = bloco.querySelector('.bloco_endereco_cidade_estrangeiro');
    const botaoBuscarEndereco = bloco.querySelector('.botao_buscar_endereco');

    if (inputPais.value == 'BR') {
        blocoCepBrasil.classList.remove('display_none');
        blocoEstadoBrasil.classList.remove('display_none');
        blocoCidadeBrasil.classList.remove('display_none');
        botaoBuscarEndereco.classList.remove('display_none');
        blocoCepEstrangeiro.classList.add('display_none');
        blocoCidadeEstrangeiro.classList.add('display_none');
        blocoEstadoEstrangeiro.classList.add('display_none');
        return;
    }
    blocoCepBrasil.classList.add('display_none');
    blocoEstadoBrasil.classList.add('display_none');
    blocoCidadeBrasil.classList.add('display_none');
    botaoBuscarEndereco.classList.add('display_none');
    blocoCepEstrangeiro.classList.remove('display_none');
    blocoCidadeEstrangeiro.classList.remove('display_none');
    blocoEstadoEstrangeiro.classList.remove('display_none');
};

const buscarListaCidade = async (estado, cidade) => {
    const bloco = document.querySelector('.bloco_endereco_add.ativo');
    const pais = bloco.querySelector('.bloco_endereco_pais input').value;
    const inputCidade = bloco.querySelector('.bloco_endereco_cidade_brasil input.input_select_value');
    if (estado == '' || pais != 'BR') {
        Loading.hide();
        formSelectOption(inputCidade, { '': 'Escolha uma cidade' });
        return;
    }

    const body = new FormData();
    body.append('estado', estado);

    const resposta = await fetch(LINK + '/sistema-endereco/buscar-cidade', {
        method: 'POST',
        body,
    });
    const json = await respostaJson(
        resposta,
        'Ocorre um erro ao buscar a lista de cidades, por favor, tente novamente.'
    );
    Loading.hide();
    if (false === json) {
        return;
    }

    formSelectOption(inputCidade, json.dado, cidade);
};

const listaMapa = document.querySelectorAll('.bloco_endereco_geral');
listaMapa.forEach(bloco => {
    const blocoAdd = bloco.querySelector('.bloco_endereco_add');
    const blocoMapa = bloco.querySelector('.mapa');

    const idTabela = bloco.querySelector('input[name=tabela]').value;
    const idLocal = bloco.querySelector('input[name=local]').value;

    const blocoCepBrasil = bloco.querySelector('.bloco_endereco_cep_brasil');
    const blocoCepEstrangeiro = bloco.querySelector('.bloco_endereco_cep_estrangeiro');
    const blocoCidadeBrasil = bloco.querySelector('.bloco_endereco_cidade_brasil');
    const blocoCidadeEstrangeiro = bloco.querySelector('.bloco_endereco_cidade_estrangeiro');
    const blocoEstadoBrasil = bloco.querySelector('.bloco_endereco_estado_brasil');
    const blocoEstadoEstrangeiro = bloco.querySelector('.bloco_endereco_estado_estrangeiro');

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

    const botaoComoChegarInativo = bloco.querySelector('.botao.inativo');
    const botaoComoChegarAtivo = bloco.querySelector('.botao.ativo');
    const botaoColocarMarcado = bloco.querySelector('.botao_colocar_marcado');
    const botaoBuscarGeolocalizadorEndereco = bloco.querySelector('.botao_buscar_geolocalizacao_endereco');
    const botaoBuscarEndereco = bloco.querySelector('.botao_buscar_endereco');
    const botaoSalvarEndereco = bloco.querySelector('.botao_salvar_endereco');

    const blocoListaPrevia = bloco.querySelector('.bloco_lista_endereco_previa');

    const botaoFecharAdd = bloco.querySelector('.bloco_endereco_add .fechar');
    const botaoAbrirAdd = bloco.querySelector('.botao_adicionar_endereco');

    let mapaAtivo = false;
    let MAPA;

    const zerarFormulario = () => {
        blocoCepBrasil.classList.remove('display_none');
        blocoEstadoBrasil.classList.remove('display_none');
        blocoCidadeBrasil.classList.remove('display_none');
        botaoBuscarEndereco.classList.remove('display_none');
        blocoCepEstrangeiro.classList.add('display_none');
        blocoCidadeEstrangeiro.classList.add('display_none');
        blocoEstadoEstrangeiro.classList.add('display_none');

        formValue(inputTitulo, '');
        formValue(inputTelefone, '');
        formValue(inputPais, 'BR');
        formValue(inputCepBrasil, '');
        formValue(inputCepEstrangeiro, '');
        formValue(inputLogradouro, '');
        formValue(inputNumero, '');
        formValue(inputComplemento, '');
        formValue(inputReferencia, '');
        formValue(inputBairro, '');
        formValue(inputEstadoBrasil, '');
        formValue(inputEstadoEstrangeiro, '');
        formValue(inputCidadeEstrangeiro, '');
        formValue(inputGeolocalizacaoTitulo, '');
        formValue(inputLatitude, '');
        formValue(inputLongitude, '');
        formSelectOption(inputCidadeBrasil, { '': 'Escolha uma cidade' });

        MAPA = new GoogleMaps(blocoMapa, -15.7861882, -47.9239443);
        MAPA.icone(LINK + '/images/painel/icone_mapa.png');
    };

    /*
    |--------------------------------------------------------------------------
    | LISTAR ENDEREÇOS
    |--------------------------------------------------------------------------
    */
    const buscarListaEndereco = pagina => {
        const body = new FormData();
        body.append('tabela', idTabela);
        body.append('local', idLocal);
        body.append('id', idVinculo);
        body.append('pagina', pagina);
        body.append('quantidade', 20);
        body.append('pais', '');
        body.append('estado', '');
        body.append('titulo', '');

        executarBuscaEndereco(body);
    };
    const buscarPreviaEndereco = () => {
        blocoListaPrevia.insertAdjacentHTML('afterbegin', `<div class="zero">Carregando endereços</div>`);
        const body = new FormData();
        body.append('tabela', idTabela);
        body.append('local', idLocal);
        body.append('id', idVinculo);
        body.append('pagina', 1);
        body.append('quantidade', 50);
        executarBuscaEndereco(body, blocoListaPrevia);
    };
    const executarBuscaEndereco = async (body, bloco) => {
        const resposta = await fetch(LINK + '/sistema-endereco/buscar-lista', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(resposta, 'Erro ao listar endereço.');
        if (false === json) {
            return;
        }

        adicionarNovoEndereco(bloco, json.dado);
    };
    buscarPreviaEndereco();

    const adicionarNovoEndereco = (bloco, lista) => {
        lista.forEach(item => {
            let titulo = item.titulo;
            if (item.cidade != '' && item.estado != '') {
                titulo += ` - ${item.cidade}/${item.estado}`;
            } else if (item.cidade != '') {
                titulo += ` - ${item.cidade}`;
            } else if (item.estado != '') {
                titulo += ` - ${item.estado}`;
            }
            bloco.insertAdjacentHTML(
                'beforeend',
                `
                    <div class="item" data-id="${item.id}">
                        <p>${titulo}</p>
                        <div class="botao_visualizar"><svg height="15" xmlns:cc="hqttp://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 29.3" style="enable-background:new 0 0 40 29.3;" xml:space="preserve"><g transform="translate(0,-288.53333)"><path d="M20,288.5c-13.3,0-19.3,12.3-19.6,12.9c-0.6,1.1-0.6,2.5,0,3.6c0.3,0.6,6.3,12.9,19.6,12.9s19.3-12.3,19.6-12.9 c0.6-1.1,0.6-2.5,0-3.6C39.3,300.8,33.3,288.5,20,288.5z M20,291.2c11.6,0,16.8,10.6,17.2,11.4c0.2,0.4,0.2,0.8,0,1.2 c-0.4,0.8-5.6,11.4-17.2,11.4S3.2,304.6,2.8,303.8c-0.2-0.4-0.2-0.8,0-1.2C3.2,301.7,8.4,291.2,20,291.2z"/><path d="M20,293.9c-5.1,0-9.3,4.2-9.3,9.3s4.2,9.3,9.3,9.3s9.3-4.2,9.3-9.3S25.1,293.9,20,293.9z M20,296.5c3.7,0,6.7,3,6.7,6.7 s-3,6.7-6.7,6.7s-6.7-3-6.7-6.7S16.3,296.5,20,296.5z"/></g></svg></div>
                    </div>
                `
            );
        });
    };
    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    botaoSalvarEndereco.addEventListener('click', () => {
        const titulo = inputTitulo.value;
        const telefone = inputTelefone.value;

        const pais = inputPais.value;
        const cep = pais == 'BR' ? inputCepBrasil.value : inputCepEstrangeiro.value;
        const logradouro = inputLogradouro.value;
        const numero = inputNumero.value;
        const complemento = inputComplemento.value;
        const referencia = inputReferencia.value;
        const bairro = inputBairro.value;
        const estado = pais == 'BR' ? inputEstadoBrasil.value : inputEstadoEstrangeiro.value;
        const cidade = pais == 'BR' ? inputCidadeBrasil.value : inputCidadeEstrangeiro.value;
    });

    /*
    |--------------------------------------------------------------------------
    | CONTROLANDO BLOCO DE ADD
    |--------------------------------------------------------------------------
    */
    botaoAbrirAdd.addEventListener('click', () => {
        zerarFormulario();
        blocoAdd.classList.remove('display_none');
        setTimeout(() => {
            blocoAdd.classList.add('ativo');
        }, 40);
    });
    botaoFecharAdd.addEventListener('click', () => {
        blocoAdd.classList.remove('ativo');
        setTimeout(() => {
            blocoAdd.classList.add('display_none');
        }, 300);
    });

    botaoBuscarEndereco.addEventListener('click', async () => {
        const cep = inputCepBrasil.value;
        if (!/^[0-9]{5}\-[0-9]{3}$/.test(cep) || inputPais.value != 'BR') {
            return;
        }

        Loading.show();

        const body = new FormData();
        body.append('cep', cep);
        const resposta = await fetch(LINK + '/sistema-endereco/buscar-endereco-pelo-cep', {
            method: 'POST',
            body,
        });
        const json = await respostaJson(
            resposta,
            'Ocorre um erro ao buscar o endereço pelo CEP, por favor, tente novamente.'
        );

        if (false === json) {
            Loading.hide();
            return;
        }

        if (json.dado.logradouro == '') {
            inputLogradouro.focus();
        } else {
            inputNumero.focus();
        }

        formValue(inputLogradouro, json.dado.logradouro);
        formValue(inputBairro, json.dado.bairro);
        formValue(inputEstadoBrasil, json.dado.estado);

        buscarListaCidade(json.dado.estado, json.dado.cidade);
    });

    /*
    |--------------------------------------------------------------------------
    | COMANDOS PARA O GOOGLE MAPS
    |--------------------------------------------------------------------------
    */
    const adicionarGeoLocalizacaoNoInput = (e, id) => {
        const latitude = e.getPosition().lat();
        const longitude = e.getPosition().lng();

        inputLatitude.value = latitude;
        inputLongitude.value = longitude;

        if (latitude == '' || longitude == '') {
            return;
        }

        liberarBotaoComoChegar(latitude, longitude);
    };

    // Salvar nova latitude e longitude ao mover ponto
    inputLatitude.addEventListener('change', () => {
        if (inputLatitude.value == '' && mapaAtivo) {
            MAPA = new GoogleMaps(blocoMapa, -15.7861882, -47.9239443);
            mapaAtivo = false;
        } else if (inputLatitude.value != '' && inputLongitude.value != '') {
            adicionarNovaPosicaoNoMapa(inputLatitude.value, inputLongitude.value);
        }
    });
    inputLongitude.addEventListener('change', () => {
        if (inputLongitude.value == '' && mapaAtivo) {
            MAPA = new GoogleMaps(blocoMapa, -15.7861882, -47.9239443);
            mapaAtivo = false;
        } else if (inputLatitude.value != '' && inputLongitude.value != '') {
            adicionarNovaPosicaoNoMapa(inputLatitude.value, inputLongitude.value);
        }
    });
    const adicionarNovaPosicaoNoMapa = (latitude, longitude) => {
        liberarBotaoComoChegar(latitude, longitude);
        MAPA.ponto(latitude, longitude, adicionarGeoLocalizacaoNoInput);
        mapaAtivo = true;
    };
    const liberarBotaoComoChegar = (latitude, longitude) => {
        const link = 'https://www.google.com.br/maps/dir//' + latitude + ', ' + longitude;
        botaoComoChegarInativo.classList.add('display_none');
        botaoComoChegarAtivo.classList.remove('display_none');
        botaoComoChegarAtivo.setAttribute('href', link);
    };

    // Coloca o ponto atual no mapa
    botaoColocarMarcado.addEventListener('click', () => {
        MAPA.removerPonto();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    this.ponto(position.coords.latitude, position.coords.longitude, adicionarGeoLocalizacaoNoInput);
                },
                erro => {}
            );
        } else {
            //
        }
    });

    // Busca a gelocalização pelo endereco ou titulo
    inputGeolocalizacaoTitulo.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            const body = new FormData();
            body.append('pais', inputPais.value);
            body.append('titulo', inputGeolocalizacaoTitulo.value);

            buscarGeolocalizacao(body);
        }
    });
    botaoBuscarGeolocalizadorEndereco.addEventListener('click', async () => {
        const body = new FormData();
        const pais = inputPais.value;
        body.append('pais', pais);
        body.append('cep', pais == 'BR' ? inputCepBrasil.value : inputCepEstrangeiro.value);
        body.append('logradouro', inputLogradouro.value);
        body.append('numero', inputNumero.value);
        body.append('bairro', inputBairro.value);
        body.append('cidade', pais == 'BR' ? inputCidadeBrasil.value : inputCidadeEstrangeiro.value);
        body.append('estado', pais == 'BR' ? inputEstadoBrasil.value : inputEstadoEstrangeiro.value);

        buscarGeolocalizacao(body);
    });
    const buscarGeolocalizacao = async body => {
        const resposta = await fetch(LINK + '/sistema-endereco/buscar-geolocalizacao', {
            method: 'POST',
            body,
        });

        const json = await respostaJson(
            resposta,
            'Ocorreu um erro ao buscar as cordenadas, por favor, tente novamente.'
        );
        if (false === json) {
            return;
        }

        const latitude = json.dado.latitude;
        const longitude = json.dado.longitude;
        inputLatitude.value = latitude;
        inputLongitude.value = longitude;

        MAPA = new GoogleMaps(blocoMapa, latitude, longitude);
        MAPA.ponto(latitude, longitude, adicionarGeoLocalizacaoNoInput);
        liberarBotaoComoChegar(latitude, longitude);
    };
});
