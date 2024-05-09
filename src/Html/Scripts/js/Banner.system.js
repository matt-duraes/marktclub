/* eslint-disable */
class Banner {
    constructor(banner, item, botaoProximo, botaoAnterior) {
        if (!banner) {
            return false;
        }
        this.banner = banner;
        this.banner.classList.add('fw_banner');
        this.botaoProximo = botaoProximo;
        this.botaoAnterior = botaoAnterior;

        this.lista = banner.querySelectorAll(item);
        this.quantidade = this.lista.length - 1;
        this.eventoBotaoProximoAnterior();
        this.eventoItem();
    }
    eventoItem() {
        this.lista.forEach((item, i) => {
            item.addEventListener('animationend', () => {
                this.bannerProximo();
            });
            item.classList.add('fw_banner_item');
            if (i > 0) {
                item.classList.add('fw_banner_hide');
                return;
            }
            item.classList.add('fw_banner_atual');
        });
    }
    bannerProximo() {
        const itemAtual = this.banner.querySelector('.fw_banner_atual');
        if (!itemAtual) {
            return;
        }
        const quantidade = this.quantidade;
        if (quantidade < 1) {
            return;
        }
        let lista = this.lista;
        let proximo;
        let i = 0;
        for (; i <= quantidade; i++) {
            if (lista[i] == itemAtual) {
                proximo = lista[i == quantidade ? 0 : i + 1];
                break;
            }
        }
        this.animarProximoBanner(itemAtual, proximo);
    }
    bannerAnterior() {
        const itemAtual = this.banner.querySelector('.fw_banner_atual');
        if (!itemAtual) {
            return;
        }
        const quantidade = this.quantidade;
        if (quantidade < 1) {
            return;
        }
        let lista = this.lista;
        let proximo;
        let i = 0;
        for (; i <= quantidade; i++) {
            if (lista[i] == itemAtual) {
                proximo = lista[i == 0 ? quantidade : i - 1];
                break;
            }
        }
        this.animarProximoBanner(itemAtual, proximo);
    }
    animarProximoBanner(atual, proximo) {
        atual.classList.remove('fw_banner_atual');
        setTimeout(() => {
            atual.classList.add('fw_banner_hide');
        }, 100);

        proximo.classList.remove('fw_banner_hide');
        setTimeout(() => {
            proximo.classList.add('fw_banner_atual');
        }, 20);
    }
    eventoBotaoProximoAnterior() {
        if (this.lista.length <= 1) {
            return;
        }
        const banner = this.banner;
        const botaoProximo = this.botaoProximo;
        const botaoAnterior = this.botaoAnterior;

        const self = this;
        if (botaoProximo && botaoAnterior) {
            botaoProximo.addEventListener('click', function () {
                self.bannerProximo();
            });
            botaoAnterior.addEventListener('click', function () {
                self.bannerAnterior();
            });
        }

        banner.addEventListener('swiped-left', function () {
            self.bannerAnterior();
        });
        banner.addEventListener('swiped-right', function () {
            self.bannerProximo();
        });
    }
}
/* eslint-enable */
