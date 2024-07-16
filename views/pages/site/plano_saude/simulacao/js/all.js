// @template "site"
// @system "Alerta"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @system "PassoPasso"
// @system "Calendario"

window.addEventListener('load', () => {
    const PassoSimular = new PassoPasso(
        '#bloco_simulacao_saude .bloco_conteudo .conteudo',
        '#bloco_simulacao_saude .bloco_progresso'
    );

    Calendario.init({
        input: '.bloco_input_data .input_data[data-mascara="00/00/0000"]',
    });

    const botaoRegiao = $$('.botao_escolher_regiao');
    const botaoAcomodacao = $$('.botao_escolher_acomodacao');
    const botaoPlano = $$('.botao_escolher_plano');
    const botaoVoltar = $$('.botao_voltar');
    const operadora = $('#input_operadora').valor();

    const botaoSimular = $('#botao_simulacao_continuar');
    const botaoContratar = $('#botao_contratar');
    const blocoDependenteLista = $('#bloco_lista_dependente');
    const dependentePadrao = $('#bloco_dependente_padrao');
    const inputTitular = $('#input_data_titular');
    const botaoDependente = $('#botao_adicionar_dependente');
    const blocoValorTotal = $('#bloco_valor_total');
    const blocoResultadoTitular = $('#bloco_resultado_titular_padrao');
    const blocoResultadoDependente = $('#bloco_resultado_dependente_padrao');
    const blocoResultadoLista = $('#bloco_valor_lista');
    const blocoEscolherPlano = $$('.bloco_escolher_plano');
    const botaoCnuFlorianopolisRegional = $$('.botao_acomodacao_enfermaria-50, .botao_acomodacao_enfermaria-30');
    const botaoCnuFlorianopolisNacional = $$('.botao_acomodacao_enfermaria, .botao_acomodacao_apartamento');

    let regiao = '';
    let plano = '';
    let acomodacao = '';

    botaoRegiao.evento('click', (e, item) => {
        botaoRegiao.classe('ativo', false);
        regiao = item.attr('data-regiao');
        PassoSimular.proximo(item);
        blocoEscolherPlano.sumir();
        $('.bloco_escolher_plano.' + regiao).aparecer();
    });

    botaoAcomodacao.evento('click', (e, item) => {
        botaoAcomodacao.classe('ativo', false);
        acomodacao = item.attr('data-acomodacao');
        PassoSimular.proximo(item);
    });

    botaoPlano.evento('click', (e, item) => {
        botaoPlano.classe('ativo', false);
        plano = item.attr('data-plano');
        PassoSimular.proximo(item);

        if (operadora == 'cnu_florianopolis') {
            setarTipoAcomodacaoCnuFlorianopolis();
        }
    });
    const setarTipoAcomodacaoCnuFlorianopolis = () => {
        botaoAcomodacao.sumir();
        if (plano == 'regional') {
            botaoCnuFlorianopolisRegional.aparecer();
            return;
        }
        botaoCnuFlorianopolisNacional.aparecer();
    };
    botaoVoltar.evento('click', (e, item) => {
        PassoSimular.anterior(item);
    });

    botaoDependente.evento('click', () => {
        const dependente = dependentePadrao.clonar();
        blocoDependenteLista.inicio(dependente);
        Calendario.init({
            input: '#bloco_lista_dependente .data_dependente input',
        });
        $('input', dependente).focus();
    });
    blocoDependenteLista.evento('click', e => {
        if (!e.target.classe('remover', '?') && !e.target.closest('.remover')) {
            return;
        }
        e.target.closest('.linha_dependente').remove();
    });

    botaoSimular.evento('click', async () => {
        blocoResultadoLista.html('');
        const dependente = $$('.linha_dependente', blocoDependenteLista);
        const titular = inputTitular.valor();

        if (vazio(titular) || !validarData(titular)) {
            Alerta.notificacao('Digite a data de nascimento do titular para continuar.', false);
            return;
        } else if (!(await validarDataDependente())) {
            Alerta.notificacao(
                'Digite a data de nascimento de todos os dependentes, caso não queira mais algum dependente, basta remover da simulação.',
                false
            );
            return;
        } else if (
            dependente.length == 0 &&
            !(await Alerta.confirmar('Continuar', 'Você vai fazer a simulação sem dependentes, deseja confirmar?', '!'))
        ) {
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/saude/realizar-simulacao',
            {
                operadora,
                titular,
                regiao,
                plano,
                acomodacao,
                dependentes: JSON.stringify($$('.linha_dependente input', blocoDependenteLista).valor()),
            },
            'Ocorreu um erro ao fazer sua simulação, por favor, tente novamente.'
        );
        Loading.hide();

        if (false === resposta) {
            return;
        }
        adicionarValorSimulacao(resposta.dado);
        PassoSimular.proximo(botaoSimular);
    });

    const adicionarValorSimulacao = dado => {
        botaoContratar.attr('href', LINK + '/saude/simulacao/' + dado.id);
        blocoValorTotal.texto('R$ ' + dado.valor_total);
        const titular = blocoResultadoTitular.clonar();
        adicionarValor(titular, dado.titular.data, dado.titular.valor);
        for (const item of dado.dependente) {
            const dependente = blocoResultadoDependente.clonar();
            adicionarValor(dependente, item.data, item.valor);
        }
    };
    const adicionarValor = (bloco, data, valor) => {
        $('.data', bloco).texto(data);
        $('.valor', bloco).texto('R$ ' + valor);
        blocoResultadoLista.final(bloco);
    };

    const validarDataDependente = () => {
        const lista = $$('.linha_dependente input', blocoDependenteLista);
        if (lista.length == 0) {
            return true;
        }
        for (const input of lista) {
            const data = input.valor();
            if (vazio(data) || !validarData(data)) {
                return false;
            }
        }
        return true;
    };
});
