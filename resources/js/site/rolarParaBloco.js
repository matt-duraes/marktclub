const rolarParaBloco = (elementoId, tamanho) => {
    var bloco = document.getElementById(elementoId);
    var deslocamento = bloco.offsetTop - tamanho;
    window.scrollTo({
        top: deslocamento,
        behavior: 'smooth',
    });
};
