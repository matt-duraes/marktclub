// @template "painel"
const bloco = document.getElementById('bloco_album_index');
const blocoLista = document.getElementById('bloco_lista');
const listaCheck = bloco.querySelectorAll('.bloco_lista article .imagem .check');

const botaoEditar = document.querySelector('#botao_editar_album');
const botaoDeletar = document.querySelector('#botao_deletar_album');

const hashDeletar = bloco.querySelector('input[name=form_system_hash]').value;

if (listaCheck) {
    let article, id;
    listaCheck.forEach(check => {
        article = check.closest('article');
        check.addEventListener('click', () => {
            id = article.getAttribute('data-id');
            marcarOuDesmarcarAlbum(article, id);
        });
    });
}

const marcarOuDesmarcarAlbum = (article, id) => {
    article.classList.toggle('arquivo_checked');
    const quantidade = bloco.querySelectorAll('.bloco_lista article.arquivo_checked').length;

    if (quantidade != 1 && botaoEditar) {
        botaoEditar.style.display = 'none';
        botaoEditar.removeAttribute('href');
    } else if (quantidade == 1 && botaoEditar) {
        botaoEditar.style.display = 'flex';
        botaoEditar.setAttribute('href', LINK + '/app/editar/album/' + id);
    }
    if (quantidade == 0 && botaoDeletar) {
        botaoDeletar.style.display = 'none';
    } else if (quantidade > 0 && botaoDeletar) {
        botaoDeletar.style.display = 'flex';
    }
};

if (botaoDeletar) {
    botaoDeletar.addEventListener('click', async () => {
        const lista = bloco.querySelectorAll('.bloco_lista article.arquivo_checked');
        const quantidade = lista.length;
        if (quantidade <= 0) {
            return;
        }

        if (await Alerta.confirmar('Deletar álbuns!', 'Tem certeza que deseja deletar os álbuns selecionados?', '!')) {
            enviarAlbunsSelecionadosParaDeletar(lista);
        }
    });
}
const enviarAlbunsSelecionadosParaDeletar = async lista => {
    Loading.show();
    const body = new FormData();
    body.append('form_system_hash', hashDeletar);
    body.append('form_system_validacao', '');
    lista.forEach(album => {
        body.append('id[]', album.getAttribute('data-id'));
    });
    const response = await fetch(LINK + '/album/deletar', {
        method: 'POST',
        body,
    });
    Loading.hide();
    if (response.status == 204) {
        Alerta.notificacao('Álbuns deletado com sucesso!', true);
        return removerAlbunsSelecionados(lista);
    }
    let json;
    try {
        json = await response.json();
    } catch (error) {
        json = {};
    }
    Alerta.notificacao(
        json.mensagem != undefined ? json.mensagem : 'Erro ao deletar os álbuns, por favor, tente novamente.',
        false
    );
};
const removerAlbunsSelecionados = lista => {
    lista.forEach(album => {
        album.parentNode.removeChild(album);
    });
    verificarSeAindaExisteAlbum();
};
const verificarSeAindaExisteAlbum = () => {
    const quantidade = bloco.querySelectorAll('.bloco_lista article').length;
    if (quantidade > 0) {
        return;
    }
    blocoLista.insertAdjacentHTML(
        'beforeend',
        `
            <div class="zero">
                <i><svg height="50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 30" style="enable-background:new 0 0 40 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path class="st0" d="M2.7,952.4c-1.5,0-2.7,1.2-2.7,2.6v24.7c0,1.5,1.2,2.6,2.7,2.6h34.7c1.5,0,2.7-1.2,2.7-2.6V955 c0-1.5-1.2-2.6-2.7-2.6H2.7z M2.7,954.1h34.7c0.5,0,0.9,0.4,0.9,0.9v18.5l-7.4-5.9c-0.3-0.2-0.7-0.3-1.1,0l-6.6,4.5l-8.8-7.1 c-0.2-0.1-0.4-0.2-0.7-0.2c-0.1,0-0.3,0.1-0.4,0.2l-11.5,7.9V955C1.8,954.5,2.2,954.1,2.7,954.1L2.7,954.1z M23.1,958.5 c-2,0-3.6,1.6-3.6,3.5s1.6,3.5,3.6,3.5s3.6-1.6,3.6-3.5S25.1,958.5,23.1,958.5z M23.1,960.3c1,0,1.8,0.8,1.8,1.8 c0,1-0.8,1.8-1.8,1.8c-1,0-1.8-0.8-1.8-1.8C21.3,961.1,22.1,960.3,23.1,960.3z M13.7,966.7l8.8,7.1c0.3,0.2,0.7,0.3,1.1,0l6.6-4.5 l8.1,6.4v4c0,0.5-0.4,0.9-0.9,0.9H2.7c-0.5,0-0.9-0.4-0.9-0.9v-4.8L13.7,966.7L13.7,966.7z"/></g></svg></i>
                <h1>Sem álbuns no momento</h1>
                <p>Crie um novo álbum para adicionar suas imagens</p>
            </div>
        `
    );
};
