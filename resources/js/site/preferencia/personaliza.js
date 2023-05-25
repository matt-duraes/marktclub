window.addEventListener('load', () => {
    const botaoEnviaListaTag = document.getElementById('botao_envia_lista_tag');
    const blocoParceiro = document.getElementById('bloco_tag_parceiro');
    const blocoListaTag = document.getElementById('bloco_tag_lista');

    if (botaoEnviaListaTag) {
        botaoEnviaListaTag.addEventListener('click', function (e) {
            e.preventDefault();

            blocoListaTag.style.display = 'none';
            blocoParceiro.classList.remove('display_none');
            scrollToTop();
        });
    }

    function scrollToTop() {
        var scrollTo = blocoParceiro.offsetTop;
        window.scrollTo({ top: scrollTo, behavior: 'smooth' });
    }
});
