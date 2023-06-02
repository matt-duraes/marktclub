window.addEventListener('load', () => {
    const blocoFavorito = document.querySelectorAll('.bloco_favorito');

    blocoFavorito.forEach(loja => {
        loja.addEventListener('click', () => {
            const lojaFavorita = loja.getAttribute('data-url');
            favoritar(loja, lojaFavorita);
        });
    });

    function favoritar(loja, lojaFavorita) {
        const svg = loja.querySelector('svg'); // Alteração: buscar o elemento svg dentro de cada bloco_favorito
        const uuid = lojaFavorita;

        if (svg.classList.contains('favorito_marcado') == false) {
            svg.classList.add('favorito_marcado');
            const acao = '1';
            enviarDados(uuid, acao);
        } else {
            svg.classList.remove('favorito_marcado');
            const acao = '0';
            enviarDados(uuid, acao);
        }
    }

    async function enviarDados(acao, uuid) {
        const body = new FormData();
        body.append('uuid', uuid);
        body.append('acao', acao);
        const response = await fetch('/convenios/favorito', {
            body: body,
            method: 'POST',
        });

        if (response.status == 204) {
            return;
        }
    }
});
