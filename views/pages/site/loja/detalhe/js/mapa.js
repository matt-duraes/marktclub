window.addEventListener('load', async () => {
    const blocoMapa = $('#bloco_mapa');
    if (!blocoMapa) {
        return;
    }

    const idLoja = $('#input_loja_id').value;

    /*
    |--------------------------------------------------------------------------
    | ENDERECO PRINCIPAL
    |--------------------------------------------------------------------------
    */
    const blocoGoogleMap = $('#bloco_endereco_google_map');
    const blocoEnderecoCompleto = $('#bloco_endereco_completo');
    const botaoEnderecoLink = $('#botao_endereco_link');
    const botaoEnderecoCopiar = $('#botao_endereco_copiar');

    const buscarEnderecoPrincipal = async () => {
        const MapaEsqueleto = new Esqueleto(blocoMapa, '.esqueleto');
        MapaEsqueleto.show();
        const resposta = await ajaxPost(
            LINK + '/endereco/principal',
            {
                id: idLoja,
                local: 'loja',
                latitude: localStorage.getItem('USUARIO_LATITUDE') || '',
                longitude: localStorage.getItem('USUARIO_LONGITUDE') || '',
            },
            ''
        );
        MapaEsqueleto.hide();
        if (false === resposta) {
            blocoMapa.sumir();
            return;
        }
        adicionarEndereco(resposta.dado);
    };
    buscarEnderecoPrincipal();
    botaoEnderecoCopiar.evento('click', () => {
        blocoEnderecoCompleto.copiar('Endereço copiado com sucesso!');
    });

    const adicionarEndereco = async dado => {
        const latitude = parseFloat(dado.latitude);
        const longitude = parseFloat(dado.longitude);
        const posicao = { lat: latitude, lng: longitude };

        blocoEnderecoCompleto.texto(dado.endereco);
        botaoEnderecoLink.attr('href', `https://www.google.com.br/maps/dir//${latitude},%20${longitude}`);

        const { Map } = await google.maps.importLibrary('maps');
        const option = {
            scrollwheel: false,
            zoom: 14,
            center: posicao,
            disableDefaultUI: true,
            panControl: false,
            zoomControl: false,
            clickableIcons: false,
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

    /*
    |--------------------------------------------------------------------------
    | POPUP ENDERECO
    |--------------------------------------------------------------------------
    */
    const botaoAbrirPopup = $('#botao_endereco_abrir_popup');
    const inputPais = $('#input_endereco_pais');
    const inputEstado = $('#input_endereco_estado');
    const inputCidade = $('#input_endereco_cidade');
    const blocoPais = $('#bloco_pais');
    const blocoEstado = $('#bloco_estado');
    const blocoCidade = $('#bloco_cidade');
    const enderecoPadrao = $('#bloco_endereco_padrao');
    const blocoLista = $('#bloco_endereco_lista');

    const PopupAbrir = new Popup('endereco-parceiro', 'bloco_endereco', true, false);
    botaoAbrirPopup.evento('click', async () => {
        abrirPopupEndereco();
    });
    const abrirPopupEndereco = async () => {
        PopupAbrir.abrir();
        buscarEstruturaEndereco('geral');
    };

    inputPais.evento('formChange', () => {
        buscarEstruturaEndereco('pais');
    });
    inputEstado.evento('formChange', () => {
        buscarEstruturaEndereco('estado');
    });
    inputCidade.evento('formChange', () => {
        buscarEndereco();
    });

    const buscarEstruturaEndereco = async tipo => {
        blocoLista.html('');
        if (tipo == 'geral') {
            formSelectLoading(inputPais);
            formSelectLoading(inputEstado);
            formSelectLoading(inputCidade);
        } else if (tipo == 'pais') {
            formSelectLoading(inputEstado);
            formSelectLoading(inputCidade);
        } else if (tipo == 'estado') {
            formSelectLoading(inputCidade);
        }

        blocoPais.sumir();
        blocoEstado.sumir();
        blocoCidade.sumir();

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/endereco/estrutura',
            {
                id: idLoja,
                local: 'loja',
                pais: inputPais.valor(),
                estado: inputEstado.valor(),
            },
            'Erro ao tentar listar endereço, recarregue a página e tente novamente.'
        );

        if (false === resposta) {
            Loading.hide();
            return;
        }

        if (resposta.dado.pais.quantidade > 1) {
            blocoPais.aparecer();
        } else if (resposta.dado.estado.quantidade > 1) {
            blocoEstado.aparecer();
        } else if (resposta.dado.cidade.quantidade > 1) {
            blocoCidade.aparecer();
        }

        if (tipo == 'geral') {
            formSelectOption(inputPais, resposta.dado.pais.lista);
        }
        if (tipo == 'geral' || tipo == 'pais') {
            if (resposta.dado.estado.quantidade > 0) {
                formSelectOption(inputEstado, resposta.dado.estado.lista);
            } else {
                formSelectOption(inputEstado, { '': 'Escolha um estado' });
            }
        }
        if (tipo == 'geral' || tipo == 'pais' || tipo == 'estado') {
            if (resposta.dado.cidade.quantidade > 0) {
                formSelectOption(inputCidade, resposta.dado.cidade.lista);
            } else {
                formSelectOption(inputCidade, { '': 'Escolha uma cidade' });
            }
            if (resposta.dado.cidade.quantidade == 1) {
                buscarEndereco(false);
                return;
            }
        }
        Loading.hide();
    };

    const buscarEndereco = async loading => {
        blocoLista.html('');
        blocoCidade.sumir();

        if (loading === undefined) {
            Loading.show();
        }
        const resposta = await ajaxPost(
            LINK + '/endereco/lista',
            {
                id: idLoja,
                local: 'loja',
                pais: inputPais.valor(),
                estado: inputEstado.valor(),
                cidade: inputCidade.valor(),
            },
            ''
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        for (const item of resposta.dado.lista) {
            adicionarNovoEndereco(item);
        }
    };
    const adicionarNovoEndereco = item => {
        const clone = enderecoPadrao.clonar();
        const enderecoCompleto = $('p', clone);
        enderecoCompleto.texto(item.endereco);
        const botaoCopiar = $('i', clone);
        botaoCopiar.evento('click', () => {
            enderecoCompleto.copiar('Endereço copiado com sucesso.');
        });
        $('a', clone).attr('href', `https://www.google.com.br/maps/dir//${item.latitude},%20${item.longitude}`);
        blocoLista.final(clone);
    };
});
