// @template "site"
// @resource "site/loja/busca"
// @resource "site/loja/favorito"
// @resource "site/loja/parceiro"
// @resource "site/busca"
// @system "Esqueleto"

const blocoMapa = $('#bloco_loja_mapa');
const blocoCarregarMais = $('#bloco_carregar_mais');
const botaoAtualizar = $('#botao_atualizar_mapa');
const blocoPrevia = $('#bloco_parceiro_previa');
const blocoPreviaFechar = $('#bloco_parceiro_previa .fechar');
const blocoPreviaFigure = $('#bloco_parceiro_previa figure');
const blocoPreviaTitulo = $('#bloco_parceiro_previa h1');
const blocoPreviaDesconto = $('#bloco_parceiro_previa .desconto');
const blocoPreviaLink = $('#bloco_parceiro_previa a');

const inputAcessado = $('#input_acessado');
const inputFavorito = $('#input_favorito');
const inputLatitude = $('#input_latitude');
const inputLongitude = $('#input_longitude');

const latitude = inputLatitude ? inputLatitude.value : 0;
const longitude = inputLongitude ? inputLongitude.value : 0;
let latitudeValor = latitude;
let longitudeValor = longitude;

const tipo = $('#input_tipo_geral').valor();

const blocoLoading = $('#bloco_parceiro_loading');
const blocoLista = $('#bloco_parceiro_lista');

const blocoZero = $('#bloco_parceiro_zero');

const botaoCarregarMais = $('#botao_carregar_mais');

const loading = $$('.parceiro_esqueleto');
loading.forEach(item => {
    const EsqueletoItem = new Esqueleto(item, '.esqueleto');
    EsqueletoItem.show();
});

const inputMapa = $('#input_mapa input');
const inputEstado = $('#input_estado');
const inputCidade = $('#input_cidade');
const inputCategoria = $('#input_categoria');
const inputSubcategoria = $('#input_subcategoria');
const inputEstabelecimento = $('#input_estabelecimento');
const inputPesquisa = $('#input_pesquisa');
const inputOrdem = $('#input_ordem');
const formBusca = $('#form_buscar');

const acessadoValor = inputAcessado ? inputAcessado.value : '';
const favoritoValor = inputFavorito ? inputFavorito.value : '';
const estadoValor = inputEstado ? inputEstado.value : '';
const cidadeValor = inputCidade ? inputCidade.value : '';
const categoriaValor = inputCategoria ? inputCategoria.value : '';
const subcategoriaValor = inputSubcategoria ? inputSubcategoria.value : '';
const estabelecimentoValor = inputEstabelecimento ? inputEstabelecimento.value : '';
const pesquisaValor = inputPesquisa ? inputPesquisa.value : '';
const ordemValor = inputOrdem ? inputOrdem.value : '';

