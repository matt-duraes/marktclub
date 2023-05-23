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
