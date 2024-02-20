// @template "painel"
// @system "Grafico"
// @system "Popup"
// @painel 'relatorio_empresas'

window.addEventListener('load', () => {
    const inputDe = document.querySelector('#input_relatorio_data_de');
    const inputAte = document.querySelector('#input_relatorio_data_ate');
    const botaoBuscar = document.getElementById('botao_buscar_relatorio');
    const graficoMes = document.getElementById('grafico_mes');
    const graficoLojaValor = document.querySelector('#lista_loja_valor .fw_grafico_bloco_lista');
    const graficoLojaTicket = document.querySelector('#lista_loja_tiket .fw_grafico_bloco_lista');

    /*
    |--------------------------------------------------------------------------
    | BUSCAR NOVA DATA
    |--------------------------------------------------------------------------
    */
    botaoBuscar.addEventListener('click', () => {
        buscarRelatorio();
    });
    const buscarRelatorio = async () => {
        graficoMes.classList.add('loading');
        graficoLojaValor.classList.add('loading');
        graficoLojaTicket.classList.add('loading');

        const valoresEmpresa = pegarValoresMarcadosEmpresa();
        const valoresParceiro = pegarValoresMarcadosParceiro();

        const de = inputDe.value;
        const ate = inputAte.value;
        const empresa = valoresEmpresa;
        const parceiro = valoresParceiro;

        const resposta = await fetch(LINK + `/relatorio/loja-venda-buscar?de=${de}&ate=${ate}&empresa=${empresa}&parceiro=${parceiro}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        });

        graficoMes.classList.remove('loading');
        graficoLojaValor.classList.remove('loading');
        graficoLojaTicket.classList.remove('loading');

        const json = await respostaJson(resposta, 'Erro ao buscar relatório, por favor, tente novamente.');
        if (false === json) {
            return;
        }

        carregarAcessoPorMes(json.dado.mes);
        carregarLista(json.dado.venda, graficoLojaValor, 'loja');
        carregarLista(json.dado.ticket, graficoLojaTicket, 'loja');
    };
    buscarRelatorio();

    const carregarAcessoPorMes = data => {
        const loading = graficoMes.querySelector('.loading_geral');
        if (loading) {
            loading.parentNode.removeChild(loading);
        }

        const Linha = new Grafico('#grafico_mes');
        Linha.titulo('Dados por mês').dado(data.data).label(data.label).linha();
    };

    const carregarLista = (data, bloco, local) => {
        let html = `<div class="scroll">`;
        data.forEach(item => {
            html += `
                <div class="linha">
                    <div class="item">${item[local]}</div>
                    <div class="porcentagem"><span style="width: ${item.porcentagem}%"></span></div>
                    <div class="valor"><span>(${item.porcentagem}%)</span>R$ ${item.total}</div>
                    ${item.numero_transacao
                        ? `
                            <hr class="divisor"/>
                            <div class="transacao">${item.numero_transacao}</div>
                        ` : ''
                    }
                </div>
            `
        });
        html += `</div>`;
        bloco.innerHTML = html;
    };
});
