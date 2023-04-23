// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Galeria"
// @system "Calendario"
// @system "Mascara"

window.onload = function() {
    const botao = document.querySelectorAll('.bloco_botao_plano button');
    botao.forEach(botao => {
        botao.addEventListener('click', (event => {
            const clicado = event.target;
            abrirModal(clicado);
        }));
    });
};

function abrirModal(clicado) {
    const LINK = document.getElementById('LINK').value;
    const botaoAuxilio = {};
    const id = clicado.getAttribute('data-id');
    const local = clicado.getAttribute('data-local');
    const url = `${LINK}/saude/abrir-tabela-preco?id=${id}&local=${local}`;
    botaoAuxilio[id] = new Pagina('Preços Plano de Saúde -'+ local, url);
    botaoAuxilio[id].abrir();

}

