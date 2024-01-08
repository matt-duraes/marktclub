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
    const conteudoPopupEndereco = $('#conteudo_popup_endereco');
    const PopupEndereco = new Popup('endereco', 'bloco_endereco', true, true);

    const enderecos = await buscarEndereco();

    blocoEnderecoTexto.innerText = enderecos.principal.endereco;
    botaoLink.setAttribute('href', enderecos.principal.link);
    adicionarEndereco(enderecos.principal.latitude, enderecos.principal.longitude);

    if (enderecos.quantidade <= 1) {
        botaoBuscar.classList.add('display_none');
        return;
    }

    botaoBuscar.addEventListener('click', () => {
        PopupEndereco.abrir();
    });

    exibirEnderecosPorCategoria(enderecos.endereco);
    adicionarEventoBotaoEndereco();

    function exibirEnderecosPorCategoria(enderecos) {
        let htmlString = '';

        for (const pais in enderecos) {
            for (const estado in enderecos[pais]) {
                htmlString += `<div class="bloco_categoria_estado">
                                    <div class="bloco_categoria_titulo">
                                        <p>${estado}</p>
                                    </div>
                                    `;

                for (const cidade in enderecos[pais][estado]) {
                    htmlString += `<div class="bloco_categoria_cidade">
                                        <div class="bloco_categoria_titulo">
                                            <p>${cidade}</p>
                                        </div>`;

                    const listaEnderecos = enderecos[pais][estado][cidade];
                    const enderecoItems = listaEnderecos.map(e => `
                        <div class="bloco_endereco_item">
                            <div class="bloco_detalhe_endereco">
                                <p>${e.endereco}</p>
                                <p class="display_none latitude">${e.latitude}</p>
                                <p class="display_none longitude">${e.longitude}</p>
                                <p class="display_none telefone">${e.telefone}</p>
                                <p class="display_none link">${e.link}</p>
                            </div>
                        </div>`
                    );

                    htmlString += enderecoItems.join('');
                    htmlString += '</div>';
                }

                htmlString += '</div>';
            }
        }

        conteudoPopupEndereco.innerHTML = htmlString;
    }

    function adicionarEventoBotaoEndereco() {
        const listaBotao = $$('.bloco_endereco_item');
        listaBotao.forEach(botao => {
            botao.addEventListener('click', (e) => {
                const bloco = e.target.parentNode;
                const latitude = bloco.querySelector('.latitude').innerText;
                const longitude = bloco.querySelector('.longitude').innerText;
                const endereco = bloco.querySelector('p').innerText;
                const telefone = bloco.querySelector('.telefone').innerText;
                const link = bloco.querySelector('.link').innerText;


                adicionarEndereco(latitude, longitude);
                blocoEnderecoTexto.innerText = endereco;

                botaoLink.setAttribute('href', link);

                PopupEndereco.fechar();
            });
        });
    }

    async function buscarEndereco() {
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
        return resposta.dado;
    };

    async function adicionarEndereco(latitude, longitude) {
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
});
