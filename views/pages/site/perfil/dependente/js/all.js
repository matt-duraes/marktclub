// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/dependente"

window.addEventListener('load', () => {
    const blocoDependente = document.querySelector('#bloco_pagina_dependente table tbody');
    const blocoZero = document.querySelector('.zero');

    BODY.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove')) {
            const linhaDependente = event.target.closest('.linha_dependente');
            linhaDependente.remove();
        }
    });

    const acessarCadastrar = document.querySelector('#acessar_cadastro');
    const campoPreenchimento = document.querySelector('#input_dependente_nome');

    acessarCadastrar.addEventListener('click', function () {
        campoPreenchimento.scrollIntoView({ behavior: 'smooth' });
        campoPreenchimento.focus();
    });

    const botaoSalvar = document.querySelector('#botao_cadastra_dependente');
    botaoSalvar.addEventListener('click', e => {
        e.preventDefault();
        salvarDependente();
    });

    const inputNome = $('input[name=dependente_nome]');
    const inputCpf = $('input[name=dependente_cpf]');
    const inputEmail = $('input[name=dependente_email]');
    const salvarDependente = async () => {
        const nomeDependente = inputNome.value;
        const cpfDependente = inputCpf.value;
        const emailDependente = inputEmail.value;
        if (nomeDependente.trim().split(' ').length < 2) {
            Alerta.notificacao('Digite seu nome completo para continuar.', false);
            return;
        }
        const resposta = await ajaxPost(
            LINK + '/perfil/salvar-dependentes',
            {
                nome: nomeDependente,
                cpf: cpfDependente,
                email: emailDependente,
            },
            'Ocorreu um erro ao salvar o dependente, por favor, tente novamente.'
        );
        if (false === resposta) {
            return;
        }

        await Alerta.mensagem('Dependente Cadastrado', 'Seu dependente foi cadastrado com sucesso', true);
        formValue(inputNome, '');
        formValue(inputCpf, '');
        formValue(inputEmail, '');
        adicionarNovoDependente(resposta.dado.id, resposta.dado.nome, true);
    };

    const adicionarNovoDependente = (id, nome) => {
        if (!blocoZero.classList.contains('display_none')) {
            blocoZero.classList.add('display_none');
        }

        blocoDependente.insertAdjacentHTML(
            'beforeend',
            `
                <tr class="hover dependente" data-id="${id}">
                    <td>${nome}</td>
                    <td class="deletar botao_deletar_dependente">
                        <p>
                            Deletar
                        </p>
                    </td>
                </tr>
            `
        );
    };

    if (blocoDependente) {
        blocoDependente.addEventListener('click', async e => {
            if (
                !e.target.classList.contains('botao_deletar_dependente') &&
                !e.target.closest('.botao_deletar_dependente')
            ) {
                return;
            }
            const resposta = await Alerta.confirmar(
                'Deletar dependente',
                'Tem certeza que deseja deletar esse dependente? Essa ação não poderá ser desfeita.',
                false
            );

            if (false === resposta) {
                return;
            }

            const bloco = e.target.closest('.dependente');
            const id = bloco.getAttribute('data-id');

            deletarDependente(bloco, id);
        });
    }
    const deletarDependente = async (bloco, id) => {
        const resposta = await ajaxPost(LINK + '/perfil/deletar-dependente', { id });
        if (false === resposta) {
            return;
        }

        bloco.parentNode.removeChild(bloco);
        Alerta.notificacao('Dependente deletado com sucesso!', true);

        if (blocoDependente.querySelectorAll('.dependente').length == 0) {
            blocoZero.classList.remove('display_none');
        }
    };
});
