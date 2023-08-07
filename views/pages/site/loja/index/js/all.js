// @template "site"
// @resource "site/loja/favorito"
// @resource "site/loja/busca"
// @resource "site/busca"
// @resource "site/busca"

window.addEventListener('load', () => {
    const botaoLojaProxima = $$('.botao_loja_proxima');
    if (botaoLojaProxima.length == 0) {
        return;
    }
    botaoLojaProxima.forEach(botao => {
        botao.addEventListener('click', () => {
            if ('geolocation' in navigator) {
                pegarLocalizacao();
            } else {
                Alerta.notificacao('Seu navegador não tem permissão para pegar sua localização.', false);
            }
        });
    });
    const pegarLocalizacao = () => {
        Loading.show();
        navigator.geolocation.getCurrentPosition(
            position => {
                window.location.assign(
                    LINK +
                        '/convenios?latitude=' +
                        encodeURI(position.coords.latitude) +
                        '&longitude=' +
                        encodeURI(position.coords.longitude)
                );
            },
            e => {
                Loading.hide();
                if (e.code == 1) {
                    Alerta.mensagem(
                        'Localização bloqueada',
                        'Você bloqueou a geolocalização, para poder mostrar as lojas próximas a você, precisamos que desbloquei sua localização e tente novamente.',
                        '!'
                    );
                }
            }
        );
    };
});

window.addEventListener('load', () => {
    // const blocoMapa = $('#bloco_google_maps');
    // if (!blocoMapa) {
    //     return;
    // }
    // const botaoAtualizar = $('#botao_atualizar_mapa');
    // const MAPA = {
    //     mapa: null,
    //     markerClusterer: null,
    //     markers: [],
    //     latitude: $('#input_loja_latitude').value || '',
    //     longitude: $('#input_loja_longetude').value || '',
    // };
    // async function googleMaps(latitude, longitude) {
    //     //@ts-ignore
    //     const { Map } = await google.maps.importLibrary('maps');
    //     const options = {
    //         scrollwheel: false,
    //         zoom: 15,
    //         center: { lat: parseFloat(latitude), lng: parseFloat(longitude) },
    //         disableDefaultUI: true,
    //         panControl: false,
    //         zoomControl: true,
    //         mapTypeId: google.maps.MapTypeId.ROADMAP,
    //     };
    //     MAPA.mapa = new Map(blocoMapa, options);
    //     MAPA.mapa.addListener('dragend', function () {
    //         const centroDoMapa = this.getCenter();
    //         MAPA.latitude = centroDoMapa.lat();
    //         MAPA.longitude = centroDoMapa.lng();
    //         botaoAtualizar.classList.remove('display_none');
    //     });
    //     adicionarPontoMapa();
    // }
    // const adicionarPontoMapa = () => {
    //     const parceiro = $$('#bloco_loja_mapa .lista article.novo');
    //     if (parceiro.length == 0) {
    //         return;
    //     }
    //     const labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    //     const locations = [
    //         { lat: -31.56391, lng: 147.154312 },
    //         { lat: -33.718234, lng: 150.363181 },
    //     ];
    //     const markers = locations.map((position, i) => {
    //         const label = labels[i % labels.length];
    //         const marker = new google.maps.Marker({
    //             position,
    //             label,
    //         });
    //         // markers can only be keyboard focusable when they have click listeners
    //         // open info window when marker is clicked
    //         marker.addListener('click', () => {
    //             infoWindow.setContent(label);
    //             infoWindow.open(map, marker);
    //         });
    //         return marker;
    //     });
    //     ppe(markers);
    //     markerClusterer.MarkerClusterer({ map: MAPA.mapa, markers });
    // parceiro.forEach((item, i) => {
    //     const posicao = {
    //         lat: parseFloat(item.getAttribute('data-latitude')),
    //         lng: parseFloat(item.getAttribute('data-longitede')),
    //     };
    //     const marker = new google.maps.Marker({
    //         position: posicao,
    //         label,
    //     });
    //     markers.i = marker;
    // });
    // let i, cliqueNoPonto, parceiroPosicao, parceiroIcone, marker;
    // for (i = 0; i < quantidade; ++i) {
    //     parceiroPosicao = new google.maps.LatLng(
    //         parceiro.lista[i].geolocalizacao.latitude,
    //         parceiro.lista[i].geolocalizacao.longitude
    //     );
    //     parceiroIcone = new google.maps.MarkerImage(LINK + '/images/mapa_icone.png', new google.maps.Size(24, 32));
    //     marker = new google.maps.Marker({
    //         position: parceiroPosicao,
    //         icon: parceiroIcone,
    //     });
    //     mouseoverNoPonto = googlemap.mouseoverNoPonto(parceiro.lista[i]);
    //     mouseoutNoPonto = googlemap.mouseoutNoPonto(parceiro.lista[i]);
    //     cliqueNoPonto = googlemap.cliqueNoPonto(parceiro.lista[i]);
    //     google.maps.event.addListener(marker, 'mouseover', mouseoverNoPonto);
    //     google.maps.event.addListener(marker, 'mouseout', mouseoutNoPonto);
    //     google.maps.event.addListener(marker, 'click', cliqueNoPonto);
    //     googlemap.markers.push(marker);
    // }
    // googlemap.markerClusterer = new MarkerClusterer(googlemap.mapa, googlemap.markers, {
    //     imagePath: LINK + '/images/mapa_mais_',
    // });
    // };
    // googleMaps(MAPA.latitude, MAPA.longitude);
});

