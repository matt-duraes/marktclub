// @template "painel"

class GoogleMaps {
    /**
     * Inicia um novo mapa
     *
     * @param {element} bloco Elemento que será usado para carregar o mapa
     * @param {float} latitude Latitude
     * @param {float} longitude Longitude
     */
    constructor(bloco, latitude, longitude, zoom, liberaScroll, controleScroll) {
        this._init(bloco, latitude, longitude, zoom, liberaScroll, controleScroll);
    }

    _init(bloco, latitude, longitude, zoom, liberaScroll, controleScroll) {
        const posicao = { lat: latitude, lng: longitude };
        this._MAPA = new google.maps.Map(bloco, {
            zoom: zoom == undefined ? 17 : zoom,
            center: posicao,
            scrollwheel: liberaScroll == undefined ? false : liberaScroll,
            disableDefaultUI: false,
            zoomControl: controleScroll == undefined ? true : controleScroll,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        });

        // const marker = new google.maps.Marker({
        //     position: uluru,
        //     map: map,
        //     draggable: true,
        //     icon: '/images/painel/icone_mapa.png',
        // });

        // google.maps.event.addListener(marker, 'dragend', function (e) {
        //     // var ponto_latitude = this.getPosition().lat();
        //     // var ponto_longitude = this.getPosition().lng();
        //     // if(input_latitude != '') $(input_latitude).val(ponto_latitude);
        //     // if(input_longitude != '') $(input_longitude).val(ponto_longitude);

        //     // if(como_chegar != '') $(como_chegar).attr('href', 'https://www.google.com.br/maps/dir//'+ponto_latitude+', '+ponto_longitude);
        //     const pontoLatitude = this.getPosition().lat();
        //     const pontoLongitude = this.getPosition().lng();
        // });
    }
}
const MAPA = new GoogleMaps(document.getElementById('mapa'), -15.7861882, -47.9239443);
