window.addEventListener('load', async () => {
    const blocoRelacionado = $('#bloco_parceiro_relacionado');
    const resposta = await ajaxPost(LINK + '/turismo/hotel', undefined, '');

    if (false == resposta) {
        $('.bloco_relacionado').remove();
        return;
    }
    blocoRelacionado.innerHTML = '';
    for (const item of resposta.dado.lista) {
        adicionarParceiro(blocoRelacionado, item);
    }
});
