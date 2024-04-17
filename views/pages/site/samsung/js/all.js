// @template "site"
// @system "Alerta"
// @system "Loading"
// @system "Popup"

const PopupAtualizar = new Popup('atualizar-dado', 'bloco_atualizar_email', true, true);
const botaoAbrirPopupAtualizar = $('#botao_samsung_atualizar');
const blocoEmailLista = $('#bloco_email_lista');
const blocoEmailZero = $('#bloco_email_zero');
const blocoEmaiNovo = $('#bloco_email_novo');
const inputEmailPessoal = $('#input_email_pessoal');
const inputEmailTrabalho = $('#input_email_trabalho');

window.addEventListener('load', () => {
    botaoAbrirPopupAtualizar.addEventListener('click', () => {
        PopupAtualizar.abrir();
    });

    const adicionarNovoEmail = (pessoal, trabalho) => {
        blocoEmailLista.classList.remove('display_none');
        blocoEmailZero.classList.add('display_none');
        botaoAbrirPopupAtualizar.innerText = 'Atualizar e-mail';
        const email = [];
        if (pessoal != '') {
            email.push(pessoal);
        }
        if (trabalho != '') {
            email.push(trabalho);
        }

        inputEmailPessoal.value = pessoal;
        inputEmailTrabalho.value = trabalho;

        blocoEmaiNovo.innerHTML = '<strong>' + email.join('</strong> ou <strong>', email) + '</strong>';
        Popup.staticFechar();
    };

    const botao = document.querySelector('.botao_atualizar_email');
    const inputPessoal = document.querySelector('input[name="email_pessoal"]');
    const inputTrabalho = document.querySelector('input[name="email_trabalho"]');

    const salvarEmail = async () => {
        if (inputPessoal.value == '' && inputTrabalho.value == '') {
            Alerta.notificacao('Você deve passar pelo menos um e-mail para continuar.', false);
            return;
        }

        Loading.show();
        const resposta = await ajaxPost(
            LINK + '/perfil/salvar-email',
            {
                /* eslint-disable */
                email_pessoal: inputPessoal.value,
                email_trabalho: inputTrabalho.value,
                /* eslint-enable */
            },
            'Erro ao atualizar seus e-mail, por favor, tente novamente.'
        );
        Loading.hide();
        if (false === resposta) {
            return;
        }
        adicionarNovoEmail(inputPessoal.value, inputTrabalho.value);
    };

    botao.addEventListener('click', () => {
        salvarEmail();
    });
    adicionarEventoEnter([inputPessoal, inputTrabalho], salvarEmail);
});
