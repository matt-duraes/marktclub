let sprintStatus = '';
const buscarSprintAtiva = async () => {
    const gerente = $('#USUARIO_GERENTE').valor();
    if (gerente != 'sim') {
        return;
    }

    const blocoLista = $('#bloco_app_lista_detalhe');
    const resposta = await ajaxPost(LINK + '/demanda-sprint/ativa', {}, '');
    if (false === resposta) {
        return;
    }
    blocoLista.classe('sprint_ativa', true);
    sprintStatus = resposta.dado.status;
    for (const id of resposta.dado.demanda) {
        const linha = $('#id_demanda_' + id);
        if (!linha) {
            continue;
        }
        linha.classe('na_sprint');
    }
};

const blocoBotaoSprint = $('#bloco_botao_sprint');
const EsqueletoBotaoSprint = new Esqueleto(blocoBotaoSprint, '.esqueleto', false);
EsqueletoBotaoSprint.show();
