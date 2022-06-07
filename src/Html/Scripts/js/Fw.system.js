class Fw {
    static el(elemento, pai) {
        this._elemento = elemento;
        this._pai = pai;
        return this;
    }

    static html(valor) {
        let elemento = this._getEl();

        if (!elemento) {
            return false;
        }

        if (valor != '' && valor != undefined && valor != null) {
            elemento.innerHTML = valor;
            return this;
        } else {
            return elemento.innerHTML;
        }
    }
    static text(valor) {
        let elemento = this._getEl();

        if (!elemento) {
            return false;
        }

        if (valor != '' && valor != undefined && valor != null) {
            elemento.textContent = valor;
            return this;
        } else {
            return elemento.textContent;
        }
    }

    static append(valor) {
        let elemento = this._getEl();

        if (!elemento) {
            return false;
        }

        if (valor != '' && valor != undefined && valor != null) {
            elemento.insertAdjacentHTML('beforeend', valor);
            return this;
        }
    }
    static prepend(valor) {
        let elemento = this._getEl();

        if (!elemento) {
            return false;
        }

        if (valor != '' && valor != undefined && valor != null) {
            elemento.insertAdjacentHTML('afterbegin', valor);
            return this;
        }
    }

    static css(propriedade, valor) {
        let elemento = this._getElAll();

        let quantidade = elemento.length;
        if (quantidade == 0) {
            return false;
        }

        if (typeof propriedade == 'string' && valor == undefined) {
            let style = window.getComputedStyle(elemento[0]);
            return style.getPropertyValue(propriedade);
        }

        let i;
        for (i = 0; i < quantidade; ++i) {
            if (typeof propriedade == 'string') {
                elemento[i].style[propriedade] = valor;
            } else if (typeof propriedade == 'object') {
                Object.entries(propriedade).forEach(function (val) {
                    elemento[i].style[val[0]] = val[1];
                });
            }
        }

        return this;
    }

    static addClass(classe, time) {
        if (classe == '' || classe == undefined) {
            return false;
        }

        let elemento = this._getElAll();

        let quantidade = elemento.length;
        if (quantidade == 0) {
            return false;
        }

        let i;
        let delay = typeof time == 'number';
        for (i = 0; i < quantidade; ++i) {
            if (delay) {
                setTimeout(
                    function (parEl, parClass) {
                        parEl.classList.add(parClass);
                    },
                    time,
                    elemento[i],
                    classe
                );
            } else {
                elemento[i].classList.add(classe);
            }
        }

        return this;
    }
    static removeClass(classe, time) {
        if (classe == '' || classe == undefined) {
            return false;
        }

        let elemento = this._getElAll();

        let quantidade = elemento.length;
        if (quantidade == 0) {
            return false;
        }

        let i;
        let delay = typeof time == 'number';
        for (i = 0; i < quantidade; ++i) {
            if (delay) {
                setTimeout(
                    function (parEl, parClass) {
                        parEl.classList.remove(parClass);
                    },
                    time,
                    elemento[i],
                    classe
                );
            } else {
                elemento[i].classList.remove(classe);
            }
        }

        return this;
    }
    static toggleClass(classe) {
        if (classe == '' || classe == undefined) {
            return false;
        }

        let elemento = this._getElAll();

        let quantidade = elemento.length;
        if (quantidade == 0) {
            return false;
        }

        let i;
        let delay = typeof time == 'number';
        for (i = 0; i < quantidade; ++i) {
            if (delay) {
                setTimeout(
                    function (parEl, parClass) {
                        parEl.classList.toggle(parClass);
                    },
                    time,
                    elemento[i],
                    classe
                );
            } else {
                elemento[i].classList.toggle(classe);
            }
        }

        return this;
    }
    static hasClass(classe) {
        if (classe == '' || classe == undefined) {
            return false;
        }

        let elemento = this._getEl();

        if (!elemento) {
            return false;
        }

        return elemento.classList.contains(classe);
    }

    static attr(atributo, valor) {
        let elemento = this._getEl();

        if (!elemento) {
            return false;
        }

        if (atributo != '' && valor == undefined) {
            return elemento.getAttribute(atributo);
        } else if (atributo != '' && (valor == '' || valor == null)) {
            elemento.removeAttribute(atributo);
            return this;
        } else if (atributo != '' && valor != '') {
            elemento.setAttribute(atributo, valor);
            return this;
        }
    }

    static closest(busca) {
        let elemento = this._elemento;
        if (typeof elemento != 'object') {
            elemento = this._getEl();
        }

        if (!elemento) {
            return false;
        }

        return elemento.closest(busca);
    }

    static val(valor) {
        let elemento = this._getEl();

        if (typeof valor == 'undefined') {
            return elemento.value;
        } else {
            elemento.value = valor;
            return true;
        }
    }

    static remove() {
        let elemento = this._elemento;
        if (typeof elemento != 'object') {
            elemento = this._getEl();
        }

        if (!elemento) {
            return false;
        }

        elemento.parentNode.removeChild(elemento);
    }

    static _evento(acao, callback) {
        let elemento = this._getElAll();

        let quantidade = elemento.length;
        if (quantidade == 0) {
            return false;
        }

        let i;
        for (i = 0; i < quantidade; ++i) {
            elemento[i].addEventListener(acao, callback);
        }
    }

    static _getEl() {
        if (typeof this._elemento == 'object') {
            return this._elemento;
        }

        let elemento = this._elemento;
        let pai = this._pai;

        if (pai) {
            return pai.querySelector(elemento);
        } else {
            return document.querySelector(elemento);
        }
    }
    static _getElAll() {
        if (typeof this._elemento == 'object') {
            return [this._elemento];
        }

        let elemento = this._elemento;
        let pai = this._pai;

        if (pai) {
            return pai.querySelectorAll(elemento);
        } else {
            return document.querySelectorAll(elemento);
        }
    }

    static on(acao, target, callback) {
        let elemento = this._getElAll();

        let quantidade = elemento.length;
        if (quantidade == 0) {
            return false;
        }

        let classe = false;
        let id = false;

        if (target.substr(0, 1) == '.') {
            classe = true;
        } else if (target.substr(0, 1) == '#') {
            id = true;
        } else {
            return false;
        }

        target = target.substr(1, target.length);

        let i;
        for (i = 0; i < quantidade; ++i) {
            elemento[i].addEventListener(acao, function (e) {
                if (classe && e.target.classList.contains(target)) {
                    callback(e.target);
                } else if (id && e.target.getAttribute('id') == target) {
                    callback(e.target);
                }
            });
        }
    }

    // mouse
    static click(callback) {
        this._evento('click', callback);
    }
    static dblclick(callback) {
        this._evento('dblclick', callback);
    }
    static mousedown(callback) {
        this._evento('mousedown', callback);
    }
    static mouseup(callback) {
        this._evento('mouseup', callback);
    }
    static mouseover(callback) {
        this._evento('mouseover', callback);
    }
    static mouseout(callback) {
        this._evento('mouseout', callback);
    }
    static mousemove(callback) {
        this._evento('mousemove', callback);
    }

    // teclado
    static keydown(callback) {
        this._evento('keydown', callback);
    }
    static keypress(callback) {
        this._evento('keypress', callback);
    }
    static keyup(callback) {
        this._evento('keyup', callback);
    }
    static input(callback) {
        this._evento('input', callback);
    }

    // inteface
    static load(callback) {
        this._evento('load', callback);
    }
    static unload(callback) {
        this._evento('unload', callback);
    }
    static error(callback) {
        this._evento('error', callback);
    }
    static resize(callback) {
        this._evento('resize', callback);
    }
    static scroll(callback) {
        this._evento('scroll', callback);
    }

    // focus
    static focus(callback) {
        this._evento('focus', callback);
    }
    static blur(callback) {
        this._evento('blur', callback);
    }
    static focusin(callback) {
        this._evento('focusin', callback);
    }
    static focusout(callback) {
        this._evento('focusout', callback);
    }

    // form
    static submit(callback) {
        this._evento('submit', callback);
    }
    static change(callback) {
        this._evento('change', callback);
    }

    // HTML
    static hashchange(callback) {
        this._evento('hashchange', callback);
    }
    static beforeunload(callback) {
        this._evento('beforeunload', callback);
    }

    // CSS
    static transitionend(callback) {
        this._evento('transitionend', callback);
    }
    static animationstart(callback) {
        this._evento('animationstart', callback);
    }
    static animationiteration(callback) {
        this._evento('animationiteration', callback);
    }
    static animationend(callback) {
        this._evento('animationend', callback);
    }

    // mobile
    static swipedright(callback) {
        this._evento('swiped-right', callback);
    }
    static swipedleft(callback) {
        this._evento('swiped-left', callback);
    }
    static swipedup(callback) {
        this._evento('swiped-up', callback);
    }
    static swipeddown(callback) {
        this._evento('swiped-down', callback);
    }
}
