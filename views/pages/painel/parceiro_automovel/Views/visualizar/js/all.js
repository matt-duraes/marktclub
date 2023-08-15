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
    const inputCor = $('#input_versao_cor');
    const inputValorDe = $('#input_versao_valor_de');
    const inputValorPor = $('#input_versao_valor_por');
    const inputStatus = $('#input_versao_status');

    let idEditar;

    botaoAbrir.addEventListener('click', () => {
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
        formValue(inputTitulo, resposta.dado.titulo);
        formValue(inputCor, resposta.dado.cor);
        formValue(inputValorDe, valorBr(resposta.dado.valor_de));
        formValue(inputValorPor, valorBr(resposta.dado.valor_por));
        inputStatus.checked = resposta.dado.status == 'ativo';

        abrirBloco(id);
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
    const salvarNovaVersao = async id => {
        const titulo = inputTitulo.value;
        const cor = inputCor.value;
        const valorDe = inputValorDe.value.replace(/\./g, '').replace(',', '.');
        const valorPor = inputValorPor.value.replace(/\./g, '').replace(',', '.');
        const status = inputStatus.value;

        if (titulo == '') {
            Alerta.notificacao('O campo título é obrigatório.', false);
            return;
        } else if (valorPor == '') {
            Alerta.notificacao('O campo valor por é obrigatório.', false);
            return;
        }

        const acao = id == undefined ? 'versao-salvar' : 'versao-atualizar';
        const request = {
            modelo: modeloId,
            indice: acao,
            titulo,
            cor,
            // eslint-disable-next-line camelcase
            valor_de: valorDe,
            // eslint-disable-next-line camelcase
            valor_por: valorPor,
            status,
        };
        if (id != undefined) {
            request.id = id;
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
        if (id == undefined) {
            Alerta.notificacao('Versão salva com sucesso!', true);
            fecharBloco();
            adicionarNovoBloco(resposta.dado.id, resposta.dado.titulo);
            return;
        }
        Alerta.notificacao('Versão atualizada com sucesso!', true);
        atualizarBlocoExistente(id, titulo);
    };
    const adicionarNovoBloco = (id, titulo) => {
        const clone = blocoModelo.cloneNode(true);
        clone.setAttribute('data-id', id);
        clone.querySelector('.linha').innerText = titulo;
        blocoLista.appendChild(clone);
    };
});
