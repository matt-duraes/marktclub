// @template "painel"

window.addEventListener('load', () => {
    const blocoAlbum = $('#bloco_linha_album');
    const blocoLista = $('#bloco_foto_lista');
    const albumId = $('#input_visualizar_id').value;
    const botaoAbrir = $('#botao_foto_abrir');
    const botaoFechar = $('#botao_foto_fechar');
    const botaoSalvar = $('#botao_foto_salvar');

    const blocoAdd = $('#bloco_foto_add');

    const inputTitulo = $('#input_foto_titulo');
    const inputImagem = $('#input_imagem');
    const icone = $('.fw_imagem_icone');
    const figure = $('.fw_imagem_figure');

    const blocoImagem = $('#bloco_imagem');
    const inputStatus = $('#input_foto_status');

    let idFoto = '';

    botaoAbrir.addEventListener('click', () => {
        idFoto = '';
        abrirBloco();
    });
    botaoFechar.addEventListener('click', () => {
        fecharBloco();
    });
    blocoAdd.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_foto_add') {
            fecharBloco();
        }
    });
    blocoLista.addEventListener('click', e => {
        if (e.target.classList.contains('botao_editar') || e.target.closest('.botao_editar')) {
            editarFoto(e.target.closest('.linha_dado'));
        } else if (e.target.classList.contains('botao_deletar') || e.target.closest('.botao_deletar')) {
            confirmarDeletarFoto(e.target.closest('.linha_dado'));
        }
    });
    const confirmarDeletarFoto = async linha => {
        if (
            await Alerta.confirmar(
                'Deletar foto',
                'Tem certeza que deseja deletar essa foto? Essa ação não poderá ser desfeita.',
                '!',
            )
        ) {
            deletarFoto(linha);
        }
    };
    const deletarFoto = async linha => {
        const id = linha.getAttribute('data-id');
        const resposta = await ajaxPost(LINK + '/app/ajax/album-dado', {
            indice: 'foto-deletar',
            id,
        });
        if (false == resposta) {
            return;
        }
        Alerta.notificacao('Foto deletada com sucesso.', true);
        linha.parentNode.removeChild(linha);
    };
    const editarFoto = async linha => {
        const id = linha.getAttribute('data-id');
        const resposta = await ajaxPost(LINK + '/app/ajax/album-dado', {
            indice: 'foto-buscar',
            id: id,
        });
        if (false == resposta) {
            Alerta.notificacao('Erro ao buscar dados da foto, por favor, tente novamente.', false);
            return;
        }
        if (resposta.dado.imagem != '') {
            icone.classList.add('display_none');
            figure.style.backgroundImage = 'url(' + resposta.dado.imagem + ')';
        }
        formValue(inputTitulo, resposta.dado.titulo);
        formValue(inputImagem, resposta.dado.imagem);
        inputStatus.checked = resposta.dado.status == 'ativo';
        idFoto = id;
        abrirBloco();
    };
    const abrirBloco = () => {
        blocoAdd.classList.remove('display_none');
        setTimeout(() => {
            blocoAdd.classList.add('ativo');
        }, 30);
    };
    const fecharBloco = () => {
        blocoAdd.classList.remove('ativo');
        setTimeout(() => {
            blocoAdd.classList.add('display_none');
            formValue(inputTitulo, '');
            figure.style.backgroundImage = '';
            icone.classList.remove('display_none');
            inputStatus.checked = false;
        }, 300);
    };

    /*
    |--------------------------------------------------------------------------
    | SALVAR NOVA VERSAO
    |--------------------------------------------------------------------------
    */
    botaoSalvar.addEventListener('click', () => {
        salvarNovaFoto();
    });
    const salvarNovaFoto = async () => {
        const titulo = inputTitulo.value;
        const status = inputStatus.checked ? 'ativo' : 'inativo';
        const imagem = inputImagem.value;

        if (titulo == '') {
            Alerta.notificacao('O campo título é obrigatório.', false);
            return;
        }

        const acao = idFoto == '' ? 'foto-salvar' : 'foto-atualizar';
        const request = {
            indice: acao,
            titulo,
            imagem,
            status,
        };
        if (idFoto != '') {
            request.id = idFoto;
        } else {
            request.album = albumId;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/album-dado',
            request,
            'Erro ao salvar foto, por favor, tente novamente.',
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        if (idFoto == '') {
            Alerta.notificacao('Foto salva com sucesso!', true);
            fecharBloco();
            adicionarNovoBloco(resposta.dado.id, resposta.dado.titulo);
            return;
        }
        Alerta.notificacao('Foto atualizada com sucesso!', true);
        atualizarBlocoExistente(idFoto, titulo);
        fecharBloco();
    };
    const adicionarNovoBloco = (id, titulo) => {
        const clone = blocoModelo.cloneNode(true);
        clone.setAttribute('data-id', id);
        clone.querySelector('.linha').innerText = titulo;
        blocoLista.appendChild(clone);
    };
    const atualizarBlocoExistente = (id, titulo) => {
        const linha = $('#bloco_foto_lista .foto[data-id="' + id + '"] .linha');
        if (!linha) {
            return;
        }
        linha.innerText = titulo;
    };
});
