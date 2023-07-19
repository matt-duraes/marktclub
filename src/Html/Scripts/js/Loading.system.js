if (!document.querySelector('#bloco_fw_loading')) {
    document.querySelector('body').insertAdjacentHTML('beforeend', `<div id="bloco_fw_loading"></div>`);
}
class Loading {
    constructor() {
        throw new Error('A class Loading não pode ser instanciada.');
    }

    static total(bloco) {
        this._totalHtml().then(() => {
            this._totalPosicao(bloco);
            document.getElementById('fw_loading_total_player').play();
        });
    }
    static async _totalHtml() {
        document.getElementById('bloco_fw_loading').innerHTML = `
            <div id="fw_loading_total">
                <div id="fw_loading_total_bg"></div>
                <lottie-player id="fw_loading_total_player" src="/images/loading/total.json" background="transparent" loop speed="1"></lottie-player>
            </div>
        `;
        return true;
    }
    static _totalPosicao(bloco) {
        const posicao = bloco.getBoundingClientRect();
        const marginTop = bloco.offsetTop - document.querySelector('html').scrollTop + posicao.height / 2;
        const marginLeft = bloco.offsetLeft + posicao.width / 2;
        const blocoTotalBg = document.getElementById('fw_loading_total_bg');
        if (marginTop > 0 && marginTop < window.innerHeight) {
            blocoTotalBg.style.top = marginTop + 'px';
            blocoTotalBg.style.left = marginLeft + 'px';
        }
    }

    static show() {
        let tipo = this._tipo;
        this._tipo = undefined;

        if (tipo == undefined) {
            this._bolaShow();
        } else if (tipo == 'form') {
            this._formShow();
        } else if (tipo == 'barra') {
            this._barraShow();
        }
    }
    static hide() {
        let tipo = this._tipo;
        this._tipo = undefined;

        if (tipo == undefined) {
            this._bolaHide();
        } else if (tipo == 'form') {
            this._formHide();
        } else if (tipo == 'barra') {
            this._barraHide();
        }
    }

    static _bolaShow() {
        document.getElementById('bloco_fw_loading').innerHTML = `
            <div id="fw_loading_bola">
                <div class="fw_loading_bola_icone"></div>
                <div class="fw_loading_bola_texto">AGUARDE</div>
            </div>
        `;
        setTimeout(() => {
            document.querySelector('#fw_loading_bola').classList.add('fw_loading_abrir');
        }, 20);
    }
    static _bolaHide() {
        let elemento = document.querySelector('#fw_loading_bola');
        if (!elemento) {
            return false;
        }

        elemento.classList.remove('fw_loading_abrir');
        setTimeout(() => {
            elemento.parentNode.removeChild(elemento);
        }, 300);
    }

    static botao(botao, option) {
        this._bloco = null;
        this._botao = botao;
        this._option = option;
        this._tipo = 'form';
        return this;
    }
    static form(bloco, botao, option) {
        this._bloco = bloco;
        this._botao = botao;
        this._option = option;
        this._tipo = 'form';
        return this;
    }
    static _formShow() {
        let bloco = this._bloco;
        if (this._bloco != null) {
            if (typeof bloco == 'string') {
                bloco = document.querySelector(bloco);
            }
            if (typeof bloco != 'object') {
                return false;
            }
            if (bloco.querySelector('.fw_loading_form')) {
                return false;
            }

            let position = document.defaultView.getComputedStyle(bloco, null).getPropertyValue('position');
            if (position == 'static' || position == '') {
                bloco.style.position = 'relative';
            }

            bloco.insertAdjacentHTML('afterbegin', '<div class="fw_loading_form"></div>');
        }

        let botao = this._botao;
        if (typeof botao == 'string') {
            botao = document.querySelector(botao);
        }
        if (typeof botao != 'object') {
            return false;
        }

        let botaoBgPadrao = botao.style['background-color'];
        let botaoCorPadrao = botao.style.color;
        let botaoTextoPadrao = botao.textContent;

        botao.setAttribute('data-bgcolor', botaoBgPadrao);
        botao.setAttribute('data-color', botaoCorPadrao);
        botao.setAttribute('data-texto', botaoTextoPadrao);

        let option = this._option;
        if (typeof option != 'object') {
            option = {};
        }

        let botaoTexto = 'AGUARDE';
        if (typeof option.botaoTexto == 'string') {
            botaoTexto = option.botaoTexto;
        }
        let botaoBg = '#CCC';
        if (typeof option.botaoBg == 'string') {
            botaoBg = option.botaoBg;
        }
        let botaoCor = '#666';
        if (typeof option.botaoCor == 'string') {
            botaoCor = option.botaoCor;
        }

        botao.textContent = botaoTexto;
        botao.style.transition = 'background-color .3s ease-out, color .3s ease-out';
        botao.style['background-color'] = botaoBg;
        botao.style.color = botaoCor;
    }

