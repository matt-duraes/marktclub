// @template "site"
// @resource "site/loja/favorito"
// @resource "site/loja/busca"
// @resource "site/busca"
// @resource "site/busca"
// @system "Esqueleto"

const parceiroLoading = tipo => {
    const blocoLoja = $('#parceiro_padrao_loja');
    blocoLoja.removeAttribute('id');

    const blocoLoading = $('#bloco_parceiro_loading');
    const blocoLista = $('#bloco_parceiro_lista');

    const blocoErro = $('#bloco_parceiro_erro');
    const blocoZero = $('#bloco_parceiro_zero');

    const blocoCarregarMais = $('#bloco_carregar_mais');
    const botaoCarregarMais = $('#botao_carregar_mais');

    const loading = $$('.bloco_parceiro_loading article');
    loading.forEach(item => {
        const EsqueletoItem = new Esqueleto(item, '.esqueleto');
        EsqueletoItem.show();
    });

    const inputLatitude = $('#input_latitude');
    const inputLongitude = $('#input_longitude');
    const inputAcessado = $('#input_acessado');
    const inputFavorito = $('#input_favorito');
    const inputEstado = $('#input_estado');
    const inputCategoria = $('#input_categoria');
    const inputSubcategoria = $('#input_subcategoria');
    const inputEstabelecimento = $('#input_estabelecimento');
    const inputPesquisa = $('#input_pesquisa');
    const inputOrdem = $('#input_ordem');

    let pagina = '';
    const buscarParceiro = async () => {
        blocoLoading.classList.remove('display_none');
        blocoCarregarMais.classList.add('display_none');
        if (pagina != '') {
            pagina++;
        }
        const resposta = await ajaxPost(
            LINK + '/convenios/listar',
            {
                pagina,
                tipo,
                latitude: inputLatitude.value,
                longitude: inputLongitude.value,
                acessado: inputAcessado.value,
                favorito: inputFavorito.value,
                estado: inputEstado.value,
                categoria: inputCategoria.value,
                subcategoria: inputSubcategoria.value,
                estabelecimento: inputEstabelecimento.value,
                pesquisa: inputPesquisa.value,
                ordem: inputOrdem.value,
            },
            ''
        );

        blocoLoading.classList.add('display_none');
        if (false === resposta) {
            blocoErro.classList.remove('display_none');
            return;
        }
        if (tipo == 'loja') {
            adicionarListaLoja(resposta.dado);
        }
        pagina = resposta.dado.paginacao.atual;
        if (resposta.dado.paginacao.total > resposta.dado.paginacao.atual) {
            blocoCarregarMais.classList.remove('display_none');
        }
    };
    buscarParceiro();
    botaoCarregarMais.addEventListener('click', () => {
        buscarParceiro();
    });

    const adicionarListaLoja = parceiro => {
        if (parceiro.lista.length == 0 && pagina == '') {
            blocoZero.classList.remove('display_none');
            return;
        }
        parceiro.lista.forEach(item => {
            const clone = blocoLoja.cloneNode(true);
            const favorito = clone.querySelector('.botao_favorito');
            favorito.setAttribute('dta-url', item.id);
            if (item.favorito == 'sim') {
                favorito.classList.add('favorito_marcado');
            }
            clone.querySelector('.item_link').setAttribute('href', item.link);
            clone.querySelector('.item_logo').innerHTML = `<img src="${item.imagem}">`;
            clone.querySelector('.item_titulo').innerText = item.titulo;
            clone.querySelector('.item_desconto').innerText = item.desconto;
            if (item.estado != '') {
                clone.querySelector('.bloco_estado').classList.remove('display_none');
                clone.querySelector('.item_estado').innerText = item.estado;
            }

            blocoLista.appendChild(clone);
        });
    };
};
window.addEventListener('load', () => {
    parceiroLoading('loja');
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
            fecharPreviaMapa();
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
