// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/dependente"

window.addEventListener('load', () => {
    const form = $('#form_dependente');

    const blocoDependente = $('#bloco_dependente');
    const blocoDependenteLista = $('#bloco_dependente_lista');
    const blocoDependenteZero = $('#bloco_dependente_zero');
    const blocoDependenteLinha = $('#bloco_dependente_linha');
    blocoDependenteLinha.removeAttribute('id');

    const inputNome = $('#input_dependente_nome');
    const inputCpf = $('#input_dependente_cpf');
    const inputEmail = $('#input_dependente_email');

    const botaoSalvar = $('#botao_cadastrar_dependente');

    const salvarDependente = async () => {
        if (!(await validarInput(form))) {
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/perfil/dependente-salvar',
            {
                nome: inputNome.value,
                cpf: inputCpf.value,
                email: inputEmail.value,
            },
            'Ocorreu um erro ao salvar o dependente, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }

        Alerta.notificacao('Dependente cadastrado com sucesso.', true);
        adicionarHtmlDependente(resposta.dado);

        monitorarLista();
        formValue(inputNome, '');
        formValue(inputCpf, '');
        formValue(inputEmail, '');
    };
    const adicionarHtmlDependente = dado => {
        const clone = blocoDependenteLinha.cloneNode(true);
        clone.setAttribute('data-id', dado.id);
        clone.querySelector('.nome').innerText = dado.nome;
        clone.querySelector('.status p').innerText = dado.status;
        blocoDependenteLista.appendChild(clone);
    };

    adicionarEventoEnter([inputNome, inputEmail, inputCpf], salvarDependente);
    botaoSalvar.addEventListener('click', () => {
        salvarDependente();
    });

    blocoDependenteLista.addEventListener('click', e => {
        if (e.target.classList.contains('botao_deletar_dependente') || e.target.closest('.botao_deletar_dependente')) {
            removerDependente(e.target.closest('.linha'));
        }
    });
    const removerDependente = async linha => {
        if (
            !(await Alerta.confirmar(
                'Deletar dependente',
                'Tem certeza que deseja deletar esse dependente? Essa ação não poderá ser desfeita.',
                false
            ))
        ) {
            return;
        }
        Loading.show();
        const id = linha.getAttribute('data-id');
        const resposta = await ajaxPost(
            LINK + '/perfil/dependente-deletar',
            { id },
            'Erro ao deletar dependente, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        linha.remove();
        monitorarLista();
    };

    const monitorarLista = () => {
        const lista = blocoDependenteLista.querySelectorAll('.linha');
        if (lista.length > 0) {
            blocoDependenteZero.classList.add('display_none');
            blocoDependente.classList.remove('display_none');
            return;
        }
        blocoDependenteZero.classList.remove('display_none');
        blocoDependente.classList.add('display_none');
    };
});
