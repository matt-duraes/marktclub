class Pagina {
    /**
     * @param {string} titulo Título para o histório ao abrir
     * @param {string} link Link que será carregado ao abrir
     * @param {object} request Object option do Fetch
     * @param {bool} fechar Se a página terá o botao de fechar
     * @param {bool} historico Se o navegador vai monitorar o histórico para abrir e fechar a página
     * @param {object} callback Callback que será executado depois de carregar a pagina
     */
    constructor(titulo, link, request, fechar, historico, callback) {
        if (titulo == undefined || titulo == '') {
            return;
        }
        const linkExplode = window.location.href.split('#');

        this._historico = historico !== undefined ? historico : true;
        this._fechar = fechar !== undefined ? fechar : true;

        this._montarRequest(request);
        this._link = link;
        this._linkAtual = linkExplode[0];
        this._titulo = titulo;
        this._ancoraAtual = linkExplode[1] || '';
        this._ancora = this._criarSlug(titulo);
        this._callback = callback;
        if (this._historico) {
            this._verificarSeVaiAbrirAoCarregar();
            this._monitorarTrocaDeUrl();
        }
    }
    _montarRequest(request) {
        if (request != undefined && request instanceof Object) {
            this._request = request;
            return;
        }
        this._request = {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
            },
        };
    }
    _criarSlug(titulo) {
        return titulo
            .toString()
            .normalize('NFD')
            .replace(/[\u0300-\u036f]/g, '')
            .toLowerCase()
            .trim()
            .replace(/\s+/g, '-')
            .replace(/[^\w\-]+/g, '')
            .replace(/\-\-+/g, '-');
    }
    _verificarSeVaiAbrirAoCarregar() {
        if (this._ancoraAtual != '' && this._ancoraAtual == this._ancora) {
            this._abrirInterno(false);
        }
    }
    _monitorarTrocaDeUrl() {
        window.onpopstate = e => {
            const url = window.location.href.split('#');
            if (url.length == 1) {
                this.fechar();
            } else if (this._ancora == url[1]) {
                this._abrirInterno(false);
            }
        };
    }

    /**
     * Abre a página
     */
    abrir() {
        this._abrirInterno(this._historico);
    }
    async _abrirInterno(historico) {
        this._abrirAnimacaoInicial();
        if (!(await this._carregarAjax(historico))) {
            this.fechar();
            return false;
        }
        return true;
    }

    /**
     * Fecha a página
     */
    fechar() {
        Pagina.staticFechar();
    }
    static async staticFechar() {
        document.querySelector('body').classList.remove('fw_pagina_body');
        const blocoGeral = document.querySelector('#bloco_fw_pagina');
        if (!blocoGeral) {
            return false;
        }
        let url = window.location.href.split('#');
        if (url.length > 1) {
            const titulo = document.querySelector('title') || '';
            history.pushState({}, titulo, url[0]);
        }
        const time = parseFloat(window.getComputedStyle(blocoGeral).getPropertyValue('transition-duration')) * 1000;

        blocoGeral.classList.remove('fw_pagina_animacao');
        blocoGeral.classList.remove('fw_pagina_animacao_conteudo');
        blocoGeral.classList.remove('fw_pagina_carregar');
        await setTimeout(() => {
            blocoGeral.innerHTML = '';
            blocoGeral.style.display = 'none';
        }, time);

        return true;
    }

    _abrirAnimacaoInicial() {
        const body = document.querySelector('body');
        const blocoGeral = document.querySelector('#bloco_fw_pagina');

        body.classList.add('fw_pagina_body');
        blocoGeral.style.display = 'flex';
        blocoGeral.classList.add('fw_pagina_carregando');

        setTimeout(() => {
            blocoGeral.classList.add('fw_pagina_animacao');
        }, 20);
    }

    _carregarAjax(historico) {
        const self = this;
        return fetch(this._link, this._request)
            .then(response => {
                if (response.status == 200) {
                    return response
                        .text()
                        .then(html => {
                            self._carregarPagina(html, historico);
                            return true;
                        })
                        .catch(() => {
                            return false;
                        });
                } else {
                    return false;
                }
            })
            .catch(() => {
                return false;
            });
    }

    async _carregarPagina(html, historico) {
        if (historico) {
            history.pushState({}, this._titulo, this._linkAtual + '#' + this._ancora);
        }

        const bloco = document.querySelector('#bloco_fw_pagina');
        bloco.classList.remove('fw_pagina_carregando');
        let classFechar = '';
        if (this._fechar) {
            bloco.classList.add('fw_pagina_box_fechar');
            classFechar = 'fw_pagina_box_fechar';
        }

        await this._removePaginaSeExistir(bloco);
        bloco.innerHTML = '';
        bloco.insertAdjacentHTML('beforeend', '<div class="fw_pagina_conteudo ' + classFechar + '">' + html + '</div>');
        bloco.style.display = 'block';

        setTimeout(() => {
            bloco.classList.add('fw_pagina_animacao_conteudo');
        }, 20);
        this._carregarScriptSistema(bloco);
        if (this._callback) {
            this._callback();
        }
    }

    async _removePaginaSeExistir(bloco) {
        const blocoConteudo = bloco.querySelector('.fw_pagina_conteudo');
        if (!blocoConteudo) {
            return Promise.resolve();
        }
        const time = parseFloat(window.getComputedStyle(blocoConteudo).getPropertyValue('transition-duration')) * 1000;
        bloco.classList.remove('fw_pagina_animacao_conteudo');
        return new Promise(r => setTimeout(r, time));
    }

    _carregarScriptSistema(bloco) {
        // MASCARA
        if (typeof fwMascaraLoading === 'function') {
            fwMascaraLoading(bloco);
        }

        // FORM
        if (typeof fwFormLoading === 'function') {
            fwFormLoading(bloco);
        }

        // CKEDITOR;
        if (typeof fwCkeditorLoading === 'function') {
            fwCkeditorLoading(bloco);
        }
        // UPLOAD ARQUIVO;
        if (typeof fwFormArquivoListaLoading === 'function') {
            fwFormArquivoListaLoading(bloco);
        }
        if (typeof fwFormArquivoLoading === 'function') {
            fwFormArquivoLoading(bloco);
        }
    }
}
document.querySelector('body').insertAdjacentHTML('afterbegin', '<div id="bloco_fw_pagina"></div>');
document.getElementById('bloco_fw_pagina').addEventListener('click', e => {
    if (e.target.classList.contains('fw_pagina_box_fechar')) {
        Pagina.staticFechar();
    }
});
