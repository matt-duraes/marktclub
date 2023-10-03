document
    .querySelector('body')
    .insertAdjacentHTML('afterbegin', '<div id="bloco_fw_popup" class="fw_popup_display_none"></div>');
const blocoFwPopup = document.getElementById('bloco_fw_popup');
const blocoFwPopupBody = document.querySelector('body');
class Popup {
    /**
     * @param {string} titulo Título para o histório ao abrir
     * @param {element} bloco Elemento
     * @param {bool} fechar Se a página terá o botao de fechar
     * @param {bool} historico Se o navegador vai monitorar o histórico para abrir e fechar a página
     */
    constructor(titulo, bloco, fechar, historico, callback) {
        if (titulo == undefined || titulo == '') {
            return;
        }
        const linkExplode = window.location.href.split('#');
        this.linkAtual = linkExplode[0];
        this.historico = historico !== undefined ? historico : true;
        this.fechar = fechar !== undefined ? fechar : true;

        this.titulo = titulo;
        this.callback = callback;
        this.bloco = bloco;
        this.bloco.classList.remove('display_none');
        this.bloco.classList.add('fw_popup_conteudo');
        this.bloco.classList.add('fw_popup_display_none');
        this.ancoraAtual = linkExplode[1] || '';
        this.ancora = this.criarSlug(titulo);
        if (this.historico) {
            this.verificarSeVaiAbrirAoCarregar();
            this.monitorarTrocaDeUrl();
        }
    }
    criarSlug(titulo) {
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
    verificarSeVaiAbrirAoCarregar() {
        if (this.ancoraAtual != '' && this.ancoraAtual == this.ancora) {
            this.abrirInterno(false);
        }
    }
    monitorarTrocaDeUrl() {
        const self = this;
        window.onpopstate = () => {
            const url = window.location.href.split('#');
            if (url.length == 1 && $('.fw_popup_animacao')) {
                Popup.staticFechar();
            } else if (self.ancora == url[1]) {
                self.abrirInterno(false);
            }
        };
    }

    /**
     * Abre a página
     */
    abrir() {
        this.abrirInterno(this.historico);
    }
    async abrirInterno(historico) {
        blocoFwPopupBody.classList.add('fw_popup_body');
        const clone = this.bloco.cloneNode(true);
        clone.classList.remove('display_none');
        clone.classList.remove('fw_popup_display_none');
        clone.classList.add('fw_popup_conteudo_animacao');
        clone.removeAttribute('id');

        const listaId = clone.querySelectorAll('*[id]');
        for (const id of listaId) {
            id.removeAttribute('id');
        }

        const blocoAberto = blocoFwPopup.querySelector('.fw_popup_conteudo');
        if (blocoAberto) {
            const bloco = blocoFwPopup.querySelector('.fw_popup_conteudo');
            bloco.classList.add('fw_popup_conteudo_animacao');
            await new Promise(resolve => setTimeout(resolve, 300));
            blocoFwPopup.innerHTML = '';
        }

        blocoFwPopup.appendChild(clone);
        blocoFwPopup.classList.remove('fw_popup_display_none');
        setTimeout(() => {
            blocoFwPopup.classList.add('fw_popup_animacao');
            clone.classList.remove('fw_popup_conteudo_animacao');
        }, 40);
        if (this.callback) {
            this.callback(clone);
        }

        if (historico) {
            history.pushState({}, this.titulo, this.linkAtual + '#' + this.ancora);
        }
    }

    /**
     * Fecha a página
     */
    fechar() {
        Popup.staticFechar();
    }
    static async staticFechar() {
        blocoFwPopup.classList.remove('fw_popup_animacao');
        const bloco = blocoFwPopup.querySelector('.fw_popup_conteudo');
        bloco.classList.add('fw_popup_conteudo_animacao');

        let url = window.location.href.split('#');
        if (url.length > 1) {
            const titulo = $('title') || '';
            history.pushState({}, titulo, url[0]);
        }

        setTimeout(() => {
            blocoFwPopup.classList.add('fw_popup_display_none');
            blocoFwPopup.innerHTML = '';
            blocoFwPopupBody.classList.remove('fw_popup_body');
        }, 300);
    }
}
blocoFwPopup.addEventListener('click', e => {
    if (
        e.target.classList.contains('popup_fechar') ||
        e.target.closest('.popup_fechar') ||
        e.target.getAttribute('id') == 'bloco_fw_popup'
    ) {
        Popup.staticFechar();
    }
});
