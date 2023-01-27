// @template "painel"
// @system "Grafico"

window.addEventListener('load', () => {
    const LINK = document.querySelector('#LINK').value;

    const buscarGrafico = async () => {
        const resposta = await fetch(LINK + '/relatorio/usuario-buscar', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });
        const json = await respostaJson(
            resposta,
            'Ocorreu um erro ao buscar gráficos, por favor, recarregue a página e tente novamente.'
        );
        if (false === json) {
            return;
        }
        console.log(json);
        carregarGraficoPorStatus(json.dado.status);
        carregarGraficoPorEstado(json.dado.estado);
        carregarGraficoRosca('Gênero', '#grafico_genero', json.dado.genero);
        carregarGraficoRosca('Faixa etária', '#grafico_faixa_etaria', json.dado.faixa_etaria);
        carregarGraficoRosca('Situação', '#grafico_situacao', json.dado.situacao);
        carregarGraficoRosca('Estado Civil', '#grafico_estado_civil', json.dado.estado_civil);
        carregarGraficoRosca('Sem atualizar dados', '#grafico_atualizar_dado', json.dado.atualizar_dado);
    };
    buscarGrafico();

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
    | GRAFICO POR STATUS
    |--------------------------------------------------------------------------
    */
    const graficoStatusTotal = document.querySelector('#grafico_status_total');
    const graficoStatusAtivo = document.querySelector('#grafico_status_ativo');
    const graficoStatusInativo = document.querySelector('#grafico_status_inativo');
    const graficoStatusBloqueado = document.querySelector('#grafico_status_bloqueado');
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
    const carregarGraficoRosca = (titulo, id, data) => {
        const loading = document.querySelector(id + ' .loading_geral');
        if (loading) {
            loading.parentNode.removeChild(loading);
        }

        const Barra = new Grafico(id, 'porcentagem');
        Barra.titulo(titulo).dado(data.data).label(data.label).rosca();
    };
});
