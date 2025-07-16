// @template "painel"
// @system "Grafico"
// @system "Popup"
// @painel 'relatorio_empresas'

window.addEventListener('load', () => {
    const inputDe = document.querySelector('#input_relatorio_data_de');
    const inputAte = document.querySelector('#input_relatorio_data_ate');
    const botaoBuscar = document.querySelector('#botao_buscar_relatorio');

    Calendario.init({
        de: $('#input_relatorio_data_de'),
        ate: $('#input_relatorio_data_ate'),
    });

    const buscarGrafico = async () => {
        const de = inputDe.value;
        const ate = inputAte.value;
        const empresa = pegarValoresMarcadosEmpresa();
        const subempresa = pegarValoresMarcadosSubempresa();

        Loading.show();

        const resposta = await ajaxGet(LINK + `/relatorio/indicacoes`, { de, ate, empresa, subempresa }, undefined, {
            headers: {
                'Content-Type': 'application/json',
            },
        });
        Loading.hide();

        if (resposta.dado == undefined) {
            return;
        }
        carregarGraficoPorIndicacao(resposta.dado);
    };
    buscarGrafico();
    if (botaoBuscar) {
        botaoBuscar.addEventListener('click', () => {
            buscarGrafico();
        });
    }

    /*
    |--------------------------------------------------------------------------
    | GRAFICO POR STATUS
    |--------------------------------------------------------------------------
    */
    const graficoStatusTotal = document.querySelector('#grafico_status_total');
    const graficoStatusConcluido = document.querySelector('#grafico_status_concluido');
    const graficoStatusProspeccao = document.querySelector('#grafico_status_prospeccao');
    const graficoStatusCancelado = document.querySelector('#grafico_status_cancelado');
    const graficoStatusVinculo = document.querySelector('#grafico_status_vinculo');
    const carregarGraficoPorIndicacao = data => {
        graficoStatusTotal.querySelector('.numero').innerText = data.total.numero;
        graficoStatusConcluido.querySelector('.numero').innerText = data.concluido.numero;
        graficoStatusConcluido.querySelector('.porcentagem').innerText = data.concluido.porcentagem + '%';
        graficoStatusConcluido.querySelector('.barra span').style.width = data.concluido.porcentagem + '%';
        graficoStatusCancelado.querySelector('.numero').innerText = data.cancelado.numero;
        graficoStatusCancelado.querySelector('.porcentagem').innerText = data.cancelado.porcentagem + '%';
        graficoStatusCancelado.querySelector('.barra span').style.width = data.cancelado.porcentagem + '%';
        graficoStatusProspeccao.querySelector('.numero').innerText = data.prospeccao.numero;
        graficoStatusProspeccao.querySelector('.porcentagem').innerText = data.prospeccao.porcentagem + '%';
        graficoStatusProspeccao.querySelector('.barra span').style.width = data.prospeccao.porcentagem + '%';
        graficoStatusVinculo.querySelector('.numero').innerText = data.semVinculo.numero;
        graficoStatusVinculo.querySelector('.porcentagem').innerText = data.semVinculo.porcentagem + '%';
        graficoStatusVinculo.querySelector('.barra span').style.width = data.semVinculo.porcentagem + '%';
    };
});
