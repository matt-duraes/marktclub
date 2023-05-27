function Accordion() {
    var items = document.querySelectorAll('.accordion_item');

    items.forEach(function (item) {
        var titulo = item.querySelector('.accordion_titulo');
        titulo.addEventListener('click', function () {
            toggleItem(item);
        });
    });

    function toggleItem(item) {
        var conteudo = item.querySelector('.accordion_conteudo');
        var seAberto = conteudo.classList.contains('abrir');

        fechaTodosItems();

        if (!seAberto) {
            conteudo.classList.add('abrir');
            item.classList.toggle('abrir');
        }
    }

    function fechaTodosItems() {
        items.forEach(function (item) {
            item.querySelector('.accordion_conteudo').classList.remove('abrir');
            item.classList.remove('abrir');
        });
    }
}
function criarAccordion() {
    var accordion = new Accordion();
}
