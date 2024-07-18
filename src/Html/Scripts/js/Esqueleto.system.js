class Esqueleto {
    /**
     *
     * @param {*} bloco Elemento principal do esqueleto
     * @param {*} loading Marcador CSS dos elementos filhos que precisam animar
     * @param {bool} bg false para não colocar background branco
     */
    constructor(bloco, loading, bg) {
        this.bloco = bloco;
        this.loading = loading;
        this.bg = bg == undefined ? true : bg;
    }

    show() {
        const bloco = this.bloco;
        const loading = this.loading;
        this.adicionarEventoBloco(bloco, loading);
    }
    adicionarEventoBloco(item, loading) {
        const css = window.getComputedStyle(item);
        const position = css.position;
        if (position != 'relative' && position != 'absolute' && position != 'fixed') {
            item.classList.add('fw_esqueleto_position');
        }
        item.classList.add('fw_esqueleto_overflow');
        item.classList.add('fw_esqueleto_bloco');
        if (this.bg) {
            item.classList.add('fw_esqueleto_bg');
        }
        item.classList.remove('display_none');
        if (loading === undefined || loading == '') {
            return;
        }
        this.adicionarEventoLoading(item, loading);
    }
    adicionarEventoLoading(bloco, loading) {
        const lista = bloco.querySelectorAll(loading);
        if (lista.length == 0) {
            return;
        }
        lista.forEach(item => {
            this.colocarZindexPai(bloco, item);
            const css = window.getComputedStyle(item);
            const position = css.position;
            if (position != 'relative' && position != 'absolute' && position != 'fixed') {
                item.classList.add('fw_esqueleto_position');
            }
            item.addEventListener('click', this.cancelarClick);
            item.insertAdjacentHTML('afterbegin', '<div class="fw_esqueleto_animacao"></div>');
            item.classList.add('fw_esqueleto_overflow');
            item.classList.add('fw_esqueleto_loading');

            if (item.classList.contains('display_none')) {
                item.classList.remove('display_none');
                item.classList.add('fw_esqueleto_none');
            }
        });
    }
    cancelarClick(e) {
        e.preventDefault();
        return;
    }
    colocarZindexPai(bloco, item) {
        let pai;
        let atual = item;
        let i = 0;
        for (; i < 100; ++i) {
            pai = atual.parentNode;
            if (pai == bloco) {
                atual.classList.add('fw_esqueleto_zindex');
                break;
            }
            atual = pai;
        }
    }
    hide() {
        const bloco = this.bloco;
        const loading = this.loading;
        this.removerEventoBloco(bloco, loading);
    }
    removerEventoBloco(item, loading) {
        item.classList.remove('fw_esqueleto_position');
        item.classList.remove('fw_esqueleto_overflow');
        item.classList.remove('fw_esqueleto_bloco');
        this.removerAnimacao(item);
        this.removerZindex(item);
        if (loading === undefined || loading == '') {
            return;
        }
        this.removerEventoLoading(item, loading);
    }
    removerEventoLoading(bloco, loading) {
        const lista = bloco.querySelectorAll(loading);
        if (lista.length == 0) {
            return;
        }
        lista.forEach(item => {
            item.classList.remove('fw_esqueleto_position');
            item.removeEventListener('click', this.cancelarClick);
            item.classList.remove('fw_esqueleto_overflow');
            item.classList.remove('fw_esqueleto_loading');
            if (item.classList.contains('fw_esqueleto_none')) {
                item.classList.remove('fw_esqueleto_none');
                item.classList.add('display_none');
            }
        });
    }
    removerZindex(bloco) {
        const lista = bloco.querySelectorAll('.fw_esqueleto_zindex');
        if (lista.length == 0) {
            return;
        }
        lista.forEach(item => {
            item.classList.remove('fw_esqueleto_zindex');
        });
    }
    removerAnimacao(bloco) {
        const animacao = bloco.querySelectorAll('.fw_esqueleto_animacao');
        if (animacao.length == 0) {
            return;
        }
        animacao.forEach(item => {
            item.remove();
        });
    }
}
