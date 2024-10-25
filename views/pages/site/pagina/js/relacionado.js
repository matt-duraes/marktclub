const comRelacionadoParceiro = $('.parceiro_loja_padrao');
if (comRelacionadoParceiro) {
    comRelacionadoParceiro.classe('parceiro_loja_padrao', false);
}

const comRelacionado = async bloco => {
    const conteudo = $('.bloco_parceiro', bloco);
    const id = bloco.attr('data-id');

    const esqueletoLista = $$('.parceiro_esqueleto', bloco);
    for (const esquele of esqueletoLista) {
        const EsqueletoItem = new Esqueleto(esquele, '.esqueleto');
        EsqueletoItem.show();
    }

    const resposta = await ajaxPost(
        LINK + '/componente',
        {
            id,
            url: paginaUrl,
            campo: ['id', 'titulo', 'url', 'desconto', 'imagem_logo', 'endereco_estado'],
        },
        ''
    );

    if (false == resposta || resposta.dado.lista.length == 0) {
        bloco.remove();
        return;
    }
    // try {
    conteudo.innerHTML = '';
    for (const item of resposta.dado.lista) {
        adicionarParceiro(conteudo, item);
    }
    // } catch (error) {
    //     bloco.remove();
    // }
};

window.addEventListener('load', async () => {
    const lista = $$('.com_relacionado');
    if (lista.length == 0) {
        return;
    }
    for (const bloco of lista) {
        comRelacionado(bloco);
    }
});
