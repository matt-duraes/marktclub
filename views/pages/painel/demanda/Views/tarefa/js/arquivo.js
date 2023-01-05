const loadingArquivo = () => {
    const bodyGeral = document.querySelector('body');
    const blocoConteudo = document.getElementById('bloco_demanda_conteudo');

    const idDemanda = document.getElementById('input_id_demanda').value;
    const blocoArquivo = document.getElementById('bloco_arquivo');
    const blocoArquivoDrop = document.getElementById('bloco_arquivo_drop');
    const blocoArquivoZero = document.getElementById('bloco_arquivo_zero');
    const blocoArquivoLista = document.getElementById('bloco_arquivo_lista');
    const botaoArquivoFazerUpload = document.getElementById('botao_arquivo_fazer_upload');
    const botaoArquivoMostrarMais = document.getElementById('botao_arquivo_mostar_mais');
    const hashArquivoUpload = document.querySelector('#bloco_arquivo input[name=form_system_hash]').value;
    const blocoArquivoRenomear = document.getElementById('bloco_demanda_detalhe_renomar');
    const inputArquivoId = document.getElementById('input_arquivo_id');
    const inputArquivoRenomear = document.getElementById('input_arquivo_renomear');
    const botaoArquivoRenomearCancelar = document.getElementById('botao_arquivo_renomear_cancelar');
    const botaoArquivoRenomearConfirmar = document.getElementById('botao_arquivo_renomear_confirmar');
    const hashArquivoRenomear = document.querySelector(
        '#bloco_demanda_detalhe_renomar input[name=form_system_hash]'
    ).value;

    /*
    |--------------------------------------------------------------------------
    | DROP AND DRAG DE ARQUIVO
    |--------------------------------------------------------------------------
    */
    blocoConteudo.addEventListener('dragover', e => {
        e.preventDefault();
        blocoArquivoDrop.classList.add('drag');
    });
    bodyGeral.addEventListener('dragover', e => {
        if (
            e.target.getAttribute('id') != 'bloco_demanda_conteudo' &&
            !e.target.closest('#bloco_demanda_conteudo') &&
            blocoArquivoDrop.classList.contains('drag')
        ) {
            arquivoDropFechar();
        }
    });
    blocoConteudo.addEventListener('drop', e => {
        e.preventDefault();
        arquivoDropFechar();
    });
    blocoArquivo.addEventListener('drop', e => {
        e.preventDefault();
        arquivoDropFechar();

        if (!e.dataTransfer.items && !e.dataTransfer.files) {
            Alerta.notificacao('O navegador não tem suporte a arrastar arquivos.', false);
            return;
        }

        let quantidade;
        if (e.dataTransfer.items) {
            quantidade = e.dataTransfer.items.length;
            for (i = 0; i < quantidade; ++i) {
                fazerUploadDoArquico(e.dataTransfer.items[i].getAsFile());
            }
        } else {
            quantidade = e.dataTransfer.files.length;
            for (i = 0; i < quantidade; ++i) {
                fazerUploadDoArquico(e.dataTransfer.files[i]);
            }
        }
    });
    bodyGeral.addEventListener('click', e => {
        arquivoDropFechar();
    });
    const arquivoDropFechar = () => {
        if (!blocoArquivoDrop.classList.contains('drag')) {
            return;
        }
        blocoArquivoDrop.classList.remove('drag');
    };

    /*
    |--------------------------------------------------------------------------
    | UPLOAD PELO BOTÃO
    |--------------------------------------------------------------------------
    */
    botaoArquivoFazerUpload.addEventListener('change', () => {
        const arquivo = botaoArquivoFazerUpload.files;
        const quantidade = arquivo.length;
        if (quantidade == 0) {
            return;
        }
        let i;
        for (i = 0; i < quantidade; ++i) {
            fazerUploadDoArquico(arquivo[i]);
        }
        botaoArquivoFazerUpload.value = '';
    });

    /*
    |--------------------------------------------------------------------------
    | FAZ UPLOAD DO ARQUIVO
    |--------------------------------------------------------------------------
    */
    let numeroArquivoFake = 0;
    const fazerUploadDoArquico = async arquivo => {
        blocoArquivoZero.classList.add('hide');

        numeroArquivoFake++;
        const id = 'id_arquivo_fake_' + numeroArquivoFake;
        blocoArquivoLista.insertAdjacentHTML(
            'afterbegin',
            `<div class="arquivo fake arquivo_novo" id="` + id + `"></div>`
        );
        const blocoFake = document.getElementById(id);

        const body = new FormData();
        body.append('demanda', idDemanda);
        body.append('form_system_hash', hashArquivoUpload);
        body.append('form_system_validacao', '');
        body.append('arquivo', arquivo);

        const response = await fetch(LINK + '/demanda/upload', {
            method: 'POST',
            body: body,
        });

        let json;
        try {
            json = await response.json();
        } catch (error) {
            json = { mensagem: 'Ocorreu um erro ao fazer o upload do arquivo.' };
        }

        if (response.status != 201 || json.html == undefined) {
            const mensagem = json.mensagem ? json.mensagem : 'Ocorreu um erro ao fazer o upload do arquivo.';
            Alerta.notificacao(mensagem, false);
            blocoFake.parentNode.removeChild(blocoFake);
            acaoAposMudarNumeroArquivos();
            return;
        }

        blocoFake.classList.remove('fake');
        blocoFake.insertAdjacentHTML('afterbegin', json.html);
        blocoFake.setAttribute('id', 'arquivo_' + json.id);
        adicionarAcoesAoArquivo(blocoFake, json.id, json.nome);
        adicionarNovaMensagem(json.mensagem);
        acaoAposMudarNumeroArquivos();
    };

    const acaoAposMudarNumeroArquivos = () => {
        const quantidade = blocoArquivoLista.querySelectorAll('.arquivo').length;
        if (quantidade == 0) {
            blocoArquivoZero.classList.remove('hide');
        } else {
            blocoArquivoZero.classList.add('hide');
        }
        if (quantidade > 4 && !botaoArquivoMostrarMais.classList.contains('clicado')) {
            blocoArquivo.classList.add('fechado');
        } else {
            blocoArquivo.classList.remove('fechado');
        }
    };
    acaoAposMudarNumeroArquivos();

    const adicionarAcoesAoArquivo = (bloco, id, nome) => {
        const deletar = bloco.querySelector('.icone.deletar');
        deletar.addEventListener('click', () => {
            deletarArquivo(id);
        });

        const editar = bloco.querySelector('.icone.editar');
        if (editar) {
            editar.addEventListener('click', () => {
                abrirEditarNomeArquivo(id, nome);
            });
        }
    };

    /*
    |--------------------------------------------------------------------------
    | ACAO AO CARREGAR PÁGINA
    |--------------------------------------------------------------------------
    */
    const blocoArquivoInicial = blocoArquivoLista.querySelectorAll('.arquivo');
    if (blocoArquivoInicial.length > 0) {
        let id, nome, blocoNome;
        blocoArquivoInicial.forEach(bloco => {
            id = bloco.getAttribute('data-id');
            blocoNome = bloco.querySelector('.nome');
            if (blocoNome) {
                nome = blocoNome.innerText;
            }
            bloco.removeAttribute('data-id');
            adicionarAcoesAoArquivo(bloco, id, nome);
        });
    }

    /*
    |--------------------------------------------------------------------------
    | DELETAR ARQUIVO
    |--------------------------------------------------------------------------
    */
    const deletarArquivo = async id => {
        if (
            await Alerta.confirmar(
                'Deletar arquivo!',
                'Tem certeza que deseja deletar esse arquivo? Essa ação não poderá ser desfeita.',
                '!'
            )
        ) {
            Loading.show();
            const response = await fetch(LINK + '/demanda/arquivo/' + id, { method: 'DELETE' });
            const json = await response.json();

            Loading.hide();
            if (response.status == 201) {
                const blocoArquivo = blocoArquivoLista.querySelector('#arquivo_' + id);
                if (blocoArquivo) {
                    blocoArquivo.parentNode.removeChild(blocoArquivo);
                }
                adicionarNovaMensagem(json.mensagem);
                acaoAposMudarNumeroArquivos();
                return;
            }

            const mensagem = json.mensagem != undefined ? json.mensagem : 'Erro ao deletar o arquivo.';
            Alerta.notificacao(mensagem, false);
        }
    };

    /*
    |--------------------------------------------------------------------------
    | RENOMEAR ARQUIVO
    |--------------------------------------------------------------------------
    */
    const abrirEditarNomeArquivo = (id, nome) => {
        blocoArquivoRenomear.style.display = 'flex';
        setTimeout(() => {
            blocoArquivoRenomear.classList.add('animar');
        }, 20);
        inputArquivoId.value = id;
        inputArquivoRenomear.value = nome;
        setTimeout(() => {
            inputArquivoRenomear.focus();
        }, 320);
    };
    const fecharEditarNomeArquivo = () => {
        blocoArquivoRenomear.classList.remove('animar');
        setTimeout(() => {
            blocoArquivoRenomear.style.display = 'none';
            inputArquivoId.value = '';
            inputArquivoRenomear.value = '';
        }, 300);
    };
    botaoArquivoRenomearCancelar.addEventListener('click', () => {
        fecharEditarNomeArquivo();
    });

    inputArquivoRenomear.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            salvarNovoNome();
        }
    });
    botaoArquivoRenomearConfirmar.addEventListener('click', () => {
        salvarNovoNome();
    });
    const salvarNovoNome = async () => {
        if (botaoArquivoRenomearConfirmar.classList.contains('aguarde')) {
            return;
        }
        botaoArquivoRenomearConfirmar.classList.add('aguarde');

        const valor = inputArquivoRenomear.value.trim();
        const id = inputArquivoId.value;

        if (valor == '') {
            Alerta.notificacao('Digite um nome para o arquivo.', false);
            return;
        } else if (valor.length > 150) {
            Alerta.notificacao('O nome do arquivo deve ter no máximo 150 caracteres.', false);
            return;
        }

        const body = new FormData();
        body.append('id', id);
        body.append('nome', valor);
        body.append('form_system_hash', hashArquivoRenomear);
        body.append('form_system_validacao', '');

        const response = await fetch(LINK + '/demanda/arquivo', {
            method: 'PUT',
            body,
        });

        const json = await response.json();
        botaoArquivoRenomearConfirmar.classList.remove('aguarde');

        if (response.status == 201) {
            fecharEditarNomeArquivo();
            const blocoNome = blocoArquivoLista.querySelector('#arquivo_' + id + ' .nome');
            if (blocoNome) {
                blocoNome.innerText = valor;
            }
            adicionarNovaMensagem(json.mensagem);
            return;
        }

        const mensagem = json.mensagem != undefined ? json.mensagem : 'Erro ao alterar o nome do arquivo.';
        Alerta.notificacao(mensagem, false);
    };

    /*
    |--------------------------------------------------------------------------
    | BOTÃO DE MOSTRAR MAIS
    |--------------------------------------------------------------------------
    */
    botaoArquivoMostrarMais.addEventListener('click', () => {
        blocoArquivo.classList.remove('fechado');
        botaoArquivoMostrarMais.classList.add('clicado');
    });
};
