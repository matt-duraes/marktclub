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
    new Historico($('#bloco_historico'), LINK + '/historico');
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
    constructor(bloco, link) {
        this.blocoDestino = bloco;
        bloco.classList.add('fw_historico_destino');
        this.link = link;
        this.montarClasse();
    }

    // MONTA O HISTORICO INICIAL
    async montarClasse() {
        await this.carregarHtml();
        await this.setarBloco();
        await this.carregarMascara();
        if (!(await this.buscarHistorico())) {
            return;
        }
        if (!(await this.adicionarHistorico())) {
            return;
        }
        this.setarEventoGeral();
        this.carregarEventoParceiro();
    }
    // CARREGA O HTML DO VISUALIZAR DO HISTORICO
    carregarHtml() {
        return new Promise(resolve => {
            const bloco = document.querySelector('#fw_bloco_historico');
            if (bloco) {
                resolve(true);
                return;
            }
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

                    <figure class="fw_historico_figure"></figure>

                    <a class="fw_historico_link fw_historico_hide" id="fw_historico_link" href="" target="">ACESSAR</a>
                </div>
            `
            );
            resolve(true);
        });
    }

    // SETAR AS PROPRIEDADES INICIAIS
    setarBloco() {
        return new Promise(resolve => {
            this.blocoHistorico = document.querySelector('#fw_historico');
            this.blocoLoading = document.querySelector('#fw_historico .fw_historico_loading');
            this.botaoAnterior = document.querySelector('#fw_historico_anterior');
            this.botaoProximo = document.querySelector('#fw_historico_proximo');
            this.botaoPausar = document.querySelector('#fw_historico_pausar');
            this.botaoContinuar = document.querySelector('#fw_historico_continuar');
            this.botaoFechar = document.querySelector('#fw_historico_fechar');
            this.blocoImagem = document.querySelector('#fw_historico .fw_historico_figure');
            this.botaoLink = document.querySelector('#fw_historico_link');
            resolve(true);
        });
    }

    // COLOCA MASCARA PARA LOADING
    carregarMascara() {
        return new Promise(resolve => {
            let mascara = '';
            let i = 0;
            for (; i < 20; ++i) {
                mascara += `
                    <div class="fw_historico_item_geral fw_historico_mascara">
                        <figure class="fw_historico_parceiro"></figure>
                        <div class="fw_historico_nome"></div>
                    </div>
                `;
            }
            this.blocoDestino.innerHTML = mascara;
            resolve(true);
        });
    }

    // FAZE O REQUEST PARA BUSCAR OS PARCEIROS
    async buscarHistorico() {
        return new Promise(async resolve => {
            const resposta = await fetch(this.link, { method: 'POST' });
            try {
                const json = await resposta.json();
                if (json.status != 'sucesso') {
                    resolve(false);
                    return;
                }
                this.historico = json.dado;
                resolve(true);
            } catch (error) {
                resolve(false);
            }
        });
    }

    // ADICIONA OS PARCEIROS
    async adicionarHistorico() {
        return new Promise(resolve => {
            if (this.historico.length == 0) {
                this.blocoDestino.innerHTML = '';
                resolve(false);
                return;
            }
            let html = '';
            let htmlVisto = '';
            const listaIdAtivo = [];
            this.historico.forEach(item => {
                const quantidadeImagem = item.imagem.length;
                let quantidadeVisto = 0;

                if (quantidadeImagem == 0) {
                    return;
                }

                let imagemHtml = '';
                item.imagem.forEach(imagem => {
                    const idImagem = 'i_' + imagem.id;
                    listaIdAtivo.push(idImagem);
                    let classeImagem = '';
                    if (localStorage.getItem(idImagem)) {
                        quantidadeVisto++;
                        classeImagem = 'fw_historico_visto';
                    }
                    imagemHtml += `<img data-id="${idImagem}" class="${classeImagem}" data-link="${imagem.link}" src="${imagem.imagem}">`;
                });

                const classe = quantidadeImagem == quantidadeVisto ? 'fw_historico_visto' : '';
                const htmlTemp = `
                    <div class="fw_historico_item_geral fw_historico_item ${classe}">
                        <figure class="fw_historico_parceiro">
                            <span><img src="${item.logo}" alt=""></span>
                        </figure>
                        <div class="fw_historico_nome">${item.titulo}</div>
                        <div class="fw_historico_imagem_lista">
                            ${imagemHtml}
                        </div>
                    </div>
                `;
                if (quantidadeImagem == quantidadeVisto) {
                    htmlVisto += htmlTemp;
                } else {
                    html += htmlTemp;
                }
            });
            this.blocoDestino.innerHTML = html + htmlVisto;
            this.limparItemLocalStorage(listaIdAtivo);
            resolve(true);
        });
    }

    // LIMPA O LOCALSTORAGE
    limparItemLocalStorage(id) {
        let i = 0;
        for (; i < localStorage.length; ++i) {
            const key = localStorage.key(i);
            if (!id.includes(key)) {
                localStorage.removeItem(key);
            }
        }
    }

    // SETAR EVENTOS DO HISTORICO
    setarEventoGeral() {
        this.blocoHistorico.addEventListener('click', e => {
            if (e.target.getAttribute('id') == 'fw_historico') {
                this.fecharBloco();
            }
        });
        this.botaoProximo.addEventListener('click', () => {
            this.passarImagemProxima();
        });
        this.botaoAnterior.addEventListener('click', () => {
            this.passarImagemAnterior();
        });
        this.botaoFechar.addEventListener('click', () => {
            this.fecharBloco();
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
    // CARREGA EVENTOS DO PARCEIRO
    carregarEventoParceiro() {
        const parceiro = this.blocoDestino.querySelectorAll('.fw_historico_item');
        this.parceiroLista = parceiro;
        parceiro.forEach((item, i) => {
            item.setAttribute('id', 'botao_historico_item_' + i);
            item.addEventListener('click', () => {
                this.parceiroAtual = item;
                this.abrirBloco();
                this.abrirNovoParceiro();
            });
        });
        parceiro[0].classList.add('fw_historico_primeiro');
        parceiro[parceiro.length - 1].classList.add('fw_historico_ultimo');
    }

    /*
    |--------------------------------------------------------------------------
    | ABRE/FECHA BLOCO VISUALIZACAO
    |--------------------------------------------------------------------------
    */
    abrirBloco() {
        this.show(this.blocoHistorico);
        setTimeout(() => {
            this.blocoHistorico.classList.add('fw_historico_abrir');
        }, 20);
    }
    fecharBloco() {
        this.blocoHistorico.classList.remove('fw_historico_abrir');
        setTimeout(() => {
            this.hide(this.blocoHistorico);
        }, 300);
    }

    /*
    |--------------------------------------------------------------------------
    | CARREGA IMAGEM
    |--------------------------------------------------------------------------
    */
    async abrirNovoParceiro() {
        await this.adicionarLoading();
        const numeroItem = await this.setarQualImagemAbrir();
        const loadingAtual = await this.setarClasseLoading(numeroItem);
        this.setarEventoAnimacao(loadingAtual);
        this.setarImagem(numeroItem);
    }
    async abrirNovaImagem(numero) {
        const loadingAtual = await this.setarClasseLoading(numero);
        this.setarEventoAnimacao(loadingAtual);
        this.setarImagem(numero);
    }
    adicionarLoading() {
        return new Promise(resolve => {
            this.imagemLista = this.parceiroAtual.querySelectorAll('.fw_historico_imagem_lista img');
            this.blocoLoading.innerHTML = '';
            let ativo = undefined;
            let i = 0;
            let item;
            const quantidade = this.imagemLista.length;
            for (; i < quantidade; ++i) {
                item = this.imagemLista[i];
                if (ativo == undefined && !item.classList.contains('fw_historico_visto')) {
                    ativo = i;
                }
                this.blocoLoading.insertAdjacentHTML(
                    'beforeend',
                    `<div class="fw_historico_loading_barra"><span></span></div>`
                );
            }
            this.loadingLista = this.blocoLoading.querySelectorAll('.fw_historico_loading_barra');
            this.loadingLista[0].classList.add('fw_historico_primeiro');
            this.loadingLista[quantidade - 1].classList.add('fw_historico_ultimo');

            resolve(true);
        });
    }
    setarQualImagemAbrir() {
        return new Promise(resolve => {
            let quantidadeVisto = 0;
            let itemEscolhido = undefined;
            this.imagemLista.forEach((item, i) => {
                if (item.classList.contains('fw_historico_visto')) {
                    quantidadeVisto++;
                } else if (itemEscolhido == undefined) {
                    itemEscolhido = i;
                }
            });
            if (this.imagemLista.length == quantidadeVisto || itemEscolhido == undefined) {
                itemEscolhido = 0;
            }
            resolve(itemEscolhido);
        });
    }
    setarClasseLoading(numero) {
        return new Promise(resolve => {
            this.loadingLista.forEach((item, i) => {
                item.classList.remove('fw_historico_visto');
                item.classList.remove('fw_historico_animar');
                if (i < numero) {
                    item.classList.add('fw_historico_visto');
                } else if (i == numero) {
                    item.classList.add('fw_historico_animar');
                    resolve(item);
                }
            });
        });
    }
    setarImagem(numero) {
        const clone = this.imagemLista[numero].cloneNode(true);
        const link = clone.getAttribute('data-link');
        const idImagem = clone.getAttribute('data-id');

        if (!localStorage.getItem(idImagem)) {
            localStorage.setItem(idImagem, 1);
        }

        this.blocoImagem.innerHTML = '';
        this.blocoImagem.appendChild(clone);
        this.botaoLink.classList.add('fw_historico_hide');
        if (link != '') {
            this.botaoLink.classList.remove('fw_historico_hide');
            this.botaoLink.setAttribute('href', link);
        }
    }
    setarEventoAnimacao(item) {
        const eventoProximaImagem = () => {
            this.passarImagemProxima(item);
        };
        item.removeEventListener('animationend', eventoProximaImagem);
        item.addEventListener('animationend', eventoProximaImagem);
    }

    /*
    |--------------------------------------------------------------------------
    | PROXIMO
    |--------------------------------------------------------------------------
    */
    passarImagemProxima() {
        this.continuarHistorico();
        const atual = this.blocoLoading.querySelector('.fw_historico_animar');
        if (atual.classList.contains('fw_historico_ultimo')) {
            this.passarParceiroProximo();
            return;
        }
        let numero = 0;
        this.loadingLista.forEach((item, i) => {
            if (item == atual) {
                numero = i + 1;
            }
        });
        this.abrirNovaImagem(numero);
    }
    passarParceiroProximo() {
        const parceiro = this.parceiroAtual;
        parceiro.classList.add('fw_historico_visto');
        if (parceiro.classList.contains('fw_historico_ultimo')) {
            this.fecharBloco();
            return;
        }
        let numero;
        this.parceiroLista.forEach((item, i) => {
            if (item == parceiro) {
                numero = i + 1;
            }
        });
        this.parceiroAtual = this.parceiroLista[numero];
        this.abrirNovoParceiro();
    }

    /*
    |--------------------------------------------------------------------------
    | ANTERIOR
    |--------------------------------------------------------------------------
    */
    passarImagemAnterior() {
        this.continuarHistorico();
        const atual = this.blocoLoading.querySelector('.fw_historico_animar');
        if (atual.classList.contains('fw_historico_primeiro')) {
            this.passarParceiroAnterior();
            return;
        }
        let numero = 0;
        this.loadingLista.forEach((item, i) => {
            if (item == atual) {
                numero = i - 1;
            }
        });
        this.abrirNovaImagem(numero);
    }
    passarParceiroAnterior() {
        const parceiro = this.parceiroAtual;
        if (parceiro.classList.contains('fw_historico_primeiro')) {
            return;
        }
        let numero;
        this.parceiroLista.forEach((item, i) => {
            if (item == parceiro) {
                numero = i - 1;
            }
        });
        this.parceiroAtual = this.parceiroLista[numero];
        this.abrirNovoParceiro();
    }

    /*
    |--------------------------------------------------------------------------
    | GERAL
    |--------------------------------------------------------------------------
    */
    show(bloco) {
        bloco.classList.remove('fw_historico_hide');
    }
    hide(bloco) {
        bloco.classList.add('fw_historico_hide');
    }
}
