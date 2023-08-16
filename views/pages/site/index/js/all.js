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
        this.imagem = imagem;
        this.carregarHtml();
        this.setarBloco();
        this.recarregarEventoBotao(botao);
    }
    carregarHtml() {
        const bloco = document.querySelector('#fw_bloco_historico');
        if (bloco) {
            return;
        }
        BODY.insertAdjacentHTML(
            'beforeend',
            `
                <div id="fw_historico" class="fw_historico_hide">
                    <div class="fw_historico_loading"></div>
                    <div class="fw_historico_seta fw_historico_hide" id="fw_historico_anterior">
                        <svg height="40" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 16 30" style="enable-background:new 0 0 16 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path d="M0,967.6c0.1,0.6,0.3,1.2,0.7,1.6l10.2,12.1c1,1.2,2.8,1.4,4,0.4c1.2-1,1.4-2.8,0.4-4c0,0-0.1-0.1-0.1-0.1l-8.7-10.2 l8.7-10.2c1.1-1.2,1-3-0.2-4.1s-3-1-4,0.2c0,0-0.1,0.1-0.1,0.1L0.7,965.5C0.2,966.1-0.1,966.8,0,967.6L0,967.6z"/></g></svg>
                    </div>
                    <div class="fw_historico_seta fw_historico_hide" id="fw_historico_proximo">
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
        this.blocoAnterior = document.querySelector('#fw_historico_anterior');
        this.blocoProximo = document.querySelector('#fw_historico_proximo');
        this.blocoImagem = document.querySelector('#fw_historico .fw_historico_img');
    }
    recarregarEventoBotao(botao) {
        if (botao.length == 0) {
            return;
        }
        botao.forEach((item, i) => {
            item.setAttribute('id', 'botao_historico_item_' + i);
            item.addEventListener('click', () => {
                this.abrirImagem(item);
            });
        });
        botao[0].classList.add('primeiro');
        botao[botao.length - 1].classList.add('ultimo');
    }
    abrirImagem(item) {
        const imagem = item.querySelectorAll(this.imagem);
        this.show(this.blocoHistorico);
        setTimeout(() => {
            this.blocoHistorico.classList.add('fw_historico_abrir');
        }, 20);
        this.adicionarLoading(imagem);
    }
    adicionarLoading(imagem) {
        this.blocoLoading.innerHTML = '';
        let ativo = undefined;
        imagem.forEach((item, i) => {
            if (ativo == undefined && !item.classList.contains('visto')) {
                ativo = i;
            }
            this.blocoLoading.insertAdjacentHTML(
                'beforeend',
                `<div class="fw_historico_loading_barra"><span></span></div>`
            );
        });
        ativo = ativo == undefined ? 0 : ativo;
        const listaSpan = this.blocoLoading.querySelectorAll('.fw_historico_loading_barra');
        let i = 0;
        for (; i <= ativo; ++i) {
            if (i == ativo) {
                listaSpan[0].classList.add('fw_historico_animar');
                return;
            }
            listaSpan[i].classList.add('fw_historico_visto');
        }
    }
    show(bloco) {
        bloco.classList.remove('fw_historico_hide');
    }
    hide(bloco) {
        bloco.classList.add('fw_historico_hide');
    }
}