    static _formHide() {
        let bloco = this._bloco;
        if (bloco != null) {
            if (typeof bloco == 'string') {
                bloco = document.querySelector(bloco);
            }
            if (typeof bloco == 'object') {
                let loading = bloco.querySelector('.fw_loading_form');
                if (typeof loading == 'object') {
                    // loading.classList.add('fw_loading_form_fim');
                    loading.parentNode.removeChild(loading);
                }
            }
        }

        let botao = this._botao;
        if (typeof botao == 'string') {
            botao = document.querySelector(botao);
        }
        if (typeof botao != 'object') {
            return false;
        }

        let botaoBg = botao.getAttribute('data-bgcolor');
        let botaoCor = botao.getAttribute('data-color');
        let botaoTexto = botao.getAttribute('data-texto');

        botao.style['background-color'] = botaoBg;
        botao.style.color = botaoCor;
        botao.textContent = botaoTexto;
    }

    static barra(botao, option) {
        this._tipo = 'barra';
        this._botao = botao;
        this._option = option;

        return this;
    }
    static _barraShow() {
        let botao = this._botao;
        if (typeof botao == 'string') {
            botao = document.querySelector(botao);
        }
        if (typeof botao != 'object') {
            return false;
        }

        let position = document.defaultView.getComputedStyle(botao, null).getPropertyValue('position');
        if (position == '' || position == 'static') {
            botao.style.position = 'relative';
        }

        botao.style.display = 'flex';
        botao.style['flex-direction'] = 'row';
        botao.style['align-items'] = 'center';
        botao.style['justify-content'] = 'center';

        let option = this._option;
        if (typeof option != 'object') {
            option = {};
        }

        let barraBg = '#FFFFFF';
        if (typeof option.barraBg == 'string') {
            barraBg = option.barraBg;
        }

        let botaoBgPadrao = botao.style['background-color'];
        let botaoCorPadrao = botao.style.color;
        let botaoTextoPadrao = botao.textContent;

        botao.setAttribute('data-bgcolor', botaoBgPadrao);
        botao.setAttribute('data-color', botaoCorPadrao);
        botao.setAttribute('data-texto', botaoTextoPadrao);

        let transition = [];
        if (typeof option.botaoTexto == 'string') {
            botao.textContent = option.botaoTexto;
        }
        if (typeof option.botaoBg == 'string') {
            botao.style['background-color'] = option.botaoBg;
            transition.push('background-color .3s ease-out');
        }
        if (typeof option.botaoBor == 'string') {
            botao.style.color = option.botaoCor;
            transition.push('color .3s ease-out');
        }

        if (transition.length > 0) {
            botao.style.transition = transition.join(', ');
        }

        botao.insertAdjacentHTML(
            'beforeend',
            `
            <div class="fw_loading_barra"><div class="fw_loading_barra_barra" style="background-color: ` +
                barraBg +
                `"></div><div class="fw_loading_barra_barra" style="background-color: ` +
                barraBg +
                `"></div><div class="fw_loading_barra_barra" style="background-color: ` +
                barraBg +
                `"></div></div>
        `
        );
    }
    static _barraHide() {
        let botao = this._botao;

        let elemento = botao.querySelector('.fw_loading_barra');
        if (!elemento) {
            return false;
        }

        let botaoBg = botao.getAttribute('data-bgcolor');
        let botaoCor = botao.getAttribute('data-color');
        let botaoTexto = botao.getAttribute('data-texto');

        botao.style['background-color'] = botaoBg;
        botao.style.color = botaoCor;
        botao.textContent = botaoTexto;
    }
}
