const blocoClone = $('#parceiro_padrao_loja');
if (blocoClone) {
    blocoClone.removeAttribute('id');
}

const adicionarParceiro = (bloco, item, tipo = 'loja') => {
    if (!blocoClone) {
        return;
    }
    const clone = blocoClone.cloneNode(true);
    clone.setAttribute('data-url', item.id);

    const favorito = clone.querySelector('.botao_favorito');
    if (tipo != 'cashback') {
        favorito.classList.remove('display_none');
    }

    if (item.favorito == 'sim') {
        favorito.classList.add('favorito_marcado');
    }
    clone.querySelector('.item_link').setAttribute('href', item.link);
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

    bloco.appendChild(clone);
};
