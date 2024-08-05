// @template "painel"

window.addEventListener('load', () => {
    const blocoModelo = $('#bloco_linha_modelo');
    const blocoLista = $('#bloco_versao_lista');
    const modeloId = $('#input_visualizar_id').value;
    const botaoAbrir = $('#botao_versao_abrir');
    const botaoFechar = $('#botao_versao_fechar');
    const botaoSalvar = $('#botao_versao_salvar');

    const blocoAdd = $('#bloco_versao_add');

    const inputTitulo = $('#input_versao_titulo');
    const inputImagem = $('#input_imagem');
    const icone = $('.fw_imagem_icone ');
    const figure = $('.fw_imagem_figure');
    const blocoImagem = $('#bloco_imagem');
    const inputCor = $('#input_versao_cor');
    const inputValorDe = $('#input_versao_valor_de');
    const inputValorPor = $('#input_versao_valor_por');
    const inputStatus = $('#input_versao_status');

    let idVersao = '';

    botaoAbrir.addEventListener('click', () => {
        idVersao = '';
        abrirBloco();
    });
    botaoFechar.addEventListener('click', () => {
        fecharBloco();
    });
    blocoAdd.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_versao_add') {
            fecharBloco();
        }
    });
    blocoLista.addEventListener('click', e => {
        if (e.target.classList.contains('botao_editar') || e.target.closest('.botao_editar')) {
            editarVersao(e.target.closest('.linha_dado'));
        } else if (e.target.classList.contains('botao_deletar') || e.target.closest('.botao_deletar')) {
            confirmarDeletarVersao(e.target.closest('.linha_dado'));
        }
    });
    const confirmarDeletarVersao = async linha => {
        if (
            await Alerta.confirmar(
                'Deletar versão',
                'Tem certeza que deseja deletar essa versão? Essa ação não poderá ser desfeita.',
                '!'
            )
        ) {
            deletarVersao(linha);
        }
    };
    const deletarVersao = async linha => {
        const id = linha.getAttribute('data-id');
        const resposta = await ajaxPost(LINK + '/app/ajax/parceiro-automovel', {
            indice: 'versao-deletar',
            id,
        });
        if (false == resposta) {
            return;
        }
        Alerta.notificacao('Versão deletada com sucesso.', true);
        linha.parentNode.removeChild(linha);
    };
    const editarVersao = async linha => {
        const id = linha.getAttribute('data-id');
        const resposta = await ajaxPost(LINK + '/app/ajax/parceiro-automovel', {
            indice: 'versao-buscar',
            id: id,
        });
        if (false == resposta) {
            Alerta.notificacao('Erro ao buscar dados da versão, por favor, tente novamente.', false);
            return;
        }
        if (resposta.dado.imagemUrl != '') {
            icone.classList.add('display_none');
            figure.style.backgroundImage = 'url(' + resposta.dado.imagemUrl + ')';
        }
        formValue(inputTitulo, resposta.dado.titulo);
        formValue(inputImagem, resposta.dado.imagem);
        formValue(inputCor, resposta.dado.cor);
        formValue(inputValorDe, valorBr(resposta.dado.valor_de));
        formValue(inputValorPor, valorBr(resposta.dado.valor_por));
        inputStatus.checked = resposta.dado.status == 'ativo';
        idVersao = id;
        abrirBloco();
    };
    const valorBr = valor => {
        if (valor == '' || valor == undefined) {
            return '';
        }
        return parseFloat(valor)
            .toFixed(2) // casas decimais
            .replace('.', ',')
            .replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1.');
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
            formValue(inputCor, '');
            formValue(inputValorDe, '');
            formValue(inputValorPor, '');
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
        salvarNovaVersao();
    });
    const salvarNovaVersao = async () => {
        const titulo = inputTitulo.value;
        const cor = inputCor.value;
        const valorDe = inputValorDe.value.replace(/\./g, '').replace(',', '.');
        const valorPor = inputValorPor.value.replace(/\./g, '').replace(',', '.');
        const status = inputStatus.checked ? 'ativo' : 'inativo';
        const imagem = inputImagem.value;

        if (titulo == '') {
            Alerta.notificacao('O campo título é obrigatório.', false);
            return;
        } else if (valorPor == '') {
            Alerta.notificacao('O campo valor por é obrigatório.', false);
            return;
        }

        const acao = idVersao == '' ? 'versao-salvar' : 'versao-atualizar';
        const request = {
            indice: acao,
            titulo,
            imagem,
            cor,
            /* eslint-disable */
            valor_de: valorDe,
            valor_por: valorPor,
            /* eslint-enable */
            status,
        };
        if (idVersao != '') {
            request.id = idVersao;
        } else {
            request.modelo = modeloId;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/app/ajax/parceiro-automovel',
            request,
            'Erro ao salvar versão, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        if (idVersao == '') {
            Alerta.notificacao('Versão salva com sucesso!', true);
            fecharBloco();
            adicionarNovoBloco(resposta.dado.id, resposta.dado.titulo);
            return;
        }
        Alerta.notificacao('Versão atualizada com sucesso!', true);
        atualizarBlocoExistente(idVersao, titulo);
        fecharBloco();
    };
    const adicionarNovoBloco = (id, titulo) => {
        const clone = blocoModelo.cloneNode(true);
        clone.setAttribute('data-id', id);
        clone.querySelector('.linha').innerText = titulo;
        blocoLista.appendChild(clone);
    };
    const atualizarBlocoExistente = (id, titulo) => {
        const linha = $('#bloco_versao_lista .versao[data-id="' + id + '"] .linha');
        if (!linha) {
            return;
        }
        linha.innerText = titulo;
    };
});
