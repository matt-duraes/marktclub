window.addEventListener('load', function () {
    const listaCodigo = document.querySelectorAll('.codigo_geral');
    if (listaCodigo.length > 0) {
        listaCodigo.forEach(bloco => {
            let blocoCode = bloco.querySelector('code');
            if (blocoCode) {
                hljs.highlightElement(blocoCode);
            }

            let botaoCopiar = bloco.querySelector('.botao_copiar');
            if (botaoCopiar) {
                botaoCopiar.addEventListener('click', () => {
                    navigator.clipboard.writeText(blocoCode.innerText);
                    Alerta.notificacao('Código copiado com sucesso!', true);
                });
            }
        });
    }
});
