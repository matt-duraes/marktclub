function Accordion() {
    this.items = document.querySelectorAll('.accordion_item');
    this.items.forEach(function (item) {
        var titulo = item.querySelector('.accordion_titulo');
        titulo.addEventListener('click', this.toggleItem.bind(this, item));
    }, this);
}

Accordion.prototype.toggleItem = function (item) {
    var conteudo = item.querySelector('.accordion_conteudo');
    var seAberto = conteudo.classList.contains('abrir');

    this.fechaTodosItems();

    if (!seAberto) {
        conteudo.classList.add('abrir');
        item.classList.toggle('abrir');
    }
};

Accordion.prototype.fechaTodosItems = function () {
    this.items.forEach(function (item) {
        item.querySelector('.accordion_conteudo').classList.remove('abrir');
        item.classList.remove('abrir');
    });
};
var accordion = new Accordion();
