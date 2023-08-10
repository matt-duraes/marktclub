carregarAlbumEditar = () => {
    const form = document.getElementById('bloco_editar_imagem');
    const botaoFechar = form.querySelectorAll('.botao_fechar_popup');
    const botaoEditar = form.querySelector('#botao_editar_imagem');

    const id = form.querySelector('input[name=id]').value;
    const album = form.querySelector('input[name=album]').value;
    const hash = form.querySelector('input[name=form_system_hash]').value;
    const inputTitulo = form.querySelector('input[name=titulo]');
    const inputCapa = form.querySelector('input[name=capa]');

    botaoFechar.forEach(botao => {
        botao.addEventListener('click', () => {
            Pagina.staticFechar();
        });
    });

    botaoEditar.addEventListener('click', async () => {
        if (botaoEditar.classList.contains('aguarde')) {
            return;
        }
        botaoEditar.classList.add('aguarde');

        const titulo = inputTitulo.value;
        const body = new FormData();
        body.append('id', id);
        body.append('album', album);
        body.append('titulo', titulo);
        body.append('capa', inputCapa.checked ? 1 : 0);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/album/galeria-editar', {
            body,
            method: 'PUT',
        });

        botaoEditar.classList.remove('aguarde');
        if (response.status == 204) {
            Pagina.staticFechar();
            Alerta.notificacao('Imagem editada com sucesso!', true);
            mudarTituloDaImagem(id, titulo);
            return;
        }
        fetchNotificacaoErro(response, 'Ocorreu um erro ao editar a imagem.');
    });

    const mudarTituloDaImagem = (id, titulo) => {
        const bloco = document.querySelector(`#bloco_arquivo_lista article[data-id=${id}]`);
        if (!bloco) {
            return;
        }
        bloco.querySelector('p').innerText = titulo;
    };
};
