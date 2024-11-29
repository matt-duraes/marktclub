if (!$('#bloco_fw_ajuda')) {
    $('body').inicio('<div id="bloco_fw_ajuda"></div>');
}
const fwBlocoAjuda = $('#bloco_fw_ajuda');
class Ajuda {
    constructor() {
        throw new Error('A class Ajuda não pode ser instanciada.');
    }

    static posicionarHtml() {
        let bloco = this.bloco;
        let blocoAjuda = $('#fw_ajuda');
        blocoAjuda.css('position', this.position);

        let blocoPosicao = bloco.getBoundingClientRect();

        let blocoTop = blocoPosicao.top;
        let blocoLeft = blocoPosicao.left;

        let blocoWidth = blocoPosicao.width;
        let blocoHeight = blocoPosicao.height;

        let textoWidth = blocoAjuda.offsetWidth;
        let scrollTop = document.querySelector('html').scrollTop;
        blocoAjuda.css({
            top: scrollTop + blocoTop + blocoHeight + 5 + 'px',
            left: blocoLeft - textoWidth / 2 + blocoWidth / 2 + 'px',
        });
    }

    static montarHtml() {
        if (this.texto != '' && this.texto != undefined) {
            fwBlocoAjuda.html(`<div id="fw_ajuda">${this.texto}</div>`);
            this.posicionarHtml();
        }
    }

    static show(bloco, texto, position) {
        if (window.innerWidth <= 1000) {
            return false;
        }

        if (position == undefined) {
            position = 'absolute';
        }

        this.texto = texto;
        this.bloco = bloco;
        this.position = position;

        this.montarHtml();
    }

    static hide() {
        document.getElementById('bloco_fw_ajuda').html('');
    }
}
