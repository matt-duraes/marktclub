window.addEventListener('load', async () => {
    const blocoMapa = $('#bloco_mapa');
    if (!blocoMapa) {
        return;
    }
    const idLoja = $('#input_loja_id').value;

    async function buscarEndereco() {
        const resposta = await ajaxPost(
            LINK + '/endereco',
            {
                id: idLoja,
                local: 'loja',
            },
            ''
        );
        ppe(resposta);
        // if (false == resposta || false === resposta.dado.existe) {
        //     blocoMapa.classList.add('display_none');
        //     return;
        // }
        // blocoMapa.classList.remove('display_none');
        // return resposta.dado;
    }
    buscarEndereco();

    // const botaoBuscar = $('#botao_endereco_buscar');
    // const botaoLink = $('#botao_endereco_link');
    // const blocoDetalheTexto = $('#bloco_detalhe_texto');
    // const blocoGoogleMap = $('#bloco_endereco_google_map');
    // const conteudoPopupEndereco = $('#conteudo_popup_endereco');
    // const PopupEndereco = new Popup('endereco', 'bloco_endereco', true, true);

    // const enderecos = await buscarEndereco();
    // if (!enderecos) {
    //     return;
    // }

    // blocoDetalheTexto.innerText = pegarTextoDetalhe(enderecos.principal);
    // botaoLink.setAttribute('href', enderecos.principal.link);
    // adicionarEndereco(enderecos.principal.latitude, enderecos.principal.longitude);

    // if (enderecos.quantidade <= 1) {
    //     botaoBuscar.classList.add('display_none');
    //     return;
    // }

    // botaoBuscar.addEventListener('click', () => {
    //     PopupEndereco.abrir();
    // });

    // exibirEnderecosPorCategoria(enderecos.endereco);
    // adicionarEventoBotaoEndereco();

    // function exibirEnderecosPorCategoria(enderecos) {
    //     let htmlString = '';

    //     for (const pais in enderecos) {
    //         for (const estado in enderecos[pais]) {
    //             htmlString += `<div class="bloco_categoria_estado">
    //                                 <div class="bloco_estado_titulo">
    //                                     ${estado}
    //                                     <hr class="divisoria_estado"/>
    //                                 </div>
    //                                 `;

    //             for (const cidade in enderecos[pais][estado]) {
    //                 htmlString += `<ul class="bloco_categoria_cidade">
    //                                     <li class="bloco_cidade_titulo">
    //                                         ${cidade}
    //                                     </li>`;

    //                 const listaEnderecos = enderecos[pais][estado][cidade];
    //                 const enderecoItems = listaEnderecos.map(
    //                     e => `
    //                     <li class="bloco_endereco_item">
    //                         <div class="bloco_detalhe_endereco">
    //                             <p class="endereco">${e.endereco}</p>
    //                             <div class="botao_geral_cor botao_buscar_endereco">Buscar</div>

    //                             <p class="display_none latitude">${e.latitude}</p>
    //                             <p class="display_none longitude">${e.longitude}</p>
    //                             <p class="display_none telefone">${e.telefone}</p>
    //                             <p class="display_none link">${e.link}</p>
    //                         </div>
    //                     </li>`
    //                 );

    //                 htmlString += enderecoItems.join('');
    //                 htmlString += '</ul>';
    //             }

    //             htmlString += '</div>';
    //         }
    //     }

    //     conteudoPopupEndereco.innerHTML = htmlString;
    // }

    // function adicionarEventoBotaoEndereco() {
    //     const listaBotao = $$('.botao_buscar_endereco');
    //     listaBotao.forEach(botao => {
    //         botao.addEventListener('click', e => {
    //             const bloco = e.target.parentNode;
    //             const latitude = bloco.querySelector('.latitude').innerText;
    //             const longitude = bloco.querySelector('.longitude').innerText;
    //             const endereco = bloco.querySelector('.endereco').innerText;
    //             const telefone = bloco.querySelector('.telefone').innerText;
    //             const link = bloco.querySelector('.link').innerText;

    //             adicionarEndereco(latitude, longitude);
    //             blocoDetalheTexto.innerText = pegarTextoDetalhe({ telefone, endereco });

    //             botaoLink.setAttribute('href', link);

    //             PopupEndereco.fechar();
    //         });
    //     });
    // }

    // async function adicionarEndereco(latitude, longitude) {
    //     latitude = parseFloat(latitude);
    //     longitude = parseFloat(longitude);
    //     const posicao = { lat: latitude, lng: longitude };

    //     const { Map } = await google.maps.importLibrary('maps');
    //     const option = {
    //         scrollwheel: false,
    //         zoom: 14,
    //         center: posicao,
    //         disableDefaultUI: true,
    //         panControl: false,
    //         zoomControl: false,
    //         clickableIcons: false,
    //         mapId: 'bloco_endereco_google_map',
    //     };
    //     const mapa = new Map(blocoGoogleMap, option);

    //     const { AdvancedMarkerElement } = await google.maps.importLibrary('marker');
    //     const markers = [0].map(() => {
    //         const icone = document.createElement('img');
    //         icone.src = LINK + '/images/mapa_icone.png';

    //         const marker = new google.maps.marker.AdvancedMarkerElement({
    //             position: posicao,
    //             content: icone,
    //         });
    //         return marker;
    //     });

    //     new markerClusterer.MarkerClusterer({ markers, map: mapa });
    // }

    // function pegarTextoDetalhe(dado) {
    //     if (dado.telefone && dado.telefone != 'null') {
    //         return `${dado.endereco} - ${formatarTelefone(dado.telefone)}`;
    //     }
    //     return dado.endereco;
    // }

    // function formatarTelefone(telefone) {
    //     if (!telefone) return;

    //     const numeros = telefone.toString().replace(/\D/g, '');

    //     if (numeros.length === 10) {
    //         return `(${numeros.substr(0, 2)}) ${numeros.substr(2, 4)}-${numeros.substr(6, 4)}`;
    //     } else if (numeros.length === 11) {
    //         return `(${numeros.substr(0, 2)}) ${numeros.substr(2, 5)}-${numeros.substr(7, 4)}`;
    //     }

    //     return telefone;
    // }
});