const carregarMapa = inputMapa && inputMapa.checked;
if (inputMapa) {
    inputMapa.addEventListener('change', () => {
        formSelectOption(inputCidade, { '': 'Escolha um estado primeiro' });
        formValue(inputEstabelecimento, '');
        formValue(inputOrdem, '');
        formBusca.classList.toggle('busca_mapa');
        if (inputMapa.checked && (inputLatitude.value == '' || inputLongitude.value == '')) {
            buscarGeolocalizacao();
        }
        if (inputMapa.checked && inputEstado.value != '') {
            buscarCidadePeloEstado(inputCidade, inputEstado.value, inputCidade.value, 'Escolha uma cidade');
        }
        if (!inputMapa.checked) {
            inputLatitude.value = '';
            inputLongitude.value = '';
        }
    });
}
const buscarGeolocalizacao = () => {
    Loading.show();
    navigator.geolocation.getCurrentPosition(
        position => {
            Loading.hide();
            inputLatitude.value = position.coords.latitude;
            inputLongitude.value = position.coords.longitude;
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
if (inputEstado) {
    inputEstado.addEventListener('formChange', () => {
        buscarCidadePeloEstado(inputCidade, inputEstado.value, '', 'Escolha uma cidade');
    });
    if (inputEstado.value != '') {
        buscarCidadePeloEstado(inputCidade, inputEstado.value, inputCidade.value, 'Escolha uma cidade');
    }
}

const montarBody = () => {
    const body = {
        pagina: pagina != '' ? pagina : 1,
        tipo,
    };
    if (latitude != '') {
        body.latitude = latitudeValor;
    }
    if (longitude != '') {
        body.longitude = longitudeValor;
    }
    if (acessadoValor != '') {
        body.acessado = acessadoValor;
    }
    if (favoritoValor != '') {
        body.favorito = favoritoValor;
    }
    if (estadoValor != '') {
        body.estado = estadoValor;
    }
    if (cidadeValor != '') {
        body.cidade = cidadeValor;
    }
    if (categoriaValor != '') {
        body.categoria = categoriaValor;
    }
    if (subcategoriaValor != '') {
        body.subcategoria = subcategoriaValor;
    }
    if (estabelecimentoValor != '') {
        body.estabelecimento = estabelecimentoValor;
    }
    if (pesquisaValor != '') {
        body.pesquisa = pesquisaValor;
    }
    if (ordemValor != '') {
        body.ordem = ordemValor;
    }
    return body;
};

let pagina = '';
const buscarParceiro = async () => {
    if (inputLatitude && inputLongitude) {
        latitudeValor = inputLatitude.value;
        longitudeValor = inputLongitude.value;
    }

    if (botaoCarregarMais.classList.contains('loading')) {
        return;
    }
    botaoCarregarMais.classList.add('loading');
    blocoLoading.classList.remove('display_none');
    if (pagina != '') {
        pagina++;
    }

    const resposta = await ajaxPost(LINK + '/convenios/listar', montarBody(), '');
    blocoLoading.classList.add('display_none');
    botaoCarregarMais.classList.remove('loading');
    if (false === resposta) {
        if (pagina == 1 || pagina == '') {
            blocoZero.classList.remove('display_none');
        }
        return;
    }
    manipularRetornoLoja(blocoLista, resposta.dado);
    pagina = resposta.dado.paginacao.atual;
    if (resposta.dado.paginacao.total > resposta.dado.paginacao.atual) {
        blocoCarregarMais.classList.remove('display_none');
    } else {
        blocoCarregarMais.classList.add('display_none');
    }
};
buscarParceiro();
botaoCarregarMais.addEventListener('click', () => {
    buscarParceiro();
});

const manipularRetornoLoja = (bloco, parceiro) => {
    if (parceiro.lista.length == 0 && (pagina == 1 || pagina == '')) {
        blocoZero.classList.remove('display_none');
        return;
    }
    const listaFake = $$('.article_fake');
    listaFake.forEach(item => {
        item.remove();
    });
    if (carregarMapa) {
        carregarPontoMapa(parceiro.mapa);
    }
    parceiro.lista.forEach(item => {
        adicionarParceiro(bloco, item, tipo);
    });
    bloco.insertAdjacentHTML('beforeend', `<div class="article_fake"></div><div class="article_fake"></div>`);
};

window.addEventListener('load', () => {
    if (!blocoMapa) {
        return false;
    }
    const blocoLoja = $('#bloco_loja_index');
    const blocoBusca = $('#bloco_buscar');
    const blocoParceiro = $('#bloco_loja_index .bloco_parceiro');
    const botaoMapa = $('#botao_visualizar_mapa');
    const botaoLista = $('#botao_visualizar_lista');
    botaoMapa.addEventListener('click', () => {
        botaoMapa.classList.add('display_none');
        botaoLista.classList.remove('display_none');
        blocoMapa.classList.remove('display_none');
        blocoParceiro.classList.add('display_none');
        blocoCarregarMais.classList.add('bloco_carregar_mais_mapa');
        blocoLoja.classList.add('bloco_mapa');
        blocoBusca.classList.add('display_none');
    });
    botaoLista.addEventListener('click', () => {
        botaoMapa.classList.remove('display_none');
        botaoLista.classList.add('display_none');
        blocoMapa.classList.add('display_none');
        blocoParceiro.classList.remove('display_none');
        blocoCarregarMais.classList.remove('bloco_carregar_mais_mapa');
        blocoLoja.classList.remove('bloco_mapa');
        blocoBusca.classList.remove('display_none');
    });
});

const MAPA = {
    mapa: null,
    latitude: parseFloat(latitude),
    longitude: parseFloat(longitude),
};

window.addEventListener('load', () => {
    const blocoMapa = $('#bloco_google_maps');
    if (!blocoMapa) {
        return;
    }

    async function iniciarMapa() {
        const { Map } = await google.maps.importLibrary('maps');
        const option = {
            scrollwheel: false,
            zoom: 13,
            center: { lat: MAPA.latitude, lng: MAPA.longitude },
            disableDefaultUI: true,
            panControl: false,
            zoomControl: false,
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
    }
    iniciarMapa();

    botaoAtualizar.addEventListener('click', () => {
        botaoAtualizar.classList.add('display_none');
        const posicao = MAPA.mapa.getCenter();
        MAPA.latitude = posicao.lat();
        MAPA.longitude = posicao.lng();
        iniciarMapa();
        pagina = '';
        inputLatitude.value = MAPA.latitude;
        inputLongitude.value = MAPA.longitude;
        buscarParceiro();
    });
});

const carregarPontoMapa = async loja => {
    const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');

    const markers = loja.map(item => {
        const imagem = item.imagem;
        const link = item.link;
        const titulo = item.titulo;
        const desconto = item.desconto;
        const botaoWpp = document.querySelector('.botao_chat');
        const icone = document.createElement('figure');
        icone.classList.add('icone_mapa');
        icone.style = `background-image: url(${imagem})`;

        const marker = new google.maps.marker.AdvancedMarkerElement({
            position: {
                lat: parseFloat(item.latitude),
                lng: parseFloat(item.longitude),
            },
            content: icone,
        });

        marker.addListener('click', () => {
            abrirPreviaMapa(titulo, desconto, imagem, link, botaoWpp);
        });
        return marker;
    });

    new markerClusterer.MarkerClusterer({ markers, map: MAPA.mapa });
};

const abrirPreviaMapa = (titulo, desconto, imagem, link, botaoWpp) => {
    botaoWpp.classList.add('display_none');
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
    const botaoWpp = document.querySelector('.botao_chat');
    blocoPrevia.classList.remove('ativo');
    botaoWpp.classList.remove('display_none');
    setTimeout(() => {
        blocoPrevia.classList.add('display_none');
        blocoPreviaFigure.innerHTML = '';
        blocoPreviaTitulo.innerText = '';
        blocoPreviaDesconto.innerText = '';
        blocoPreviaLink.removeAttribute('href');
    }, 300);
};
if (blocoPreviaFechar) {
    blocoPreviaFechar.addEventListener('click', () => {
        fecharPreviaMapa();
    });
}
