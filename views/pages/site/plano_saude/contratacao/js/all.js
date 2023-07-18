// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Galeria"
// @system "Calendario"
// @system "Mascara"
// @resource "site/passo_passo"

document.querySelectorAll('input[type=checkbox][name=responsavel]').forEach(function (checkbox) {
    checkbox.addEventListener('change', function () {
        if (this.checked) {
            document.querySelector('input[name=responsavel_nome]').value =
                document.querySelector('input[name=nome]').value;
            document.querySelector('input[name=responsavel_cpf]').value =
                document.querySelector('input[name=cpf]').value;
            document.querySelector('input[name=responsavel_rg]').value = document.querySelector('input[name=rg]').value;
            document.querySelector('input[name=responsavel_orgao_expedidor]').value =
                document.querySelector('input[name=orgao_expedidor]').value;
        } else {
            document
                .querySelectorAll('#seguro-contratacao .formulario-contratacao .elemento-formulario.responsavel input')
                .forEach(function (input) {
                    input.value = '';
                });
        }
    });
});
const idSimulacao = document.getElementById('simulacao').value;
const form = document.getElementById('formulario_contratacao');
const enviarSimulacao = document.querySelector('#enviarSimulacao');

form.addEventListener('submit', async event => {
    event.preventDefault();
    const formData = new FormData(event.target);
    const resposta = await fetch(LINK + '/saude/contratacao/' + idSimulacao, {
        method: 'POST',
        body: formData,
    });

    if (false === resposta) {
        return;
    }
    Loading.show();
    if (resposta === false) {
        Alerta.notificacao('Formulário não enviado', false);
        return;
    }
    setTimeout(teste, 3000);
});

const teste = () => {
    Loading.hide();
    Alerta.notificacao('Dados enviados para contratação', true);
    return;
};
