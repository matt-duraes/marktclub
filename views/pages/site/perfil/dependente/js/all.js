// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/dependente"

window.addEventListener('load', () => {
    document.querySelector('body').addEventListener('click', function (event) {
        if (event.target.classList.contains('remove')) {
            const linhaDependente = event.target.closest('.linha_dependente');
            linhaDependente.remove();
        }
    });

    const botaoSalvar = document.querySelector('#botao_cadastra_dependente');
    botaoSalvar.addEventListener('click', async e => {
        e.preventDefault();
        await salvarDependente();
    });

    const salvarDependente = async () => {
        const colunas = document.querySelectorAll('#bloco_pagina_dependente .dependente .linha_dependente.normal');

        for (let i = 0; i < colunas.length; i++) {
            const coluna = colunas[i];

            const nomeDependente = coluna.querySelector('input[name=dependente_nome]').value;
            const cpfDependente = coluna.querySelector('input[name=dependente_cpf]').value;
            const emailDependente = coluna.querySelector('input[name=dependente_email]').value;

            if (nomeDependente !== '' || cpfDependente !== '' || emailDependente !== '') {
                const body = new FormData();
                body.append('nome', nomeDependente);
                body.append('cpf', cpfDependente);
                body.append('email', emailDependente);

                const resposta = await fetch(`${LINK}/perfil/salvar-dependentes`, {
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

                if (resposta.status == 201 && json.dado.id !== '' && json.dado.nome !== '') {
                    adicionarNovoDependente(json.dado.id, json.dado.nome, true);
                    return;
                }

                Alerta.notificacao(
                    json.erro != undefined && json.erro.mensagem != undefined
                        ? json.erro.mensagem
                        : 'Ocorreu um erro ao salvar dependente, tente novamente.',
                    false
                );
            }
        }
    };

    const blocoDependente = document.querySelector('#bloco_pagina_dependente table tbody');
    const adicionarNovoDependente = (id, nome, fechar) => {
        const blocoZero = document.querySelector('.zero');
        if (blocoZero) {
            blocoZero.style.display = 'none';
        }

        blocoDependente.insertAdjacentHTML(
            'beforeend',
            `
                <tr class="hover">
                    <td>${nome}</td>
                    <td class="deletar">
                        <p data-id="${id}" >
                            Deletar
                            <i class="botao_deletar botao_deletar_dependente">${Icone.fechar(8)}</i>
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
        const body = new FormData();
        body.append('id', id);

        const resposta = await fetch(LINK + '/perfil/deletar-dependente', {
            method: 'POST',
            body,
        });

        if (resposta.status == 204) {
            Alerta.notificacao('Dependente deletado com sucesso!', true);
            setTimeout(() => {
                window.location.reload();
            }, 2000);
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
