// @template "painel"
// @system "Grafico"

window.addEventListener('load', () => {
    const LINK = document.querySelector('#LINK').value;

    const blocoStatus = document.querySelector('#bloco_status');
    const blocoStatusScroll = document.querySelector('#bloco_status .scroll');
    let statusAtual = 1;
    blocoStatus.addEventListener('swiped-left', e => {
        if (statusAtual == 4) {
            return;
        }
        blocoStatusScroll.classList.remove('bloco_' + statusAtual);
        statusAtual++;
        blocoStatusScroll.classList.add('bloco_' + statusAtual);
    });
    blocoStatus.addEventListener('swiped-right', e => {
        if (statusAtual == 1) {
            return;
        }
        blocoStatusScroll.classList.remove('bloco_' + statusAtual);
        statusAtual--;
        blocoStatusScroll.classList.add('bloco_' + statusAtual);
    });

    /*
    |--------------------------------------------------------------------------
    | CARREGAR GRÁFICO DE ESTADO
    |--------------------------------------------------------------------------
    */
    const graficoStatusTotal = document.querySelector('#grafico_status_total');
    const graficoStatusAtivo = document.querySelector('#grafico_status_ativo');
    const graficoStatusInativo = document.querySelector('#grafico_status_inativo');
    const graficoStatusBloqueado = document.querySelector('#grafico_status_bloqueado');

    const buscarGraficoPorStatus = async () => {
        const resposta = await fetch(LINK + `/relatorio/usuario-status`, {
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

        if (resposta.status != 200 || json.dado == undefined) {
            return;
        }

        carregarGraficoPorStatus(json.dado);
    };
    buscarGraficoPorStatus();
    const carregarGraficoPorStatus = data => {
        graficoStatusTotal.querySelector('.numero').innerText = data.total.numero;
        graficoStatusAtivo.querySelector('.numero').innerText = data.ativo.numero;
        graficoStatusAtivo.querySelector('.porcentagem').innerText = data.ativo.porcentagem + '%';
        graficoStatusAtivo.querySelector('.barra span').style.width = data.ativo.porcentagem + '%';
        graficoStatusInativo.querySelector('.numero').innerText = data.inativo.numero;
        graficoStatusInativo.querySelector('.porcentagem').innerText = data.inativo.porcentagem + '%';
        graficoStatusInativo.querySelector('.barra span').style.width = data.inativo.porcentagem + '%';
        graficoStatusBloqueado.querySelector('.numero').innerText = data.bloqueado.numero;
        graficoStatusBloqueado.querySelector('.porcentagem').innerText = data.bloqueado.porcentagem + '%';
        graficoStatusBloqueado.querySelector('.barra span').style.width = data.bloqueado.porcentagem + '%';
    };

    /*
    |--------------------------------------------------------------------------
    | CARREGAR GRÁFICO DE ESTADO
    |--------------------------------------------------------------------------
    */
    const graficoEstado = document.querySelector('#grafico_estado');
    const buscarGraficoPorEstado = async () => {
        graficoEstado.classList.add('loading');
        const resposta = await fetch(LINK + `/relatorio/usuario-estado`, {
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

        graficoEstado.classList.remove('loading');

        if (resposta.status != 200 || json.dado == undefined) {
            return;
        }

        carregarGraficoPorEstado(json.dado);
    };
    buscarGraficoPorEstado();
    const carregarGraficoPorEstado = data => {
        const loading = graficoEstado.querySelector('.loading_geral');
        if (loading) {
            loading.parentNode.removeChild(loading);
        }

        const Barra = new Grafico('#grafico_estado');
        Barra.titulo('Usuário por estado').header(data.header).dado(data.data).label(data.label).barra();
    };

    /*
    |--------------------------------------------------------------------------
    | CARREGAR GRÁFICO DE ROSCA
    |--------------------------------------------------------------------------
    */
    const buscarGraficoRosca = async (titulo, id, uri) => {
        const bloco = document.querySelector(id);
        bloco.classList.add('loading');

        const resposta = await fetch(LINK + uri, {
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

        bloco.classList.remove('loading');
        if (resposta.status != 200 || json.dado == undefined) {
            return;
        }

        carregarGraficoRosca(titulo, id, json.dado);
    };
    const carregarGraficoRosca = (titulo, id, data) => {
        const loading = document.querySelector(id + ' .loading_geral');
        if (loading) {
            loading.parentNode.removeChild(loading);
        }

        const Barra = new Grafico(id, 'porcentagem');
        Barra.titulo(titulo).dado(data.data).label(data.label).rosca();
    };

    buscarGraficoRosca('Gênero', '#grafico_genero', '/relatorio/usuario-genero');
    buscarGraficoRosca('Faixa etária', '#grafico_faixa_etaria', '/relatorio/usuario-faixa-etaria');
    buscarGraficoRosca('Situação', '#grafico_situacao', '/relatorio/usuario-situacao');
    buscarGraficoRosca('Estado Civil', '#grafico_estado_civil', '/relatorio/usuario-estado-civil');
    buscarGraficoRosca('Sem atualizar dados', '#grafico_atualizar_dado', '/relatorio/usuario-atualizar-dado');
});
