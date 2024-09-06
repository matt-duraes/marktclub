// @template "painel"

window.addEventListener('load', () => {
    const inputTipo = $('#input_tipo');
    const inputApiStatus = $('#input_api_status');
    const inputApiUri = $('#input_api_uri');
    const blocoLista = $('#bloco_view_conteudo');

    const blocoApiSim = $$('.bloco_api_sim');
    const blocoTitulo = $('.bloco_titulo');
    const blocoTexto = $('.bloco_texto');
    const blocoLink = $('.bloco_link');
    const blocoTarget = $('.bloco_target');

    inputTipo.evento('formChange', () => {
        limparObrigatorio();
        const valor = inputTipo.valor();
        if (valor == 'titulo_texto') {
            blocoTitulo.aparecer();
            blocoTexto.aparecer();
        } else if (valor == 'titulo') {
            blocoTitulo.aparecer();
        } else if (valor == 'botao' || valor == 'botao_destaque') {
            blocoTitulo.aparecer();
            blocoLink.aparecer();
            blocoTarget.aparecer();
        }
    });

    inputApiStatus.evento('change', () => {
        blocoApiSim.classe('display_none', !inputApiStatus.checked);
        inputApiUri.focus();
    });

    blocoLista.evento('click', e => {
        if (e.target.classe('status', '?') || e.target.closest('.status')) {
            const bloco = e.target.classe('status', '?') ? e.target : e.target.closest('.status');
            mudarStatus(bloco);
        }
    });

    const limparObrigatorio = () => {
        blocoTitulo.sumir();
        blocoTexto.sumir();
        blocoLink.sumir();
        blocoTarget.sumir();
        blocoApiSim.sumir();
    };

    const mudarStatus = bloco => {
        const id = bloco.closest('article').attr('data-id');
        bloco.classe('ativo');
    };
});
