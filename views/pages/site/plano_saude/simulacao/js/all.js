// @template "site"
// @system "Alerta"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @system "Calendario"

window.addEventListener('load', () => {
    const plano = $('#input_plano_saude').valor();

    Calendario.init({
        input: $('#input_data_titular'),
    });

    const botaoSimular = $('#botao_fazer_simulacao');
    const botaoRefazerSimulacao = $('#botao_refazer_simulacao');

    const inputDataTitular = $('#input_data_titular');

    const blocoSimulacao = $('#bloco_simulacao');
    const blocoResultado = $('#bloco_resultado');

    const botaoDependente = $('#botao_adicionar_dependente');
    const blocoDependenteLista = $('#bloco_lista_dependente');
    const blocoDependentePadrao = $('#bloco_dependente_padrao');
    const blocoSemDependente = $('#bloco_sem_dependente');

    const blocoPlanoLista = $('#bloco_plano_lista');
    const blocoPlanoPadrao = $('#bloco_plano_padrao');
    const blocoPlanoValorPadrao = $('#bloco_plano_valor_padrao');
    const blocoPlanoItemPadrao = $('#bloco_plano_item_padrao');

    botaoDependente.evento('click', () => {
        blocoSemDependente.sumir();
        const dependente = blocoDependentePadrao.clonar();
        blocoDependenteLista.final(dependente);
        Calendario.init({
            input: $('.bloco_input input', dependente),
        });
        $('input', dependente).focus();
        fwMascaraLoading(dependente);
    });

    blocoDependenteLista.evento('click', e => {
        if (!e.target.classe('remover', '?') && !e.target.closest('.remover')) {
            return;
        }
        e.target.closest('.linha_dependente').remove();
        if ($$('.linha_dependente', blocoDependenteLista).length === 0) {
            blocoSemDependente.aparecer();
        }
    });

    botaoSimular.evento('click', async () => {
        // const dependenteLista = $$('.linha_dependente input', blocoDependenteLista);
        // const titular = inputDataTitular.valor();
        // if (vazio(titular)) {
        //     Alerta.mensagem(
        //         'Campo obrigatório',
        //         'Você precisa enviar a data de nascimento do titular para continuar.',
        //         false
        //     );
        //     return;
        // } else if (
        //     dependenteLista.length === 0 &&
        //     !(await Alerta.confirmar('Dependente', 'Não foi cadastrado nenhum dependente, deseja continuar?', '!'))
        // ) {
        //     return;
        // }

        // const dependenteValor = [];
        // let dependenteVazio = 0;
        // for (const dependente of dependenteLista) {
        //     const data = dependente.valor();
        //     if (vazio(data)) {
        //         dependenteVazio++;
        //         continue;
        //     }
        //     dependenteValor.push(data);
        // }
        // const dependenteVazioMensagem =
        //     dependenteVazio > 1
        //         ? `Existem ${dependenteVazio} dependentes sem a data de nascimento, deseja continuar?`
        //         : 'Existe 1 dependente sem a data de nascimento, deseja continuar?';
        // if (dependenteVazio && !(await Alerta.confirmar('Dependente', dependenteVazioMensagem, '!'))) {
        //     return;
        // }

        const titular = '27/07/1987';
        const dependenteValor = ['05/07/1982', '17/09/2015', '21/12/2018'];

        Loading.show();
        const resposta = await ajaxPost(LINK + '/saude/simulacao', {
            dependente: dependenteValor,
            titular: titular,
            plano,
        });

        Loading.hide();
        if (false === resposta) {
            return;
        }
        blocoSimulacao.sumir();
        blocoResultado.aparecer();
        adicionarPlano(resposta.dado);
    });

    const pegarTamanhoBloco = () => {
        const larguraBloco =
            blocoResultado.getBoundingClientRect().width -
            parseInt(blocoResultado.css('padding-left')) -
            parseInt(blocoResultado.css('padding-right'));

        if (larguraBloco < 350) {
            return larguraBloco - 40;
        }
        const tamanhoAtual = 350;
        const quantidade = Math.floor(larguraBloco / tamanhoAtual) * tamanhoAtual;
        const resto = larguraBloco - quantidade;
        ppe(resto);
        return tamanhoAtual;
    };

    const adicionarPlano = resposta => {
        const tamanho = pegarTamanhoBloco();
        for (const item of resposta) {
            const clonar = blocoPlanoPadrao.clonar();
            clonar.css('width', `${tamanho}px`);
            $('header h2', clonar).texto(item.titulo);
            const total = montarValor(item.valor_total);
            $('.dinheiro .valor', clonar).texto(total[0]);
            $('.dinheiro .centavo', clonar).texto(total[1]);
            $('.botao', clonar).attr('data-id', item.plano);

            const valorLista = item.valor_lista;
            const blocoValor = $('table.data', clonar);
            for (const valor in valorLista) {
                const valorClone = blocoPlanoValorPadrao.clonar();
                $('.indice', valorClone).texto(valor);
                $('.valor', valorClone).texto(valorLista[valor]);
                blocoValor.final(valorClone);
            }

            const valorDetalhe = item.item;
            const blocoDetalhe = $('.detalhe', clonar);
            for (const valor in valorDetalhe) {
                const detalheClone = blocoPlanoItemPadrao.clonar();
                $('.indice', detalheClone).texto(valor);
                $('.valor', detalheClone).texto(valorDetalhe[valor].toString().replace('.', ','));
                blocoDetalhe.final(detalheClone);
            }
            blocoPlanoLista.final(clonar);
        }
        blocoPlanoLista.classe('tamanho_' + resposta.length);
    };

    const montarValor = valor => {
        const total = valor.toString().split('.');
        let centavo = total[1] || '';

        if (centavo.length === 0) {
            centavo = '00';
        } else if (centavo.length === 1) {
            centavo = centavo + '0';
        } else if (centavo.length > 2) {
            centavo = centavo.slice(0, 2);
        }
        return [total[0], centavo];
    };

    botaoRefazerSimulacao.evento('click', () => {
        blocoSimulacao.aparecer();
        blocoResultado.sumir();
    });
});
