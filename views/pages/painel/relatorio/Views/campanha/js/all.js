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

        Loading.show();

        const resposta = await ajaxGet(LINK + `/relatorio/campanha-vouchers`, { de, ate, empresa }, undefined, {
            headers: {
                'Content-Type': 'application/json',
            },
        });
        Loading.hide();

        if (resposta.dado == undefined) {
            return;
        }
        carregarGraficoPorCampanha(resposta.dado);
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
    const graficoStatusResgatado = document.querySelector('#grafico_status_resgatado');
    const graficoStatusDisponivel = document.querySelector('#grafico_status_disponivel');
    const graficoStatusVencido = document.querySelector('#grafico_status_vencido');
    const carregarGraficoPorCampanha = data => {
        graficoStatusTotal.querySelector('.numero').innerText = data.total.numero;
        graficoStatusResgatado.querySelector('.numero').innerText = data.resgatado.numero;
        graficoStatusResgatado.querySelector('.porcentagem').innerText = data.resgatado.porcentagem + '%';
        graficoStatusResgatado.querySelector('.barra span').style.width = data.resgatado.porcentagem + '%';
        graficoStatusVencido.querySelector('.numero').innerText = data.vencido.numero;
        graficoStatusVencido.querySelector('.porcentagem').innerText = data.vencido.porcentagem + '%';
        graficoStatusVencido.querySelector('.barra span').style.width = data.vencido.porcentagem + '%';
        graficoStatusDisponivel.querySelector('.numero').innerText = data.disponivel.numero;
        graficoStatusDisponivel.querySelector('.porcentagem').innerText = data.disponivel.porcentagem + '%';
        graficoStatusDisponivel.querySelector('.barra span').style.width = data.disponivel.porcentagem + '%';
    };
});
