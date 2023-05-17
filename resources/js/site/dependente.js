window.addEventListener('load', () => {
    const botaoAdicionarDependente = document.querySelector('.adicionaDependente');

    if (botaoAdicionarDependente) {
        botaoAdicionarDependente.addEventListener('click', e => {
            e.preventDefault();

            const blocoDefault = document.querySelector('.dependente .linha_dependente.default');
            const novoBloco = document.querySelector('.dependente .linha_dependente');
            const clone = blocoDefault.cloneNode(true);
            clone.classList.remove('default');
            clone.classList.add('normal');

            novoBloco.parentNode.insertBefore(clone, novoBloco.nextSibling);
        });
    }
});
