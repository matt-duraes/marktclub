function inicializarPassoAPasso() {
    const listaGeral = document.querySelectorAll('.bloco_passo_passo_geral');

    if (listaGeral.length == 0) {
        return;
    }

    listaGeral.forEach((bloco, i) => {
        const blocoConteudo = bloco.querySelector('.bloco_conteudo');
        const conteudoLista = bloco.querySelectorAll('.bloco_conteudo .conteudo');
        const quantidadeConteudo = conteudoLista.length - 1;
        const itemLista = bloco.querySelectorAll('.bloco_progresso .item');
        blocoConteudo.classList.add('bloco_conteudo_item_' + conteudoLista.length);

        conteudoLista.forEach((conteudo, i2) => {
            let id = conteudo.getAttribute('id') || '';
            if (id == '') {
                id = 'id_passo_passo_' + i + '_' + i2;
                conteudo.setAttribute('id', id);
            }
            const item = itemLista[i2];
            item.setAttribute('data-id', id);
            item.setAttribute('data-numero', i2);

            let linha;
            if (i2 == 0) {
                linha = '</div><div class="linha_direita"></div>';
                item.classList.add('atual');
                const bola = item.querySelector('.bola');
                const numero = item.querySelector('span');
                const texto = item.querySelector('p');
                bola.classList.add('cor_border');
                numero.classList.add('cor_color');
                texto.classList.add('cor_color');
            } else if (i2 == quantidadeConteudo) {
                linha = '<div class="linha_esquerda">';
            } else {
                linha = '<div class="linha_esquerda"></div><div class="linha_direita"></div>';
            }
            item.insertAdjacentHTML('afterbegin', linha);
        });
        bloco.classList.add('carregado');
    });

    // Restante do código...
}

window.addEventListener('load', () => {
    inicializarPassoAPasso();
});
// ANTERIOR
const botaoAnterior = document.querySelectorAll('.botao_passa_passo_anterior');
if (botaoAnterior.length > 0) {
    botaoAnterior.forEach(botao => {
        botao.addEventListener('click', () => {
            irParaPassoAnterior(botao);
        });
    });
}
const irParaPassoAnterior = botao => {
    const bloco = botao.closest('.bloco_passo_passo_geral');
    const itemLista = bloco.querySelectorAll('.bloco_progresso .item');
    const itemAtual = bloco.querySelector('.bloco_progresso .item.atual');
    const numero = parseInt(itemAtual.getAttribute('data-numero')) - 1;
    const novoNumero = parseInt(numero) + 1;

    montarNovoItem(bloco, itemLista, numero, novoNumero);
};

// PROXIMO
const botaoProximo = document.querySelectorAll('.botao_passa_passo_proximo');
if (botaoProximo.length > 0) {
    botaoProximo.forEach(botao => {
        botao.addEventListener('click', () => {
            irParaProximoPasso(botao);
        });
    });
}

const irParaProximoPasso = botao => {
    const bloco = botao.closest('.bloco_passo_passo_geral');
    const itemLista = bloco.querySelectorAll('.bloco_progresso .item');
    const itemAtual = bloco.querySelector('.bloco_progresso .item.atual');
    const numero = parseInt(itemAtual.getAttribute('data-numero')) + 1;
    const novoNumero = parseInt(numero) + 1;

    montarNovoItem(bloco, itemLista, numero, novoNumero);
};

const montarNovoItem = (bloco, lista, numero, novoNumero) => {
    const atual = lista[numero];

    let jaFoiAtual = false;
    let blocoBola, blocoNumero, blocoTexto, blocoLinhaEsquerda, blocoLinhaDireita;
    lista.forEach(item => {
        blocoBola = item.querySelector('.bola');
        blocoNumero = item.querySelector('.bola span');
        blocoTexto = item.querySelector('p');
        blocoLinhaEsquerda = item.querySelector('.linha_esquerda');
        blocoLinhaDireita = item.querySelector('.linha_direita');

        if (item == atual) {
            item.classList.add('atual');
            item.classList.remove('concluido');

            blocoBola.classList.add('cor_border');
            blocoNumero.classList.add('cor_color');
            blocoTexto.classList.add('cor_color');
            if (blocoLinhaEsquerda) {
                blocoLinhaEsquerda.classList.add('cor_bg');
            }
            if (blocoLinhaDireita) {
                blocoLinhaDireita.classList.remove('cor_bg');
            }
            jaFoiAtual = true;
        } else if (jaFoiAtual) {
            item.classList.remove('atual');
            item.classList.remove('concluido');

            blocoBola.classList.remove('cor_border');
            blocoNumero.classList.remove('cor_color');
            blocoTexto.classList.remove('cor_color');
            if (blocoLinhaEsquerda) {
                blocoLinhaEsquerda.classList.remove('cor_bg');
            }
            if (blocoLinhaDireita) {
                blocoLinhaDireita.classList.remove('cor_bg');
            }
        } else {
            item.classList.add('concluido');
            item.classList.remove('atual');

            blocoBola.classList.add('cor_border');
            blocoNumero.classList.add('cor_color');
            blocoTexto.classList.add('cor_color');
            if (blocoLinhaEsquerda) {
                blocoLinhaEsquerda.classList.add('cor_bg');
            }
            if (blocoLinhaDireita) {
                blocoLinhaDireita.classList.add('cor_bg');
            }
        }
    });
    const blocoScroll = bloco.querySelector('.bloco_scroll');
    blocoScroll.className = 'bloco_scroll passo_' + novoNumero;
};
