// @template "painel"

window.addEventListener('load', () => {
    const botaoAbrir = $('#botao_versao_abrir');
    const botaoFechar = $('#botao_versao_fechar');
    const botaoSalvar = $('#botao_versao_salvar');

    const blocoAdd = $('#bloco_versao_add');
    const blocoLista = $('#bloco_versao .bloco_linha_unica');

    const inputTitulo = $('#input_versao_titulo');
    const inputCor = $('#input_versao_cor');
    const inputValorDe = $('#input_versao_valor_de');
    const inputValorAte = $('#input_versao_valor_por');

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
    const deletarVersao = linha => {
        const id = linha.getAttribute('data-id');
    };
    const editarVersao = linha => {
        const id = linha.getAttribute('data-id');
        formValue(inputTitulo, 'Teste');
        formValue(inputCor, '');
        formValue(inputValorDe, '');
        formValue(inputValorAte, '');
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
            formValue(inputCor, '');
            formValue(inputValorDe, '');
            formValue(inputValorAte, '');
        }, 300);
    };
});
