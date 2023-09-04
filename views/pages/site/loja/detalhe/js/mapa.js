window.addEventListener('load', async () => {
    const blocoMapa = $('#bloco_mapa');
    if (!blocoMapa) {
        return;
    }
    const idLoja = $('#input_loja_id').value;

    const botaoBuscar = $('#botao_endereco_buscar');
    const botaoLink = $('#botao_endereco_link');
    const blocoEnderecoTexto = $('#bloco_endereco_texto');
    const blocoGoogleMap = $('#bloco_endereco_google_map');
    const EsqueletoMapa = new Esqueleto(blocoMapa, '.esqueleto');

    const buscarEndereco = async () => {
        const resposta = await ajaxPost(
            LINK + '/endereco',
            {
                id: idLoja,
                tipo: 'loja',
            },
            ''
        );

        if (false == resposta || false === resposta.dado.existe) {
            blocoMapa.classList.add('display_none');
            return;
        }
        blocoMapa.classList.remove('display_none');
        const dado = resposta.dado;

        if (dado.quantidade > 1) {
            setarFormulario();
        } else {
            botaoBuscar.classList.add('display_none');
        }
        blocoEnderecoTexto.innerText = dado.principal.endereco;
        botaoLink.setAttribute('href', dado.principal.link);
        adicionarEndereco(dado.principal.latitude, dado.principal.longitude);
    };
    buscarEndereco(false);
    const setarFormulario = () => {
        botaoBuscar.classList.add('display_none');
    };
    const adicionarEndereco = async (latitude, longitude) => {
        latitude = parseFloat(latitude);
        longitude = parseFloat(longitude);
        const posicao = { lat: latitude, lng: longitude };

        const { Map } = await google.maps.importLibrary('maps');
        const option = {
            scrollwheel: false,
            zoom: 14,
            center: posicao,
            disableDefaultUI: true,
            panControl: false,
            zoomControl: true,
            mapId: 'bloco_endereco_google_map',
        };
        const mapa = new Map(blocoGoogleMap, option);

        const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');
        const markers = [0].map(() => {
            const icone = document.createElement('img');
            icone.src = LINK + '/images/mapa_icone.png';

            const marker = new google.maps.marker.AdvancedMarkerElement({
                position: posicao,
                content: icone,
            });
            return marker;
        });

        new markerClusterer.MarkerClusterer({ markers, map: mapa });
    };
});
