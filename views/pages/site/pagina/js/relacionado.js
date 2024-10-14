const comRelacionadoParceiro = $('.parceiro_loja_padrao');
if (comRelacionadoParceiro) {
    comRelacionadoParceiro.classe('parceiro_loja_padrao', false);
}

const comRelacionado = async bloco => {
    const conteudo = $('.bloco_parceiro', bloco);
    const id = bloco.attr('data-id');

    const EsqueletoItem = new Esqueleto(conteudo, '.esqueleto');
    EsqueletoItem.show();

    const resposta = await ajaxPost(
        LINK + '/componente',
        {
            id,
            url: paginaUrl,
            campo: ['id', 'titulo', 'url', 'tipo_loja', 'desconto', 'imagem_logo', 'endereco_estado'],
        },
        ''
    );

    if (false == resposta || resposta.dado.lista.length == 0) {
        bloco.remove();
        return;
    }

    EsqueletoItem.hide();
    conteudo.innerHTML = '';
    for (const item of resposta.dado.lista) {
        comRelacionadoAdicionarParceiro(conteudo, item, tipoLoja);
    }
};
const comRelacionadoLink = (tipo, uri) => {
    //
};
const comRelacionadoAdicionarParceiro = (conteudo, item) => {
    const clone = comRelacionadoParceiro.clonar();
    clone.setAttribute('data-url', item.id);

    const favorito = clone.querySelector('.botao_favorito');
    favorito.classList.remove('display_none');

    if (item.favorito == 'sim') {
        favorito.classList.add('favorito_marcado');
    }

    clone.querySelector('.item_link').attr('href', comRelacionadoLink(item.uri));
    clone.querySelector('.item_logo').innerHTML = `<img src="${item.imagem}">`;
    clone.querySelector('.item_titulo').innerText = item.titulo;
    clone.querySelector('.item_desconto').innerHTML = tipo == 'cashback' ? item.desconto + '%' : item.desconto;
    if (tipo == 'cashback') {
        clone.querySelector('.item_pontos').innerText = 'Revertido em pontos SILIUM';
        clone.querySelector('.item_volta').innerText = 'Receba de volta';
    }
    if (item.estado != '' && tipo != 'cashback') {
        clone.querySelector('.bloco_estado').classList.remove('display_none');
        clone.querySelector('.item_estado').innerText = item.estado;
    }

    conteudo.final(clone);
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
