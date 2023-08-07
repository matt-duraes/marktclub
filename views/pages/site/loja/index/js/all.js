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
                if (e.message == 'User denied Geolocation') {
                    Alerta.mensagem(
                        'Localização bloqueada',
                        'Você bloqueou a geolocalização, para poder mostrar as lojas próximas a você, precisamos que desbloquei sua localização e tente novamente.',
                        '!'
                    );
                    return;
                }
                Alerta.mensagem(
                    'Erro na localização',
                    'Ocorreu um erro ao pegar sua localização, verifique suas permissões no navegador e tente novamente.',
                    '!'
                );
            }
        );
    };
});

window.addEventListener('load', () => {
    const blocoMapa = $('#bloco_loja_mapa');
    if (!blocoMapa) {
        return false;
    }
    const blocoParceiro = $('#bloco_loja_index .bloco_parceiro');
    const botaoMapa = $('#botao_visualizar_mapa');
    const botaoLista = $('#botao_visualizar_lista');
    botaoMapa.addEventListener('click', () => {
        botaoMapa.classList.add('display_none');
        botaoLista.classList.remove('display_none');
        blocoMapa.classList.remove('display_none');
        blocoParceiro.classList.add('display_none');
    });
    botaoLista.addEventListener('click', () => {
        botaoMapa.classList.remove('display_none');
        botaoLista.classList.add('display_none');
        blocoMapa.classList.add('display_none');
        blocoParceiro.classList.remove('display_none');
    });
});
window.addEventListener('load', () => {
    const blocoMapa = $('#bloco_google_maps');
    if (!blocoMapa) {
        return;
    }

    const botaoAtualizar = $('#botao_atualizar_mapa');
    const blocoPrevia = $('#bloco_parceiro_previa');
    const blocoPreviaFechar = $('#bloco_parceiro_previa .fechar');
    const blocoPreviaFigure = $('#bloco_parceiro_previa figure');
    const blocoPreviaTitulo = $('#bloco_parceiro_previa h1');
    const blocoPreviaDesconto = $('#bloco_parceiro_previa .desconto');
    const blocoPreviaLink = $('#bloco_parceiro_previa a');

    const MAPA = {
        mapa: null,
        latitude: parseFloat($('#input_loja_latitude').value || 0),
        longitude: parseFloat($('#input_loja_longetude').value || 0),
    };

    async function iniciarMapa() {
        const { Map } = await google.maps.importLibrary('maps');
        const option = {
            scrollwheel: false,
            zoom: 14,
            center: { lat: MAPA.latitude, lng: MAPA.longitude },
            disableDefaultUI: true,
            panControl: false,
            zoomControl: true,
            mapId: 'mapa_loja_id',
        };
        MAPA.mapa = new Map(blocoMapa, option);
        MAPA.mapa.addListener('dragend', function () {
            const centroDoMapa = this.getCenter();
            MAPA.latitude = centroDoMapa.lat();
            MAPA.longitude = centroDoMapa.lng();
            botaoAtualizar.classList.remove('display_none');
        });
        carregarPontos();
    }
    iniciarMapa();

    const carregarPontos = async () => {
        const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');

        const dado = [...document.querySelectorAll('#bloco_loja_mapa .lista article.novo')];
        const markers = dado.map(item => {
            const imagem = item.getAttribute('data-imagem');
            const link = item.getAttribute('data-link');
            const titulo = item.getAttribute('data-titulo');
            const desconto = item.getAttribute('data-desconto');

            const icone = document.createElement('img');
            icone.classList.add('icone_mapa');
            icone.src = imagem;

            const marker = new google.maps.marker.AdvancedMarkerElement({
                position: {
                    lat: parseFloat(item.getAttribute('data-latitude')),
                    lng: parseFloat(item.getAttribute('data-longitude')),
                },
                content: icone,
            });

            marker.addListener('click', () => {
                abrirPreviaMapa(titulo, desconto, imagem, link);
            });
            return marker;
        });

        new markerClusterer.MarkerClusterer({ markers, map: MAPA.mapa });
    };

    const abrirPreviaMapa = (titulo, desconto, imagem, link) => {
        blocoPreviaFigure.innerHTML = `<img src="${imagem}">`;
        blocoPreviaTitulo.innerText = titulo;
        blocoPreviaDesconto.innerText = desconto;
        blocoPreviaLink.setAttribute('href', link);
        blocoPrevia.classList.remove('display_none');
        setTimeout(() => {
            blocoPrevia.classList.add('ativo');
        }, 40);
    };
    const fecharPreviaMapa = () => {
        blocoPrevia.classList.remove('ativo');
        setTimeout(() => {
            blocoPrevia.classList.add('display_none');
            blocoPreviaFigure.innerHTML = '';
            blocoPreviaTitulo.innerText = '';
            blocoPreviaDesconto.innerText = '';
            blocoPreviaLink.removeAttribute('href');
        }, 300);
    };
    blocoPreviaFechar.addEventListener('click', () => {
        fecharPreviaMapa();
    });
});
