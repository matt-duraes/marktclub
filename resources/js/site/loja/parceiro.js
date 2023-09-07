const blocoClone = $('#parceiro_padrao_loja');
if (blocoClone) {
    blocoClone.removeAttribute('id');
}

const adicionarParceiro = (bloco, item) => {
    if (!blocoClone) {
        return;
    }
    const clone = blocoClone.cloneNode(true);
    const favorito = clone.querySelector('.botao_favorito');
    favorito.setAttribute('dta-url', item.id);
    if (item.favorito == 'sim') {
        favorito.classList.add('favorito_marcado');
    }
    clone.querySelector('.item_link').setAttribute('href', item.link);
    clone.querySelector('.item_logo').innerHTML = `<img src="${item.imagem}">`;
    clone.querySelector('.item_titulo').innerText = item.titulo;
    clone.querySelector('.item_desconto').innerText = item.desconto;
    if (item.estado != '') {
        clone.querySelector('.bloco_estado').classList.remove('display_none');
        clone.querySelector('.item_estado').innerText = item.estado;
    }

    bloco.appendChild(clone);
};
