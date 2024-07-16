let sprintStatus = '';
let idSprint;
let demandaSprintId;
let demandaSprintAcao;

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

const blocoSprintAtivaTitulo = $('#bloco_sprint_ativa_titulo');
const blocoSprintAtivaTexto = $('#bloco_sprint_ativa_texto');
const inputSprintAtivaMotivo = $('#input_sprint_ativa_texto');
const botaoSprintAtivaAcao = $('#botao_sprint_ativa_acao');

const EsqueletoBotaoSprint = new Esqueleto(blocoBotaoSprint, '.esqueleto', false);
const PopupSprintAtiva = new Popup('Adicionar', 'bloco_popup_sprint_ativa', false, false);
const popupSprintSalvar = new Popup('Salvar sprint', 'bloco_sprint_nova', false, false);

const buscarSprintAtiva = async existe => {
    if (USUARIO_GERENTE != 'sim') {
        blocoSprintExiste.sumir();
        return;
    }

    if (existe) {
        EsqueletoBotaoSprint.show();
    }

    const resposta = await ajaxPost(LINK + '/demanda-sprint/ativa', {}, '');
    if (existe) {
        EsqueletoBotaoSprint.hide();
    }
    if (false === resposta) {
        blocoSprintExiste.sumir();
        return;
    }
    idSprint = resposta.dado.id;
    sprintStatus = resposta.dado.status;
    blocoSprintLista.classe('sprint_ativa', true);
    blocoCriarSprint.sumir();
    if (existe) {
        blocoSprintExiste.aparecer();
        for (const id of resposta.dado.demanda) {
            const linha = $('#id_demanda_' + id);
            if (!linha) {
                continue;
            }
            linha.classe('na_sprint');
        }
    }
};

const fazerRequestAdicionarRemoverDemandaSprint = (acao, demanda, sprint, texto) => {
    return ajaxPost(
        LINK + '/demanda-sprint/demanda-' + acao,
        {
            demanda,
            sprint,
            texto,
        },
        'Erro na demanda, por favor, tente novamente.'
    );
};

const carregarSprintLista = () => {
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
        blocoSprintLista.classe('sprint_ativa');
        blocoCriarSprint.sumir();
        blocoSprintExiste.aparecer();
    });

    blocoSprintLista.evento('click', e => {
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
            blocoSprintAtivaTitulo.texto(acao == 'add' ? 'Adicionar demanda' : 'Remover demanda');
            blocoSprintAtivaTexto.texto(
                acao == 'add'
                    ? 'Escreva de forma detalhada o porque essa demanda está sendo adicionado a sprint em andamento.'
                    : 'Escreva de forma detalhada o porque essa demanda está sendo removida de uma sprint em andamento.'
            );
            inputSprintAtivaMotivo.valor('');
            PopupSprintAtiva.abrir();
            return;
        }

        botao.classe('aguarde', true);
        Loading.botao(botao).show();
        const resposta = await fazerRequestAdicionarRemoverDemandaSprint(acao, id, idSprint, '');
        botao.classe('aguarde', false);
        Loading.botao(botao).hide();

        if (false === resposta) {
            return;
        }
        bloco.classe('na_sprint', acao == 'add');
    };

    botaoSprintAtivaAcao.evento('click', async () => {
        const texto = inputSprintAtivaMotivo.valor();
        if (vazio(texto)) {
            Alerta.notificacao('Preencha o motivo para continuar.', false);
            return;
        }
        const resposta = await fazerRequestAdicionarRemoverDemandaSprint(
            demandaSprintAcao,
            demandaSprintId,
            idSprint,
            texto
        );
        if (false === resposta) {
            return;
        }
        PopupSprintAtiva.fechar();
        const bloco = $('#id_demanda_' + demandaSprintId);
        if (bloco) {
            bloco.classe('na_sprint', demandaSprintAcao == 'add');
        }
    });
};
if (blocoSprintLista) {
    carregarSprintLista();
}
