class Popup {
    constructor() {
        throw new Error('A class Popup não pode ser instanciada.');
    }

    static init(option) {
        this._option = option;
        this.LINK =
            window.location.protocol +
            '//' +
            location.href.replace('http://', '').replace('https://', '').split('/')[0];
        this._acaoAbrirPopup();
        this._acaoFecharPopup();
        this._acaoMudarUri();
    }

    static _acaoAbrirPopup() {
        const option = this._option;
        const botaoAbrir = option.abrir.botao;
        const bloco = option.bloco;

        if (botaoAbrir.length == undefined && typeof botaoAbrir === 'object') {
            botaoAbrir.addEventListener('click', () => {
                this._popupAbrir(true);
            });
        } else if (botaoAbrir.length > 0 && typeof botaoAbrir === 'object') {
        }
        if (bloco.classList.contains('abrir_popup')) {
            this._popupAbrir(false);
        }
    }
    static _acaoFecharPopup() {
        const option = this._option;
        const bloco = option.bloco;
        const blocoId = bloco.getAttribute('id') || '';
        const botaoFechar = option.fechar.botao;
        if (botaoFechar.length == undefined && typeof botaoFechar === 'object') {
            botaoFechar.addEventListener('click', () => {
                this._popupFechar(true);
            });
        } else if (botaoFechar.length > 0 && typeof botaoFechar === 'object') {
            [].forEach.call(botaoFechar, botao => {
                botao.addEventListener('click', () => {
                    this._popupFechar(true);
                });
            });
        }

        document.querySelector('body').addEventListener('keydown', e => {
            if (e.key == 'Escape') {
                this._popupFechar(true);
            }
        });
        if (blocoId != '') {
            bloco.addEventListener('click', e => {
                if (e.target.getAttribute('id') == blocoId) {
                    this._popupFechar(true);
                }
            });
        }
    }
    static _acaoMudarUri() {
        const option = this._option;
        const regAbrir = new RegExp('^' + option.abrir.uri.replace(/\//g, '\\/') + '$');
        const regFechar = new RegExp('^' + option.fechar.uri.replace(/\//g, '\\/') + '$');
        window.onpopstate = e => {
            if (regFechar.test(document.location.pathname)) {
                this._popupFechar(false);
            } else if (regAbrir.test(document.location.pathname)) {
                this._popupAbrir(false);
            }
        };
    }

    static _popupAbrir(change) {
        const option = this._option;
        const bloco = option.bloco;
        const display = option.display || 'flex';
        const classe = option.display || 'popup_ativo';
        const titulo = option.abrir.titulo;
        const uri = option.abrir.uri;

        document.querySelector('body').classList.add('body_hide');

        bloco.style.display = display;
        setTimeout(() => {
            bloco.classList.add(classe);
        }, 20);

        if (change) {
            history.pushState({}, titulo, this.LINK + uri);
        }
    }
    static _popupFechar(change) {
        const option = this._option;
        const bloco = option.bloco;
        const classe = option.display || 'popup_ativo';
        const titulo = option.fechar.titulo;
        const uri = option.fechar.uri;

        bloco.classList.remove(classe);
        setTimeout(() => {
            document.querySelector('body').classList.remove('body_hide');
            bloco.style.display = 'none';
        }, 300);

        if (change) {
            history.pushState({}, titulo, this.LINK + uri);
        }
    }
}
