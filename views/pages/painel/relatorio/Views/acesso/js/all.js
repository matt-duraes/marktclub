// @template "painel"
// @system "Grafico"

window.addEventListener('load', () => {
    const LINK = document.querySelector('#LINK').value;
    const inputEmpresa = document.querySelector('#input_relatorio_empresa');
    const inputDe = document.querySelector('#input_relatorio_data_de');
    const inputAte = document.querySelector('#input_relatorio_data_ate');
    const botaoBuscar = document.querySelector('#botao_buscar_relatorio');

    Calendario.init({
        de: 'input_relatorio_data_de',
        ate: 'input_relatorio_data_ate',
    });

    /*
    |--------------------------------------------------------------------------
    | CARREGAR GRÁFICO DE ACESSO
    |--------------------------------------------------------------------------
    */
    const graficoAcesso = document.querySelector('#grafico_acesso');
    const buscarAcessoPorPagina = async () => {
        const de = inputDe.value;
        const ate = inputAte.value;
        const empresa = inputEmpresa ? inputEmpresa.value : '';

        graficoAcesso.classList.add('loading');
        const resposta = await fetch(LINK + `/relatorio/acesso-dia?de=${de}&ate=${ate}&empresa=${empresa}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        graficoAcesso.classList.remove('loading');

        if (resposta.status != 200 || json.dado == undefined) {
            return;
        }

        carregarAcessoPorPagina(json.dado);
    };
    buscarAcessoPorPagina();
    const carregarAcessoPorPagina = data => {
        const loading = graficoAcesso.querySelector('.loading_geral');
        if (loading) {
            loading.parentNode.removeChild(loading);
        }

        const Linha = new Grafico('#grafico_acesso');
        Linha.titulo('Páginas acessadas').header(data.header).dado(data.data).label(data.label).linha();
    };

    /*
    |--------------------------------------------------------------------------
    | CARREGA PÁGINA MAIS ACESSADAS
    |--------------------------------------------------------------------------
    */
    const listaAcessoUsuario = document.querySelector('#lista_acesso_usuario .fw_grafico_bloco_lista');
    const listaAcessoPagina = document.querySelector('#lista_acesso_pagina .fw_grafico_bloco_lista');
    const listaAcessoParceiro = document.querySelector('#lista_acesso_parceiro .fw_grafico_bloco_lista');
    const listaAcessoParceiroOnline = document.querySelector('#lista_acesso_parceiro_online .fw_grafico_bloco_lista');
    const listaAcessoParceiroFisico = document.querySelector('#lista_acesso_parceiro_fisico .fw_grafico_bloco_lista');

    const buscarMaisAcessado = async local => {
        const de = inputDe.value;
        const ate = inputAte.value;
        const empresa = inputEmpresa ? inputEmpresa.value : '';

        let bloco, loading;
        let uri = '';
        if (local == 'usuario') {
            bloco = listaAcessoUsuario;
            loading = document.querySelector('#lista_acesso_usuario');
        } else if (local == 'loja') {
            bloco = listaAcessoParceiro;
            loading = document.querySelector('#lista_acesso_parceiro');
        } else if (local == 'loja-online') {
            bloco = listaAcessoParceiroOnline;
            loading = document.querySelector('#lista_acesso_parceiro_online');
            uri = '&estabelecimento=online';
        } else if (local == 'loja-fisico') {
            bloco = listaAcessoParceiroFisico;
            loading = document.querySelector('#lista_acesso_parceiro_fisico');
        } else if (local == 'pagina') {
            bloco = listaAcessoPagina;
            loading = document.querySelector('#lista_acesso_pagina');
        }

        loading.classList.add('loading');

        const resposta = await fetch(
            LINK + `/relatorio/mais-acessado?local=${local}&de=${de}&ate=${ate}&empresa=${empresa}${uri}`,
            {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        );

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        loading.classList.remove('loading');

        if (resposta.status != 200 || json.dado == undefined) {
            return;
        }

        carregarListaMaisAcesso(json.dado, bloco, local);
    };
    buscarMaisAcessado('usuario');
    buscarMaisAcessado('pagina');
    buscarMaisAcessado('loja-online');
    buscarMaisAcessado('loja-fisica');

    const carregarListaMaisAcesso = (data, bloco, local) => {
        let html = `<div class="scroll">`;
        data.forEach(item => {
            html += `
                <div class="linha">
                    <div class="item">${item[local]}</div>
                    <div class="porcentagem"><span style="width: ${item.porcentagem}%"></span></div>
                    <div class="valor"><span>(${item.porcentagem}%)</span>${item.total}</div>
                </div>
            `;
        });
        html += `</div>`;
        bloco.innerHTML = html;
    };

    /*
    |--------------------------------------------------------------------------
    | GRAFICO POR DISPOSITIVO
    |--------------------------------------------------------------------------
    */
    const graficoDispositivo = document.querySelector('#grafico_dispositivo');
    const graficoNavegador = document.querySelector('#grafico_navegador');
    const graficoOs = document.querySelector('#grafico_os');

    const buscarPorDispositivo = async tipo => {
        const de = inputDe.value;
        const ate = inputAte.value;
        const empresa = inputEmpresa ? inputEmpresa.value : '';

        let bloco;
        if (tipo == 'dispositivo') {
            bloco = graficoDispositivo;
        } else if (tipo == 'navegador') {
            bloco = graficoNavegador;
        } else if (tipo == 'os') {
            bloco = graficoOs;
        }
        bloco.classList.add('loading');

        const resposta = await fetch(
            LINK + `/relatorio/dispositivo?tipo=${tipo}&de=${de}&ate=${ate}&empresa=${empresa}`,
            {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                },
            }
        );

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        bloco.classList.remove('loading');

        if (resposta.status != 200 || json.dado == undefined) {
            return;
        }

        carregarGraficoDispositivo(json.dado, tipo);
    };
    buscarPorDispositivo('dispositivo');
    buscarPorDispositivo('navegador');
    buscarPorDispositivo('os');

    const carregarGraficoDispositivo = (data, tipo) => {
        let bloco, id, titulo;
        if (tipo == 'dispositivo') {
            bloco = graficoDispositivo;
            id = '#grafico_dispositivo';
            titulo = 'Acesso por dispositivo';
        } else if (tipo == 'navegador') {
            bloco = graficoNavegador;
            id = '#grafico_navegador';
            titulo = 'Acesso por navegador';
        } else if (tipo == 'os') {
            bloco = graficoOs;
            id = '#grafico_os';
            titulo = 'Acesso por Sistema';
        }
        const loading = bloco.querySelector('.loading_geral');
        if (loading) {
            loading.parentNode.removeChild(loading);
        }
        const Rosca = new Grafico(id, 'porcentagem');
        Rosca.titulo(titulo).dado(data.data).label(data.label).rosca();
    };

    /*
    |--------------------------------------------------------------------------
    | BUSCAR NOVA DATA
    |--------------------------------------------------------------------------
    */
    botaoBuscar.addEventListener('click', () => {
        buscarAcessoPorPagina();

        buscarMaisAcessado('usuario');
        buscarMaisAcessado('loja');
        buscarMaisAcessado('pagina');

        buscarPorDispositivo('dispositivo');
        buscarPorDispositivo('navegador');
        buscarPorDispositivo('os');

        dataInicial = inputDe.value;
        dataFinal = inputAte.value;

        botaoBuscar.classList.remove('show');
    });
});