async function initMap() {
    const { Map } = await google.maps.importLibrary('maps');
    const map = new Map(document.getElementById('bloco_google_maps'), {
        zoom: 3,
        center: { lat: -28.024, lng: 140.887 },
    });
    const infoWindow = new google.maps.InfoWindow({
        content: '',
        disableAutoPan: true,
    });
    // Create an array of alphabetical characters used to label the markers.
    const labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    // Add some markers to the map.
    const markers = locations.map((position, i) => {
        const label = labels[i % labels.length];
        const marker = new google.maps.Marker({
            position,
            label,
        });

        // markers can only be keyboard focusable when they have click listeners
        // open info window when marker is clicked
        marker.addListener('click', () => {
            infoWindow.setContent(label);
            infoWindow.open(map, marker);
        });
        return marker;
    });

    // Add a marker clusterer to manage the markers.
    new markerClusterer.MarkerClusterer({ markers, map });
}

const locations = [
    { lat: -31.56391, lng: 147.154312 },
    { lat: -33.718234, lng: 150.363181 },
    { lat: -33.727111, lng: 150.371124 },
    { lat: -33.848588, lng: 151.209834 },
    { lat: -33.851702, lng: 151.216968 },
    { lat: -34.671264, lng: 150.863657 },
    { lat: -35.304724, lng: 148.662905 },
    { lat: -36.817685, lng: 175.699196 },
    { lat: -36.828611, lng: 175.790222 },
    { lat: -37.75, lng: 145.116667 },
    { lat: -37.759859, lng: 145.128708 },
    { lat: -37.765015, lng: 145.133858 },
    { lat: -37.770104, lng: 145.143299 },
    { lat: -37.7737, lng: 145.145187 },
    { lat: -37.774785, lng: 145.137978 },
    { lat: -37.819616, lng: 144.968119 },
    { lat: -38.330766, lng: 144.695692 },
    { lat: -39.927193, lng: 175.053218 },
    { lat: -41.330162, lng: 174.865694 },
    { lat: -42.734358, lng: 147.439506 },
    { lat: -42.734358, lng: 147.501315 },
    { lat: -42.735258, lng: 147.438 },
    { lat: -43.999792, lng: 170.463352 },
];

initMap();
