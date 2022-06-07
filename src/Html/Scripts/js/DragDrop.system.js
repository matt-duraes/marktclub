class DragDrop {
    constructor() {
        this._bloco;
        this._item;
        this._botao;
        this._grupo;
        this._eventoInicio;
        this._eventoFim;
        this._eventoAdd;
        this._eventoRemover;
        this._eventoMover;
        this._eventoClonar;
        this._eventoAlterar;
    }

    /**
     * Bloco onde ficara a lista
     *
     * @param {string} bloco Elemento onde ficaram os item que serão arrastado
     */
    bloco(bloco) {
        this._bloco = bloco;
        return this;
    }

    /**
     * Item a serem arrastados (opcional)
     *
     * @param {string} item Selector css
     */
    item(item) {
        this._item = item;
        return this;
    }

    /**
     * Botão que será usado para arrastar  (opcional)
     *
     * @param {string} botao Seletor css
     */
    botao(botao) {
        this._botao = botao;
        return this;
    }

    /**
     * Grupo para o bloco (opcional)
     *
     * @param {string|object} grupo String ou objecto
     */
    grupo(grupo) {
        this._grupo = grupo;
        return this;
    }

    /**
     * Evento para quando começar a arrastar
     *
     * @param {func} evento Função
     */
    eventoInicio(evento) {
        this._eventoInicio = evento;
        return this;
    }

    /**
     * Evento para quando terminar o arrastar (opcional)
     *
     * @param {func} evento Função
     */
    eventoFim(evento) {
        this._eventoFim = evento;
        return this;
    }

    /**
     * Evento para quando adicionar item em um grupo (opcional)
     *
     * @param {func} evento Função
     */
    eventoAdd(evento) {
        this._eventoAdd = evento;
        return this;
    }

    /**
     * Evento para quando remover um item de um grupo (opcional)
     *
     * @param {func} evento Função
     */
    eventoRemover(evento) {
        this._eventoRemover = evento;
        return this;
    }

    /**
     * Evento para quando mover um item (opcional)
     *
     * @param {func} evento Função
     */
    eventoMover(evento) {
        this._eventoMover = evento;
        return this;
    }

    /**
     * Evento para quando clonar um item (opcional)
     *
     * @param {func} evento Função
     */
    eventoClonar(evento) {
        this._eventoClonar = evento;
        return this;
    }

    /**
     * Evento para quando acontecer uma alteração em um item (opcional)
     *
     * @param {func} evento Função
     */
    eventoAlterar(evento) {
        this._eventoAlterar = evento;
        return this;
    }

    /**
     * Inicia o DragDrop
     */
    iniciar() {
        new Sortable(this._bloco, this._montarOption());
    }

    _montarOption() {
        let option = {
            animation: 150,
            chosenClass: 'drag_drop_fantasma',
        };
        if (!this._vazio(this._item)) {
            option.draggable = this._item;
        }
        if (!this._vazio(this._botao)) {
            option.handle = this._botao;
        }
        if (!this._vazio(this._grupo)) {
            option.group = this._grupo;
        }
        if (!this._vazio(this._eventoInicio)) {
            option.onStart = this._eventoInicio;
        }
        if (!this._vazio(this._eventoFim)) {
            option.onEnd = this._eventoFim;
        }
        if (!this._vazio(this._eventoAdd)) {
            option.onAdd = this._eventoAdd;
        }
        if (!this._vazio(this._eventoRemover)) {
            option.onRemove = this._eventoRemover;
        }
        if (!this._vazio(this._eventoMover)) {
            option.onMove = this._eventoMover;
        }
        if (!this._vazio(this._eventoClone)) {
            option.onClone = this._eventoClone;
        }
        if (!this._vazio(this._eventoChange)) {
            option.onChange = this._eventoChange;
        }
        return option;
    }
    _vazio(item) {
        if (item == '' || item == null || item == undefined) {
            return true;
        }
        return false;
    }
}
