// @template "painel"

class GoogleMaps {
    /**
     * Inicia um novo mapa
     *
     * @param {element} bloco Elemento que será usado para carregar o mapa
     * @param {float} latitude Latitude
     * @param {float} longitude Longitude
     */
    constructor(bloco, latitude, longitude, zoom, liberaScroll, controleScroll, icone) {
        this._MARKER = [];
        this._MAPA = {};
        this._icone = icone;

        this._init(bloco, latitude, longitude, zoom, liberaScroll, controleScroll);
    }

    _init(bloco, latitude, longitude, zoom, liberaScroll, controleScroll) {
        latitude = parseFloat(latitude);
        longitude = parseFloat(longitude);

        const posicao = { lat: latitude, lng: longitude };
        this._MAPA = new google.maps.Map(bloco, {
            zoom: zoom == undefined ? 17 : zoom,
            center: posicao,
            scrollwheel: liberaScroll == undefined ? false : liberaScroll,
            disableDefaultUI: false,
            zoomControl: controleScroll == undefined ? true : controleScroll,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });
    }
    ponto(latitude, longitude, moverCallback) {
        latitude = parseFloat(latitude);
        longitude = parseFloat(longitude);

        const marker = new google.maps.Marker({
            position: { lat: latitude, lng: longitude },
            map: this._MAPA,
            draggable: moverCallback !== undefined,
            icon: this._icone != undefined ? this._icone : undefined,
        });
        this._MARKER.push(marker);

        if (moverCallback !== undefined) {
            google.maps.event.addListener(marker, 'dragend', function (e) {
                moverCallback(this);
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

const listaMapa = document.querySelectorAll('.bloco_endereco_geral');

listaMapa.forEach(bloco => {
    const mapa = bloco.querySelector('.mapa');
    const inputLatitude = bloco.querySelector('.bloco_endereco_latitude input');
    const inputLongitude = bloco.querySelector('.bloco_endereco_longitude input');
    const botaoComoChegarInativo = bloco.querySelector('.botao.inativo');
    const botaoComoChegarAtivo = bloco.querySelector('.botao.ativo');
    const botaoColocarMarcado = bloco.querySelector('.botao_colocar_marcado');

    const novaPosicaoPonto = e => {
        const latitude = e.getPosition().lat();
        const longitude = e.getPosition().lng();

        inputLatitude.value = latitude;
        inputLongitude.value = longitude;

        if (latitude == '' || longitude == '') {
            return;
        }

        liberarBotaoComoChegar(latitude, longitude);
    };

    let mapaAtivo = false;
    let MAPA = new GoogleMaps(mapa, -15.7861882, -47.9239443);
    // '/images/painel/icone_mapa.png'
    // MAPA.ponto(-15.7861882, -47.9239443, novaPosicaoPonto);

    inputLatitude.addEventListener('change', () => {
        if (inputLatitude.value == '' && mapaAtivo) {
            MAPA = new GoogleMaps(mapa, -15.7861882, -47.9239443);
            mapaAtivo = false;
        } else if (inputLatitude.value != '' && inputLongitude.value != '') {
            adicionarNovaPosicaoNoMapa(inputLatitude.value, inputLongitude.value);
        }
    });
    inputLongitude.addEventListener('change', () => {
        if (inputLongitude.value == '' && mapaAtivo) {
            MAPA = new GoogleMaps(mapa, -15.7861882, -47.9239443);
            mapaAtivo = false;
        } else if (inputLatitude.value != '' && inputLongitude.value != '') {
            adicionarNovaPosicaoNoMapa(inputLatitude.value, inputLongitude.value);
        }
    });
    const adicionarNovaPosicaoNoMapa = (latitude, longitude) => {
        liberarBotaoComoChegar(latitude, longitude);
        MAPA.ponto(latitude, longitude, novaPosicaoPonto);
        mapaAtivo = true;
    };
    const liberarBotaoComoChegar = (latitude, longitude) => {
        const link = 'https://www.google.com.br/maps/dir//' + latitude + ', ' + longitude;
        botaoComoChegarInativo.classList.add('display_none');
        botaoComoChegarAtivo.classList.remove('display_none');
        botaoComoChegarAtivo.setAttribute('href', link);
    };
    botaoColocarMarcado.addEventListener('click', () => {
        MAPA.removerPonto();
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                position => {
                    this.ponto(position.coords.latitude, position.coords.longitude, novaPosicaoPonto);
                },
                erro => {}
            );
        } else {
            console.log('erro 2');
        }
    });
});
