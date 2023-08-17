// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/dependente"

window.addEventListener('load', () => {
    BODY.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove')) {
            const linhaDependente = event.target.closest('.linha_dependente');
            linhaDependente.remove();
        }
    });

    const botaoSalvar = document.querySelector('#botao_cadastra_dependente');
    botaoSalvar.addEventListener('click', e => {
        e.preventDefault();
        salvarDependente();
    });

    const salvarDependente = async () => {
        const nomeDependente = $('input[name=dependente_nome]').value;
        const cpfDependente = $('input[name=dependente_cpf]').value;
        const emailDependente = $('input[name=dependente_email]').value;
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
            ''
        );
        if (resposta.status != 'sucesso') {
            Alerta.notificacao('Não foi possível cadastrar o dependente, preencha os campos corretamente', false);
        }

        if (resposta.status == 'sucesso') {
            await Alerta.mensagem('Dependente Cadastrado', 'Seu dependente foi cadastrado com sucesso', true);
            adicionarNovoDependente(resposta.dado.id, resposta.dado.nome, true);
            return;
        }
    };

    const blocoDependente = document.querySelector('#bloco_pagina_dependente table tbody');
    const adicionarNovoDependente = (id, nome) => {
        const blocoZero = document.querySelector('.zero');
        if (blocoZero) {
            blocoZero.style.display = 'none';
        }

        blocoDependente.insertAdjacentHTML(
            'beforeend',
            `
                <tr class="hover">
                    <td>${nome}</td>
                    <td class="deletar botao_deletar_dependente"  data-id="${id}">
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

            const id = e.target.closest('.deletar').getAttribute('data-id');
            const resposta = await Alerta.confirmar(
                'Deletar dependente',
                'Tem certeza que deseja deletar esse dependente? Essa ação não poderá ser desfeita.',
                false
            );

            if (resposta) {
                deletarDependente(id);
            }
        });
    }
    const deletarDependente = async id => {
        const resposta = await ajaxPost(LINK + '/perfil/deletar-dependente', { id });
        if (resposta) {
            setTimeout(() => {
                window.location.reload();
            }, 2000);
            Alerta.notificacao('Dependente deletado com sucesso!', true);
            return;
        }
    };
});
