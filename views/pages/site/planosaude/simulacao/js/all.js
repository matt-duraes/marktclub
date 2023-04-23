// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/passo_passo"

const OPERADORA = document.querySelector('#operadora').value;
if (OPERADORA === 'unimed-vitoria') {
    // @resource "site/saude/unimedvitoria/simulacao"
} else if (OPERADORA === 'unimed-florianopolis') {
    // @resource "site/saude/unimedflorianopolis/simulacao"
} else if (OPERADORA === 'amil') {
    // @resource "site/saude/amil/simulacao"
} else if (OPERADORA === 'unimed-seguro') {
    // @resource "site/saude/unimedSeguro/simulacao"
}

const botaoAdicionaDependente = document.querySelector('.adicionaDependente');

if (botaoAdicionaDependente) {
    botaoAdicionaDependente.addEventListener('click', function (e) {
        e.preventDefault();

        let blocoDefault = document.querySelector('.dependente .linha_dependente.default');
        let novoBloco = document.querySelector('.dependente .linha_dependente');
        let clone = blocoDefault.cloneNode(true);
        let cloneSemDefault = clone.classList.remove('default');

        novoBloco.parentNode.insertBefore(clone, novoBloco.nextSibling);
    });
}

const parent = document.querySelector('body');
parent.addEventListener('click', function (event) {
    if (event.target.classList.contains('remove')) {
        const linhaDependente = event.target.closest('.linha_dependente');
        linhaDependente.remove();
    }
});
