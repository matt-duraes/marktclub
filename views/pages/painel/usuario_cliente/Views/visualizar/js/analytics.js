window.addEventListener('load', () => {
    /*
    |--------------------------------------------------------------------------
    | VERIFICA SE TEM DEPENDENTE
    |--------------------------------------------------------------------------
    */
    const blocoAnalytics = document.querySelector('#bloco_analytics');
    if (!blocoAnalytics) {
        return;
    }

    Calendario.init({
        de: 'input_relatorio_data_de',
        ate: 'input_relatorio_data_ate',
    });

    const botaoBuscar = document.getElementById('botao_buscar_analytics');
    const botaoCarregarMais = document.querySelector('#bloco_analytics_visualizar .botao_carregar_mais');
    const botaoAnalyticsAbrir = document.querySelector('#bloco_analytics .botao_analytics_abrir');
    const botaoAnalyticsFechar = document.getElementById('botao_analytics_fechar');
    const blocoAnalyticsVisualizar = document.getElementById('bloco_analytics_visualizar');
    const blocoListaIndex = document.getElementById('bloco_analytics_lista_index');
    const blocoListaGeral = document.getElementById('bloco_analytics_lista_geral');

    const idUsuario = document.querySelector('#input_visualizar_id').value;
    const inputDe = document.getElementById('input_relatorio_data_de');
    const inputAte = document.getElementById('input_relatorio_data_ate');

    const buscarAnalytics = async (blocoLista, pagina, quantidade, de, ate) => {
        if (blocoLista == blocoListaGeral) {
            Loading.show();
        }

        const body = new FormData();
        body.append('usuario', idUsuario);
        body.append('pagina', pagina);
        body.append('quantidade', quantidade);
        body.append('indice', 'analytics');
        if (de != undefined && ate != undefined) {
            body.append('de', de);
            body.append('ate', ate);
        }

        const resposta = await fetch(LINK + '/app/ajax/usuario-cliente', {
            method: 'POST',
            body,
        });

        const loading = blocoLista.querySelector('.loading');
        if (loading) {
            loading.parentNode.removeChild(loading);
        }

        const json = await respostaJson(resposta, 'Ocorreu um erro ao buscar o analytics.');

        if (blocoLista == blocoListaGeral) {
            Loading.hide();
        }

        if (false === json) {
            return;
        }

        if (json.dado.lista == 0) {
            blocoLista.insertAdjacentHTML('beforeend', `<div class="zero">Sem dados no momento</div>`);
            return;
        }

        if (blocoLista == blocoListaGeral && pagina == 1) {
            abrirBlocoVisualizarAnalytics();
        }

        if (blocoLista == blocoListaIndex) {
            blocoAnalytics.querySelector('.botao_link').classList.remove('display_none');
        } else if (blocoLista == blocoListaGeral && json.dado.pagina.total > pagina) {
            botaoCarregarMais.classList.remove('display_none');
        } else if (blocoLista == blocoListaGeral && json.dado.pagina.total <= pagina) {
            botaoCarregarMais.classList.add('display_none');
        }
        montarListaAnalytics(blocoLista, json.dado.lista);
    };
    buscarAnalytics(blocoListaIndex, 1, 5);

    const montarListaAnalytics = (bloco, lista) => {
        lista.forEach(item => {
            let icone =
                '<svg height="15" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M12.7,0H5.5C2.4,0,0,2.4,0,5.5v7.3c0,3,2.4,5.5,5.5,5.5h7.3c3,0,5.5-2.4,5.5-5.5V5.5C18.2,2.4,15.7,0,12.7,0z M14.5,12.7c0,1-0.8,1.8-1.8,1.8H5.5c-1,0-1.8-0.8-1.8-1.8V5.5c0-1,0.8-1.8,1.8-1.8h7.3c1,0,1.8,0.8,1.8,1.8V12.7z"/><path class="st0" d="M12.7,21.8H5.5c-3,0-5.5,2.4-5.5,5.5v7.3c0,3,2.4,5.5,5.5,5.5h7.3c3,0,5.5-2.4,5.5-5.5v-7.3 C18.2,24.3,15.7,21.8,12.7,21.8z M14.5,34.5c0,1-0.8,1.8-1.8,1.8H5.5c-1,0-1.8-0.8-1.8-1.8v-7.3c0-1,0.8-1.8,1.8-1.8h7.3 c1,0,1.8,0.8,1.8,1.8V34.5z"/><path class="st0" d="M34.5,21.8h-7.3c-3,0-5.5,2.4-5.5,5.5v7.3c0,3,2.4,5.5,5.5,5.5h7.3c3,0,5.5-2.4,5.5-5.5v-7.3 C40,24.3,37.6,21.8,34.5,21.8z M36.4,34.5c0,1-0.8,1.8-1.8,1.8h-7.3c-1,0-1.8-0.8-1.8-1.8v-7.3c0-1,0.8-1.8,1.8-1.8h7.3 c1,0,1.8,0.8,1.8,1.8V34.5z"/><path class="st0" d="M34.5,0h-7.3c-3,0-5.5,2.4-5.5,5.5v7.3c0,3,2.4,5.5,5.5,5.5h7.3c3,0,5.5-2.4,5.5-5.5V5.5C40,2.4,37.6,0,34.5,0 z M36.4,12.7c0,1-0.8,1.8-1.8,1.8h-7.3c-1,0-1.8-0.8-1.8-1.8V5.5c0-1,0.8-1.8,1.8-1.8h7.3c1,0,1.8,0.8,1.8,1.8V12.7z"/></svg>';
            if (item.dispositivo == 'Desktop') {
                icone =
                    '<svg height="17" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 25.3 24" style="enable-background:new 0 0 25.3 24;" xml:space="preserve"><path d="M2.5,16.4h20.2V2.5H2.5V16.4z M13.9,18.9v2.5h5.1V24H6.3v-2.5h5.1v-2.5H1.3c-0.7,0-1.3-0.6-1.3-1.3c0,0,0,0,0,0V1.3C0,0.6,0.6,0,1.3,0H24c0.7,0,1.3,0.6,1.3,1.3v16.4c0,0.7-0.6,1.3-1.3,1.3H13.9z"/></svg>';
            } else if (item.dispositivo == 'Mobile Phone') {
                icone =
                    '<svg height="20" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 15.5 24" style="enable-background:new 0 0 15.5 24;" xml:space="preserve"><path d="M2.3,2.2h12c0.6,0,1.1,0.5,1.1,1.1v19.6c0,0.6-0.5,1.1-1.1,1.1H1.2c-0.6,0-1.1-0.5-1.1-1.1V0h2.2V2.2z M2.3,9.8h10.9V4.4H2.3V9.8z M2.3,12v9.8h10.9V12H2.3z"/></svg>';
            } else if (item.dispositivo == 'App' && item.os == 'Android') {
                icone =
                    '<svg height="17" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 21.6 24" style="enable-background:new 0 0 21.6 24;" xml:space="preserve"><path d="M19.2,13.2H2.4v8.4h16.8V13.2z M19.2,10.8c0-4.6-3.8-8.4-8.4-8.4s-8.4,3.8-8.4,8.4H19.2z M4.1,2.4C6,0.8,8.3,0,10.8,0c2.5,0,4.9,0.9,6.7,2.4l1.7-1.7L21,2.3l-1.7,1.7c1.5,1.9,2.4,4.3,2.4,6.7v12c0,0.7-0.5,1.2-1.2,1.2H1.2C0.5,24,0,23.5,0,22.8v-12c0-2.5,0.9-4.9,2.4-6.7L0.6,2.3l1.7-1.7L4.1,2.4L4.1,2.4z M7.2,8.4C6.5,8.4,6,7.9,6,7.2S6.5,6,7.2,6s1.2,0.5,1.2,1.2S7.9,8.4,7.2,8.4z M14.4,8.4c-0.7,0-1.2-0.5-1.2-1.2S13.7,6,14.4,6s1.2,0.5,1.2,1.2S15.1,8.4,14.4,8.4z"/></svg>';
            } else if (item.dispositivo == 'App' && item.os == 'IOS') {
                icone =
                    '<svg height="19" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 19.6 24" style="enable-background:new 0 0 19.6 24;" xml:space="preserve"><path d="M14.4,8c-0.5,0-1.1,0.1-1.9,0.4c0.1,0-0.8,0.3-1,0.4c-0.5,0.2-1,0.3-1.5,0.3C9.4,9.1,9,9,8.4,8.8C8.3,8.7,8.1,8.7,7.9,8.6c-0.1,0-0.4-0.2-0.5-0.2C6.7,8.1,6.3,8,6,8C4.7,8,3.6,8.8,2.9,9.9c-1.4,2.4-0.6,6.8,1.4,9.8c1.1,1.6,1.7,2.1,1.9,2.1c0.2,0,0.4-0.1,0.8-0.2l0.2-0.1c1.1-0.5,1.9-0.7,3-0.7c1.1,0,1.8,0.2,2.9,0.7l0.2,0.1c0.4,0.2,0.6,0.2,0.9,0.2c0.4,0,0.9-0.5,1.9-2c0.3-0.4,0.5-0.9,0.8-1.3c-0.1-0.1-0.3-0.2-0.4-0.4c-1.4-1.3-2.3-3.1-2.3-5.3c0-1.6,0.5-3.2,1.5-4.5C15.3,8.1,14.8,8,14.4,8z M14.5,5.8c0.8,0.1,3,0.3,4.4,2.4c-0.1,0.1-2.6,1.5-2.6,4.6c0,3.6,3.2,4.8,3.2,4.9c0,0.1-0.5,1.7-1.7,3.4c-1,1.5-2,2.9-3.7,2.9c-1.6,0-2.1-0.9-4-0.9c-1.8,0-2.4,0.9-3.9,1c-1.6,0.1-2.8-1.6-3.8-3C0.5,18-1.1,12.5,1,8.9c1.1-1.8,2.9-3,5-3c1.5,0,3,1,4,1C10.9,6.9,12.5,5.6,14.5,5.8z M13.3,3.8c-0.8,1-2.2,1.8-3.6,1.7C9.6,4.2,10.2,2.7,11,1.8c0.9-1,2.3-1.8,3.5-1.8C14.7,1.4,14.1,2.8,13.3,3.8z"/></svg>';
            } else if (item.dispositivo == 'Tablet') {
                icone =
                    '<svg height="17" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 19.2 24" style="enable-background:new 0 0 19.2 24;" xml:space="preserve"><path d="M2.4,2.4v19.2h14.4V2.4H2.4z M1.2,0H18c0.7,0,1.2,0.5,1.2,1.2v21.6c0,0.7-0.5,1.2-1.2,1.2H1.2C0.5,24,0,23.5,0,22.8V1.2C0,0.5,0.5,0,1.2,0z M9.6,18c0.7,0,1.2,0.5,1.2,1.2c0,0.7-0.5,1.2-1.2,1.2c-0.7,0-1.2-0.5-1.2-1.2C8.4,18.5,8.9,18,9.6,18z"/></svg>';
            } else if (item.dispositivo == 'TV Device') {
                icone =
                    '<svg height="17" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 24.1 24" style="enable-background:new 0 0 24.1 24;" xml:space="preserve"><path d="M16.1,4.8h6.7c0.7,0,1.2,0.5,1.2,1.2v16.8c0,0.7-0.5,1.2-1.2,1.2H1.2C0.5,24,0,23.5,0,22.8c0,0,0,0,0,0V6c0-0.7,0.5-1.2,1.2-1.2h6.7L4.9,1.7L6.6,0l4.8,4.8h1.4L17.5,0l1.7,1.7L16.1,4.8z M2.4,7.2v14.4h19.2V7.2H2.4z"/></svg>';
            }

            bloco.insertAdjacentHTML(
                'beforeend',
                `
                <div class="linha">
                    <div class="icone item_data_ajuda" data-ajuda="${item.dispositivo}">${icone}</div>
                    <div class="pagina">${item.url}</div>
                    <div class="data">${item.data}</div>
                </div>
                `
            );
        });
        colocarDataAjuda();
    };
    const colocarDataAjuda = () => {
        const lista = document.querySelectorAll('.item_data_ajuda');
        if (lista.length == 0) {
            return;
        }
        lista.forEach(item => {
            item.classList.remove('item_data_ajuda');
            item.addEventListener('mouseover', () => {
                const texto = item.getAttribute('data-ajuda');
                Ajuda.show(item, texto);
            });
            item.addEventListener('mouseout', () => {
                Ajuda.hide();
            });
        });
    };

    /*
    |--------------------------------------------------------------------------
    | BUSCAR TODOS OS DADOS
    |--------------------------------------------------------------------------
    */
    botaoAnalyticsAbrir.addEventListener('click', () => {
        buscarAnalytics(blocoListaGeral, 1, 50);
    });
    const abrirBlocoVisualizarAnalytics = () => {
        if (blocoAnalyticsVisualizar.classList.contains('abrir')) {
            return;
        }
        blocoAnalyticsVisualizar.classList.remove('display_none');
        setTimeout(() => {
            blocoAnalyticsVisualizar.classList.add('abrir');
        }, 40);
    };
    botaoAnalyticsFechar.addEventListener('click', () => {
        fecharBlocoVisualizarAnalytics();
    });
    blocoAnalyticsVisualizar.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_analytics_visualizar') {
            fecharBlocoVisualizarAnalytics();
        }
    });
    const fecharBlocoVisualizarAnalytics = () => {
        blocoAnalyticsVisualizar.classList.remove('abrir');
        setTimeout(() => {
            blocoAnalyticsVisualizar.classList.add('display_none');
        }, 300);
    };

    let paginaAtual = 1;
    let deAtual, ateAtual;
    botaoCarregarMais.addEventListener('click', () => {
        paginaAtual++;
        botaoCarregarMais.classList.add('display_none');
        buscarAnalytics(blocoListaGeral, paginaAtual, 50, deAtual, ateAtual);
    });
    inputDe.addEventListener('keyup', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            document.querySelector('#fw_calendario').style.display = 'none';
            inputDe.blur();
            buscarListaAnalytics();
        }
    });
    inputAte.addEventListener('keyup', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            document.querySelector('#fw_calendario').style.display = 'none';
            inputAte.blur();
            buscarListaAnalytics();
        }
    });
    botaoBuscar.addEventListener('click', () => {
        buscarListaAnalytics();
    });
    const buscarListaAnalytics = () => {
        if (inputDe.value == '' && inputAte.value == '') {
            executarNovaBusca();
            return;
        }

        const de = converterData(inputDe.value);
        const ate = converterData(inputAte.value);
        const reg = new RegExp('^[0-9]{4}-[0-9]{2}-[0-9]{2}$');
        if (!reg.test(de)) {
            Alerta.notificacao('A data de início da busca deve ser uma data válida.', false);
            return;
        } else if (!reg.test(ate)) {
            Alerta.notificacao('A data final da busca deve ser uma data válida.', false);
            return;
        }
        executarNovaBusca(de, ate);
    };
    const executarNovaBusca = (de, ate) => {
        deAtual = de;
        ateAtual = ate;
        paginaAtual = 1;

        botaoCarregarMais.classList.add('display_none');
        blocoListaGeral.innerHTML = '';
        buscarAnalytics(blocoListaGeral, 1, 50, de, ate);
    };
    const converterData = data => {
        const e = data.split('/');
        return e[2] + '-' + e[1] + '-' + e[0];
    };
});
