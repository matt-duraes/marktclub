// @template "painel"
// @system "Grafico"

window.addEventListener('load', () => {
    const LINK = document.querySelector('#LINK').value;
    const inputDe = document.querySelector('#input_relatorio_data_de');
    const inputAte = document.querySelector('#input_relatorio_data_ate');
    const textoData = document.querySelector('#bloco_relatorio_data');
    const botaoBuscar = document.querySelector('#botao_buscar_relatorio');
    const iconeCalendario = document.querySelector('#icone_calendario');
    let dataInicial = inputDe.value;
    let dataFinal = inputAte.value;

    const listaMes = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    const adicionarData = () => {
        const de = inputDe.value.split('/');
        const ate = inputAte.value.split('/');
        if (de.length == 0 || ate.length == 0) {
            textoData.innerText = 'Últimos 6 meses';
        } else if (de.length != 3 || ate.length != 3) {
            textoData.innerText = '';
            return;
        }

        textoData.innerText = `${de[0]} ${listaMes[parseInt(de[1]) - 1]} ${de[2]} até ${ate[0]} ${
            listaMes[parseInt(ate[1]) - 1]
        } ${ate[2]}`;

        if (dataInicial != inputDe.value || dataFinal != inputAte.value) {
            botaoBuscar.classList.add('show');
            iconeCalendario.classList.remove('show');
            return;
        }
        botaoBuscar.classList.remove('show');
        iconeCalendario.classList.add('show');
    };
    Calendario.init({
        de: 'input_relatorio_data_de',
        ate: 'input_relatorio_data_ate',
        callback: adicionarData,
    });

    /*
    |--------------------------------------------------------------------------
    | BUSCAR NOVA DATA
    |--------------------------------------------------------------------------
    */
    botaoBuscar.addEventListener('click', () => {
        dataInicial = inputDe.value;
        dataFinal = inputAte.value;

        botaoBuscar.classList.remove('show');
        iconeCalendario.classList.add('show');
    });
});
