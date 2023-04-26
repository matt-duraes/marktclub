window.addEventListener('load', () => {
    /*
    |--------------------------------------------------------------------------
    | VERIFICA SE TEM DEPENDENTE
    |--------------------------------------------------------------------------
    */
    const blocoDependente = document.querySelector('#bloco_dependente');
    if (!blocoDependente) {
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    const usuario = document.querySelector('#input_visualizar_id').value;
    const inputPodeDeletar = document.querySelector('#input_permissao_deletar');
    let podeDeletar = false;
    if (inputPodeDeletar) {
        podeDeletar = inputPodeDeletar.value == 'sim';
    }

    const buscarDependente = async () => {
        const body = new FormData();
        body.append('usuario', usuario);
        const resposta = await fetch(LINK + '/app/ajax/usuario-dependente', {
            method: 'POST',
            body,
        });

        const loading = blocoDependente.querySelector('.loading');
        loading.parentNode.removeChild(loading);

        const json = await respostaJson(resposta, 'Erro ao buscar dependentes.', false);
        if (false === json) {
            blocoDependente.insertAdjacentHTML(
                'beforeend',
                `<div class="zero dependente_zero">Erro ao buscar dependentes</div>`
            );
            return;
        }
        if (json.dado.length == 0) {
            blocoDependente.insertAdjacentHTML(
                'beforeend',
                `<div class="zero dependente_zero">Sem dependentes cadastrados</div>`
            );
            return;
        }
        json.dado.forEach(item => {
            adicionarNovoDependente(item.id, item.nome, item.email, item.status);
        });
    };
    buscarDependente();

    const adicionarNovoDependente = (id, nome, email, status, fechar) => {
        if (fechar === true) {
            fecharBlocoDependente();
        }
        const blocoZero = blocoDependente.querySelector('.dependente_zero');
        if (blocoZero) {
            blocoZero.parentNode.removeChild(blocoZero);
        }

        let statusNome = status;
        if (status == 'inativo') {
            statusNome = 'Inativo';
        } else if (status == 'ativo') {
            statusNome = 'Ativo';
        } else if (status == 'bloqueado') {
            statusNome = 'Bloqueado';
        }

        let htmlDeletar = '';
        if (podeDeletar) {
            htmlDeletar = `<i class="botao_deletar botao_deletar_dependente">${Icone.fechar(11)}</i>`;
        }
        blocoDependente.insertAdjacentHTML(
            'beforeend',
            `
                <div class="dependente lista_dado" data-id="${id}">
                    <div class="linha">
                        <strong class="texto_nome">Nome:</strong> ${nome}
                    </div>
                    <div class="linha">
                        <strong class="texto_nome">Status:</strong> ${statusNome}
                    </div>
                    ${htmlDeletar}
                </div>
            `
        );
    };

    /*
    |--------------------------------------------------------------------------
    | VERIFICA SE PODE SALVAR DEPENDENTE
    |--------------------------------------------------------------------------
    */
    const botaoAbrir = document.querySelector('#botao_dependente_abrir');
    if (!botaoAbrir) {
        return false;
    }

    /*
    |--------------------------------------------------------------------------
    | ABRIR/FECHAR BOLOCO
    |--------------------------------------------------------------------------
    */
    const blocoDependenteAdd = document.querySelector('#bloco_dependente_add');
    botaoAbrir.addEventListener('click', () => {
        blocoDependenteAdd.classList.add('display_flex');
        setTimeout(() => {
            blocoDependenteAdd.classList.add('abrir');
            inputNome.focus();
        }, 20);
    });

    const botaoFechar = document.querySelector('#botao_dependente_fechar');
    botaoFechar.addEventListener('click', () => {
        fecharBlocoDependente();
    });
    blocoDependenteAdd.addEventListener('click', e => {
        if (e.target.getAttribute('id') == 'bloco_dependente_add') {
            fecharBlocoDependente();
        }
    });
    const fecharBlocoDependente = () => {
        blocoDependenteAdd.classList.remove('abrir');
        setTimeout(() => {
            inputNome.value = '';
            inputEmail.value = '';
            inputCpf.value = '';
            blocoDependenteAdd.classList.remove('display_flex');
        }, 300);
    };

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    const inputNome = document.querySelector('#input_dependente_nome');
    const inputEmail = document.querySelector('#input_dependente_email');
    const inputCpf = document.querySelector('#input_dependente_cpf');
    const hash = document.querySelector('#dependente_hash').value;

    const botaoSalvar = document.querySelector('#botao_dependente_salvar');
    botaoSalvar.addEventListener('click', () => {
        salvarDependente();
    });
    inputNome.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            salvarDependente();
        }
    });
    inputEmail.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            salvarDependente();
        }
    });
    inputCpf.addEventListener('keydown', e => {
        if (e.key == 'Enter') {
            e.preventDefault();
            salvarDependente();
        }
    });

    const salvarDependente = async () => {
        if (inputNome.value == '') {
            inputNome.focus();
            Alerta.notificacao('O campo Nome é obrigatório.', false);
            return;
        } else if (inputEmail.value == '') {
            inputEmail.focus();
            Alerta.notificacao('O campo E-mail é obrigatório.', false);
            return;
        } else if (inputCpf.value == '') {
            inputCpf.focus();
            Alerta.notificacao('O campo CPF é obrigatório.', false);
            return;
        }

        Loading.show();

        const body = new FormData();
        body.append('usuario', usuario);
        body.append('nome', inputNome.value);
        body.append('email', inputEmail.value);
        body.append('cpf', inputCpf.value);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');

        const resposta = await fetch(LINK + '/app/salvar/usuario-dependente', {
            method: 'POST',
            body,
        });

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Loading.hide();

        if (resposta.status == 201) {
            adicionarNovoDependente(json.dado.id, inputNome.value, inputEmail.value, 'inativo', true);
            return;
        }

        Alerta.notificacao(
            json.erro != undefined && json.erro.mensagem != undefined
                ? json.erro.mensagem
                : 'Ocorreu um erro ao salvar dependente, tente novamente.',
            false
        );
    };

    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    */
    if (!podeDeletar) {
        return;
    }
    blocoDependente.addEventListener('click', async e => {
        if (
            !e.target.classList.contains('botao_deletar_dependente') &&
            !e.target.closest('.botao_deletar_dependente')
        ) {
            return;
        }

        const id = e.target.closest('.dependente').getAttribute('data-id');
        const resposta = await Alerta.confirmar(
            'Deletar dependente',
            'Tem certeza que deseja deletar esse dependente? Essa ação não poderá ser desfeita.',
            false
        );

        if (resposta) {
            deletarDependente(id);
        }
    });
    const deletarDependente = async id => {
        const body = new FormData();
        body.append('id[]', id);
        body.append('form_system_hash', hash);
        body.append('form_system_validacao', '');

        const resposta = await fetch(LINK + '/app/deletar/usuario-dependente', {
            method: 'POST',
            body,
        });

        if (resposta.status == 204) {
            Alerta.notificacao('Dependente deletado com sucesso!', true);

            const bloco = blocoDependente.querySelector('.dependente[data-id="' + id + '"]');
            if (bloco) {
                bloco.parentNode.removeChild(bloco);
            }
            if (blocoDependente.querySelectorAll('.dependente').length == 0) {
                blocoDependente.insertAdjacentHTML(
                    'beforeend',
                    `<div class="zero dependente_zero">Sem dependentes cadastrados</div>`
                );
            }
            return;
        }

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        Alerta.notificacao(
            json.erro != undefined && json.erro.mensagem != undefined
                ? json.erro.mensagem
                : 'Ocorreu um erro ao deletar dependente, por favor, tente novamente.',
            false
        );
    };
});
