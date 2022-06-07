class Banner {

    init(option) {

        if (typeof option != 'object') {
            option = {};
        }

        if (typeof option.bloco != 'string' || typeof option.elemento != 'string') {
            return false;
        }

        let banner = document.getElementById(option.bloco);

        if (!banner) {
            return false;
        }

        let tempo = 8000;
        if (typeof option.tempo == 'number') {
            tempo = option.tempo * 1000;
        }

        let self = this;
        self._giroAutomatico(banner, option.elemento, tempo).then(retorno => {

            if (false === retorno) {
                return false;
            }

            let anterior, proximo;
            if (typeof option.anterior == 'string') {
                anterior = document.getElementById(option.anterior);
            }
            if (typeof option.proximo == 'string') {
                proximo = document.getElementById(option.proximo);
            }

            if (anterior && proximo) {
                proximo.addEventListener('click', function() {
                    self._proximo();
                });
                anterior.addEventListener('click', function() {
                    self._anterior();
                });
            }

            banner.addEventListener("swiped-left", function() {
                self._proximo();
            });
            banner.addEventListener("swiped-right", function() {
                self._anterior();
            });

        });

    }

    async _giroAutomatico(banner, elemento, tempo) {

        elemento = banner.querySelectorAll(elemento);
        let quantidade = elemento.length;
        if (quantidade == 0) {
            return false;
        }

        elemento[0].style.transition = 'opacity .3s ease-out';
        elemento[0].style.opacity = 1;
        elemento[0].style['z-index'] = 3;

        let i;
        for (i = 1; i < quantidade; ++i) {
            elemento[i].style.transition = 'opacity .3s ease-out';
            elemento[i].style.opacity = 0;
            elemento[i].style['z-index'] = 1;
        }

        this._bannerAtual = 0;
        this._bannerTotal = quantidade - 1;
        this._elemento = elemento;

        this._contador = setInterval(() => {
            this._proximo();
        }, tempo);

        self = this;
        banner.addEventListener('mouseover', function() {
            clearInterval(self._contador);
        });
        banner.addEventListener('mouseout', function() {
            self._contador = setInterval(() => {
                self._proximo();
            }, tempo);
        });

    }
    _proximo() {

        let bannerAtual = this._bannerAtual;
        if (bannerAtual == this._bannerTotal) {
            this._bannerAtual = 0;
        } else {
            this._bannerAtual++
        }

        let elementoAtual = this._elemento[bannerAtual];
        let elementoProximo = this._elemento[this._bannerAtual];

        elementoAtual.style.opacity = 0;
        elementoProximo.style.opacity = 1;
        setTimeout(() => {
            elementoAtual.style['z-index'] = 1;
            elementoProximo.style['z-index'] = 3;
        }, 300);

    }
    _anterior() {

        let bannerAtual = this._bannerAtual;
        if (bannerAtual == 0) {
            this._bannerAtual = this._bannerTotal;
        } else {
            this._bannerAtual--
        }

        let elementoAtual = this._elemento[bannerAtual];
        let elementoProximo = this._elemento[this._bannerAtual];

        elementoAtual.style.opacity = 0;
        elementoProximo.style.opacity = 1;
        setTimeout(() => {
            elementoAtual.style['z-index'] = 1;
            elementoProximo.style['z-index'] = 3;
        }, 300);

    }

}