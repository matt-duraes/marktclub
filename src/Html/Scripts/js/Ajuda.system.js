class Ajuda {

    constructor() {

        throw new Error('A class Ajuda não pode ser instanciada.');

    }

    static posicionarHtml() {

        let bloco = this.bloco;
        let blocoAjuda = document.getElementById('fw_ajuda');

        let blocoPosicao = bloco.getBoundingClientRect();

        let blocoTop = blocoPosicao.top;
        let blocoLeft = blocoPosicao.left;

        let blocoWidth = blocoPosicao.width;
        let blocoHeight = blocoPosicao.height;

        let textoWidth = blocoAjuda.offsetWidth;
        let scrollTop = document.querySelector('html').scrollTop;
        blocoAjuda.style.top = (scrollTop + blocoTop + blocoHeight + 5) + 'px';
        blocoAjuda.style.left = (blocoLeft - (textoWidth / 2) + (blocoWidth / 2)) + 'px';

    }

    static montarHtml() {

        if (this.texto != '' && this.texto != undefined) {
            document.getElementById('bloco_fw_ajuda').innerHTML = '<div id="fw_ajuda" style="position: ' + this.position + '">' + this.texto + '</div>';
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
        document.getElementById('bloco_fw_ajuda').innerHTML = '';
    }

}