// @template "site"
// @resource "site/loja/favorito"

const loadingFavoritoFaq = () => {
    const botaoFechar = $('#botao_faq_favorito_fechar');
    botaoFechar.addEventListener('click', () => {
        PaginaFavorito.fechar();
    });
};
const PaginaFavorito = new Pagina('faq-favorito', LINK + '/faq/favorito', {}, true, true, loadingFavoritoFaq);

window.addEventListener('load', () => {
    const botaoFavorito = $('#botao_favorito_tutorial');
    if (botaoFavorito) {
        botaoFavorito.addEventListener('click', () => {
            PaginaFavorito.abrir();
        });
    }
});

window.addEventListener('load', () => {
    new Historico($$('#bloco_historico .item'), '.lista img');
});

class Historico {
    /**
     * Inicia uma galeria
     *
     * @param {elemento} bloco Elemento onde vai ficar a galeria
     * @param {elemento} figure Qual vai ser o elemento que terá o bloco da foto
     * @param {elemento} botao Qual vai ser o elemento que abrirá a foto, caso não informe, será o figure
     * @param {string} download Link para download da imagem caso queira abilitar essa opção
     */
    constructor(botao, imagem) {
        this.setarEventoPadrao = false;
        this.imagem = imagem;
        this.carregarHtml();
        this.setarBloco();
        this.setarEventoGeral();
        this.recarregarEventoBotao(botao);
    }
    carregarHtml() {
        const bloco = document.querySelector('#fw_bloco_historico');
        if (bloco) {
            return;
        }
        this.setarEventoPadrao = true;
        BODY.insertAdjacentHTML(
            'beforeend',
            `
                <div id="fw_historico" class="fw_historico_hide">
                    <div class="fw_historico_loading"></div>
                    <div class="fw_historico_pausar_continuar" id="fw_historico_pausar"><svg height="20" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path style="fill-rule:evenodd;clip-rule:evenodd;" d="M40,20c0,11-9,20-20,20S0,31,0,20S9,0,20,0S40,9,40,20L40,20z M10.4,9.8c0-1.1,0.9-1.9,1.9-1.9h4.2 c1.1,0,1.9,0.9,1.9,1.9v20.3c0,1.1-0.9,1.9-1.9,1.9h-4.2c-1.1,0-1.9-0.9-1.9-1.9L10.4,9.8z M23.5,7.9c-1.1,0-1.9,0.9-1.9,1.9v20.3 c0,1.1,0.9,1.9,1.9,1.9h4.2c1.1,0,1.9-0.9,1.9-1.9V9.8c0-1.1-0.9-1.9-1.9-1.9H23.5z"/></svg></div>
                    <div class="fw_historico_pausar_continuar fw_historico_hide" id="fw_historico_continuar"><svg height="20" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><path class="st0" d="M20,0C8.95,0,0,8.95,0,20s8.95,20,20,20s20-8.95,20-20S31.05,0,20,0L20,0z M28.19,20.67l-12.66,9.21c-0.26,0.19-0.58,0.21-0.87,0.07s-0.46-0.42-0.46-0.74l0-18.43c0-0.32,0.17-0.6,0.46-0.74c0.29-0.15,0.61-0.12,0.87,0.07l12.66,9.21c0.22,0.16,0.34,0.4,0.34,0.67C28.54,20.28,28.42,20.51,28.19,20.67L28.19,20.67z"/></svg></div>
                    <div class="fw_historico_fechar" id="fw_historico_fechar"><svg height="17" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"  xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg></div>
                    <div class="fw_historico_seta" id="fw_historico_anterior">
                        <svg height="40" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 30" style="enable-background:new 0 0 16 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M0,967.6c0.1,0.6,0.3,1.2,0.7,1.6l10.2,12.1c1,1.2,2.8,1.4,4,0.4c1.2-1,1.4-2.8,0.4-4c0,0-0.1-0.1-0.1-0.1l-8.7-10.2 l8.7-10.2c1.1-1.2,1-3-0.2-4.1s-3-1-4,0.2c0,0-0.1,0.1-0.1,0.1L0.7,965.5C0.2,966.1-0.1,966.8,0,967.6L0,967.6z"/></g></svg>
                    </div>
                    <div class="fw_historico_seta" id="fw_historico_proximo">
                        <svg height="40" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 30" style="enable-background:new 0 0 16 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M16,967.1c-0.1-0.6-0.3-1.2-0.7-1.6L5.1,953.4c-1-1.2-2.8-1.4-4-0.4c-1.2,1-1.4,2.8-0.4,4c0,0,0.1,0.1,0.1,0.1l8.7,10.2 l-8.7,10.2c-1.1,1.2-1,3,0.2,4.1s3,1,4-0.2c0,0,0.1-0.1,0.1-0.1l10.2-12.1C15.8,968.6,16.1,967.9,16,967.1L16,967.1z"/></g></svg>
                    </div>

                    <figure class="fw_historico_figure><img class="fw_historico_img"></figure>

                    <a id="fw_historico_link" href="" target="">CLIQUE AQUI</a>
                </div>
            `
        );
    }
    setarBloco() {
        this.blocoHistorico = document.querySelector('#fw_historico');
        this.blocoLoading = document.querySelector('#fw_historico .fw_historico_loading');
        this.botaoAnterior = document.querySelector('#fw_historico_anterior');
        this.botaoProximo = document.querySelector('#fw_historico_proximo');
        this.botaoPausar = document.querySelector('#fw_historico_pausar');
        this.botaoContinuar = document.querySelector('#fw_historico_continuar');
        this.botaoFechar = document.querySelector('#fw_historico_fechar');
        this.blocoImagem = document.querySelector('#fw_historico .fw_historico_img');
    }
    setarEventoGeral() {
        if (!this.setarEventoPadrao) {
            return;
        }
        this.blocoHistorico.addEventListener('click', e => {
            if (e.target.getAttribute('id') == 'fw_historico') {
                this.fecharImagem();
            }
        });
        this.botaoProximo.addEventListener('click', () => {
            const atual = document.querySelector('.fw_historico_loading_barra.fw_historico_animar');
            if (!atual) {
                return;
            }
            atual.classList.remove('fw_historico_animar');
            atual.classList.add('fw_historico_visto');
            this.passarImagemProxima(atual);
        });
        this.botaoAnterior.addEventListener('click', () => {
            const atual = document.querySelector('.fw_historico_loading_barra.fw_historico_animar');
            if (!atual) {
                return;
            }
            atual.classList.remove('fw_historico_animar');
            atual.classList.add('fw_historico_visto');
            this.passarImagemAnterior(atual);
        });
        this.botaoFechar.addEventListener('click', () => {
            this.fecharImagem();
        });
        this.botaoPausar.addEventListener('click', () => {
            this.pausarHistorico();
        });
        this.botaoContinuar.addEventListener('click', () => {
            this.continuarHistorico();
        });
    }
    pausarHistorico() {
        this.hide(this.botaoPausar);
        this.show(this.botaoContinuar);
        const bloco = this.blocoLoading.querySelector('.fw_historico_loading_barra.fw_historico_animar');
        if (!bloco) {
            return;
        }
        bloco.classList.add('fw_historico_animar_pausar');
    }
    continuarHistorico() {
        this.show(this.botaoPausar);
        this.hide(this.botaoContinuar);
        const bloco = this.blocoLoading.querySelector('.fw_historico_animar_pausar');
        if (!bloco) {
            return;
        }
        bloco.classList.remove('fw_historico_animar_pausar');
    }
    recarregarEventoBotao(botao) {
        if (botao.length == 0) {
            return;
        }
        this.parceiroLista = botao;
        botao.forEach((item, i) => {
            item.setAttribute('id', 'botao_historico_item_' + i);
            item.addEventListener('click', () => {
                this.abrirImagem(item);
            });
        });
        botao[0].classList.add('fw_historico_primeiro');
        botao[botao.length - 1].classList.add('fw_historico_ultimo');
    }
    abrirImagem(item) {
        this.parceiroAtual = item;
        const imagem = item.querySelectorAll(this.imagem);
        this.show(this.blocoHistorico);
        setTimeout(() => {
            this.blocoHistorico.classList.add('fw_historico_abrir');
        }, 20);
        this.adicionarLoading(imagem);
    }
    fecharImagem() {
        this.blocoHistorico.classList.remove('fw_historico_abrir');
        setTimeout(() => {
            this.hide(this.blocoHistorico);
        }, 300);
    }
    adicionarLoading(imagem) {
        this.blocoLoading.innerHTML = '';
        let ativo = undefined;
        let i = 1;
        let item;
        let classe;
        const quantidade = imagem.length;
        for (; i <= quantidade; ++i) {
            item = imagem[i];
            classe = '';
            if (i == 1) {
                classe = 'fw_historico_primeiro';
            } else if (i == quantidade) {
                classe = 'fw_historico_ultimo';
            }
            if (ativo == undefined && !item.classList.contains('fw_historico_visto')) {
                ativo = i;
            }
            this.blocoLoading.insertAdjacentHTML(
                'beforeend',
                `<div class="fw_historico_loading_barra ${classe}"><span></span></div>`
            );
        }
        ativo = ativo == undefined ? 0 : ativo;
        const listaSpan = this.blocoLoading.querySelectorAll('.fw_historico_loading_barra');
        this.loadingLista = listaSpan;
        for (i = 0; i <= ativo; ++i) {
            if (i == ativo) {
                listaSpan[0].classList.add('fw_historico_animar');
                this.adicionarEventoFimAnimacao(listaSpan[0]);
                return;
            }
            listaSpan[i].classList.add('fw_historico_visto');
        }
    }
    adicionarEventoFimAnimacao(item) {
        const eventoProximaImagem = () => {
            this.passarImagemProxima(item);
        };
        item.addEventListener('animationend', eventoProximaImagem);
    }
    passarImagemProxima(atual) {
        this.continuarHistorico();
        atual.classList.remove('fw_historico_animar');
        atual.classList.add('fw_historico_visto');
        if (atual.classList.contains('fw_historico_ultimo')) {
            this.passarParceiroProximo();
            return;
        }
        let proximo, numero;
        this.loadingLista.forEach((item, i) => {
            if (atual == item) {
                numero = i + 1;
                proximo = this.loadingLista[numero];
            }
        });
        proximo.classList.add('fw_historico_animar');
        this.adicionarEventoFimAnimacao(proximo);
    }
    passarImagemAnterior(atual) {
        this.continuarHistorico();
        atual.classList.remove('fw_historico_animar');
        atual.classList.remove('fw_historico_visto');
        if (atual.classList.contains('fw_historico_primeiro')) {
            this.passarParceiroAnterior();
            return;
        }
        let anterior, numero;
        this.loadingLista.forEach((item, i) => {
            if (atual == item) {
                numero = i - 1;
                anterior = this.loadingLista[numero];
            }
        });
        anterior.classList.add('fw_historico_animar');
        this.adicionarEventoFimAnimacao(anterior);
    }
    passarParceiroProximo() {
        const parceiroAtual = this.parceiroAtual;
        if (!parceiroAtual) {
            this.fecharImagem();
            return;
        }
        parceiroAtual.classList.add('visto');
        let proximo, numero;
        this.parceiroLista.forEach((parceiro, i) => {
            if (parceiroAtual == parceiro) {
                numero = i + 1;
                proximo = this.parceiroLista[numero];
            }
        });
        if (!proximo) {
            this.fecharImagem();
            return;
        }
        this.colocarParceiroVisivel(proximo);
        this.abrirImagem(proximo);
    }
    passarParceiroAnterior() {
        const parceiroAtual = this.parceiroAtual;
        if (!parceiroAtual) {
            this.fecharImagem();
            return;
        }
        let anterior, numero;
        this.parceiroLista.forEach((parceiro, i) => {
            if (parceiroAtual == parceiro) {
                numero = i - 1;
                anterior = this.parceiroLista[numero];
            }
        });
        if (!anterior) {
            this.fecharImagem();
            return;
        }
        this.colocarParceiroVisivel(anterior);
        this.abrirImagem(anterior);
    }

    colocarParceiroVisivel(parceiro) {
        if (parceiro.offsetLeft > window.innerWidth || parceiro.offsetLeft + parceiro.offsetWidth < 0) {
            parceiro.scrollIntoView({
                behavior: 'smooth',
                block: 'nearest',
                inline: 'center',
            });
        }
    }

    show(bloco) {
        bloco.classList.remove('fw_historico_hide');
    }
    hide(bloco) {
        bloco.classList.add('fw_historico_hide');
    }
}
