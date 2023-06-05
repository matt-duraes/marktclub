window.addEventListener('load', () => {
    const blocoFavorito = document.querySelectorAll('.bloco_favorito');
    const favoritar = loja => {
        let uuid = loja.getAttribute('data-url');
        let svg = loja.querySelector('i');

        svg.classList.toggle('favorito_marcado');
        let acao = svg.classList.contains('favorito_marcado') ? '1' : '0';

        enviarDados(uuid, acao);
    };

    blocoFavorito.forEach(loja => {
        loja.addEventListener('click', () => {
            favoritar(loja);
        });
    });

    async function enviarDados(uuid, acao) {
        const body = new FormData();
        body.append('uuid', uuid);
        body.append('acao', acao);
        const response = await fetch('/convenios/favorito', {
            method: 'POST',
            body: body,
        });

        if (response.status === 204) {
            return;
        }
    }
});
