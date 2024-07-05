let sprintStatus = '';
let idSprint;
let demandaSprintId;
let demandaSprintAcao;

const blocoBotaoSprint = $('#bloco_botao_sprint');
const EsqueletoBotaoSprint = new Esqueleto(blocoBotaoSprint, '.esqueleto', false);

const blocoCriarSprint = $$('.bloco_criar_sprint');
const blocoSprintExiste = $$('.bloco_sprint_existe');

// BOTÃO TOPO
const botaoCriarSprint = $('#botao_criar_sprint');
const botaoFecharTodos = $('#botao_fechar_todo');
const botaoAbrirTodos = $('#botao_abrir_todo');
const botaoNaSprint = $('#botao_na_sprint');
const botaoForaSprint = $('#botao_fora_sprint');
const botaoSprintSalvar = $('#botao_sprint_salvar_salvar');

// INPUT
const inputSprintTitulo = $('#input_sprint_titulo');
const inputSprintDataInicio = $('#input_sprint_data_inicio');
const inputSprintDataFinal = $('#input_sprint_data_final');

const popupSprintSalvar = new Popup('Salvar sprint', 'bloco_sprint_nova', false, false);
botaoCriarSprint.evento('click', () => {
    popupSprintSalvar.abrir();
});

botaoAbrirTodos.evento('click', () => {
    $$('#bloco_app_lista_detalhe .linha .lista').classe('aberto', true);
});
botaoFecharTodos.evento('click', () => {
    $$('#bloco_app_lista_detalhe .linha .lista').classe('aberto', false);
});
botaoNaSprint.evento('click', () => {
    botaoForaSprint.classe('hover', false);
    botaoNaSprint.classe('hover');
    if (botaoNaSprint.classe('hover', '?')) {
        $$('#bloco_app_lista_detalhe .linha:not(.na_sprint)').classe('display_none', true);
        $$('#bloco_app_lista_detalhe .linha.na_sprint').classe('display_none', false);
        return;
    }
    $$('#bloco_app_lista_detalhe .linha').classe('display_none', false);
});
botaoForaSprint.evento('click', () => {
    botaoNaSprint.classe('hover', false);
    botaoForaSprint.classe('hover');
    if (botaoForaSprint.classe('hover', '?')) {
        $$('#bloco_app_lista_detalhe .linha:not(.na_sprint)').classe('display_none', false);
        $$('#bloco_app_lista_detalhe .linha.na_sprint').classe('display_none', true);
        return;
    }
    $$('#bloco_app_lista_detalhe .linha').classe('display_none', false);
});

const buscarSprintAtiva = async () => {
    if (USUARIO_GERENTE != 'sim') {
        blocoSprintExiste.sumir();
        blocoSprintExiste.sumir();
        return;
    }

    EsqueletoBotaoSprint.show();

    const blocoLista = $('#bloco_app_lista_detalhe');
    const resposta = await ajaxPost(LINK + '/demanda-sprint/ativa', {}, '');
    EsqueletoBotaoSprint.hide();
    if (false === resposta) {
        blocoSprintExiste.sumir();
        return;
    }
    idSprint = resposta.dado.id;
    sprintStatus = resposta.dado.status;
    blocoCriarSprint.sumir();
    blocoLista.classe('sprint_ativa', true);
    for (const id of resposta.dado.demanda) {
        const linha = $('#id_demanda_' + id);
        if (!linha) {
            continue;
        }
        linha.classe('na_sprint');
    }
};

/*
|--------------------------------------------------------------------------
| SALVAR NOVA SPRINT
|--------------------------------------------------------------------------
*/
botaoSprintSalvar.evento('click', async () => {
    const titulo = inputSprintTitulo.valor();
    const dataInicio = inputSprintDataInicio.valor();
    const dataFinal = inputSprintDataFinal.valor();
    if (vazio(titulo)) {
        Alerta.notificacao('Digite um título para continuar.', false);
        return;
    } else if (vazio(dataInicio)) {
        Alerta.notificacao('Digite a data de início para continuar.', false);
        return;
    } else if (vazio(dataFinal)) {
        Alerta.notificacao('Digite a data de início para continuar.', false);
        return;
    }

    Loading.show();
    const resposta = await ajaxPost(
        LINK + '/demanda-sprint/salvar',
        {
            titulo,
            /* eslint-disable */
            data_inicio: dataInicio,
            data_final: dataFinal,
            /* eslint-enable */
        },
        'Erro ao salvar sprint, por favor, tente novamente.'
    );
    Loading.hide();

    if (false === resposta) {
        return;
    }
    idSprint = resposta.dado.id;
    sprintStatus = resposta.dado.status;
    popupSprintSalvar.fechar();
    conteudoLista.classe('sprint_ativa');
    blocoCriarSprint.sumir();
    blocoSprintExiste.aparecer();
});

conteudoLista.evento('click', e => {
    const linha = e.target.closest('.linha');
    if (e.target.classe('botao_sprint_add', '?') || e.target.closest('.botao_sprint_add')) {
        acaoDemandaSprint(linha, $('.botao_sprint_add', linha), 'add');
    } else if (e.target.classe('botao_sprint_remover', '?') || e.target.closest('.botao_sprint_remover')) {
        acaoDemandaSprint(linha, $('.botao_sprint_remover', linha), 'remover');
    }
});

const acaoDemandaSprint = async (bloco, botao, acao) => {
    if (botao.classe('aguarde', '?')) {
        return;
    }

    const id = bloco.attr('data-id');
    if (sprintStatus != 'nova') {
        demandaSprintId = id;
        demandaSprintAcao = acao;
        return;
    }

    botao.classe('aguarde', true);
    Loading.botao(botao).show();
    const resposta = await fazerRequestRequest(acao, id, '');
    botao.classe('aguarde', false);
    Loading.botao(botao).hide();

    if (false === resposta) {
        return;
    }
    bloco.classe('na_sprint', acao == 'add');
};
const fazerRequestRequest = (acao, demanda, texto) => {
    return ajaxPost(
        LINK + '/demanda-sprint/demanda-' + acao,
        {
            demanda,
            sprint: idSprint,
            texto,
        },
        'Erro na demanda, por favor, tente novamente.'
    );
};
