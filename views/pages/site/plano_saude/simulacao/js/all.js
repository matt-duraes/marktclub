// @template "site"
// @system "Alerta"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @system "Calendario"

window.addEventListener('load', () => {
    const convenio = $('#input_plano_saude').valor();

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

    let simulacaoLista;
    botaoSimular.evento('click', async () => {
        const dependenteLista = $$('.linha_dependente input', blocoDependenteLista);
        const titular = inputDataTitular.valor();
        if (vazio(titular)) {
            Alerta.mensagem(
                'Campo obrigatório',
                'Você precisa enviar a data de nascimento do titular para continuar.',
                false
            );
            return;
        } else if (
            dependenteLista.length === 0 &&
            !(await Alerta.confirmar('Dependente', 'Não foi cadastrado nenhum dependente, deseja continuar?', '!'))
        ) {
            return;
        }

        const dependenteValor = [];
        let dependenteVazio = 0;
        for (const dependente of dependenteLista) {
            const data = dependente.valor();
            if (vazio(data)) {
                dependenteVazio++;
                continue;
            }
            dependenteValor.push(data);
        }
        const dependenteVazioMensagem =
            dependenteVazio > 1
                ? `Existem ${dependenteVazio} dependentes sem a data de nascimento, deseja continuar?`
                : 'Existe 1 dependente sem a data de nascimento, deseja continuar?';
        if (dependenteVazio && !(await Alerta.confirmar('Dependente', dependenteVazioMensagem, '!'))) {
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(LINK + '/saude/simulacao', {
            dependente: dependenteValor,
            titular: titular,
            plano: convenio,
        });

        Loading.hide();
        if (false === resposta) {
            return;
        }
        blocoSimulacao.sumir();
        blocoResultado.aparecer();
        adicionarPlano(resposta.dado);
    });

    const pegarTamanhoBloco = tamanho => {
        const larguraBloco =
            blocoResultado.getBoundingClientRect().width -
            parseInt(blocoResultado.css('padding-left')) -
            parseInt(blocoResultado.css('padding-right'));

        if (larguraBloco <= 300) {
            return 300;
        } else if (larguraBloco < 350) {
            return larguraBloco - 30;
        } else if (larguraBloco < 500) {
            return larguraBloco - 60;
        }
        const tamanhoAtual = tamanho === undefined ? 350 : tamanho;
        const quantidade = Math.floor(larguraBloco / tamanhoAtual);
        const larguraReal = quantidade * tamanhoAtual;
        const resto = larguraBloco - larguraReal;
        const restoCondicao = 60 + quantidade * 20;
        if (resto < restoCondicao) {
            return pegarTamanhoBloco(tamanhoAtual - 10);
        }
        return tamanhoAtual;
    };

    const adicionarPlano = resposta => {
        const tamanho = pegarTamanhoBloco();
        simulacaoLista = {};
        for (const item of resposta) {
            simulacaoLista[item.plano] = item;
            const clonar = blocoPlanoPadrao.clonar();
            clonar.attr('data-plano', item.plano);
            clonar.css('width', `${tamanho}px`);
            $('header h2', clonar).texto(item.titulo);
            const total = montarValor(item.valor_total);
            $('.dinheiro .valor', clonar).texto(total[0]);
            $('.dinheiro .centavo', clonar).texto(total[1]);

            const blocoValor = $('.valor_lista', clonar);
            for (const valor of item.valor_lista) {
                const valorClone = blocoPlanoValorPadrao.clonar();
                $('.indice .nome', valorClone).texto(valor.nome);
                $('.indice .data', valorClone).texto(`(${valor.data})`);
                $('.valor', valorClone).texto(`R$ ${valor.valor}`);
                blocoValor.final(valorClone);
            }

            $('.botao', clonar).classe('botao_escolher_plano', true);

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
        blocoPlanoLista.css('width', tamanho * resposta.length + 20 * (resposta.length - 1) + 'px');
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
    blocoPlanoLista.evento('click', async e => {
        const clicado = e.target.classe('botao_escolher_plano', '?') || e.target.closest('.botao_escolher_plano');
        if (!clicado) {
            return;
        }
        e.preventDefault();

        const plano = e.target.closest('.plano').attr('data-plano');
        if (!plano in simulacaoLista) {
            Alerta.notificacao('Erro ao salvar simulação, por favor, tente novamente.', false);
            return;
        }
        const item = simulacaoLista[plano];
        const dado = JSON.stringify({
            plano: item.plano,
            valor: item.valor_lista,
            total: item.valor_total,
            item: item.item,
        });

        const novaJanela = item.link !== '' ? window.open('', '_blank') : false;

        Loading.show();
        const resposta = await ajaxPost(LINK + '/saude/simulacao-escolhida', {
            plano: convenio,
            dado,
        });

        if (false === resposta) {
            Loading.hide();
            if (novaJanela) {
                novaJanela.close();
            }
            return;
        } else if (novaJanela) {
            novaJanela.location.href = item.link;
            Loading.hide();
            return;
        }

        window.location.assign(LINK + '/saude/contratar/' + resposta.dado.id);
    });
});
