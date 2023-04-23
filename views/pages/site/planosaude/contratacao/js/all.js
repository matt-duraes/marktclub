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

const dados = document.getElementById('dados');
const contato = document.getElementById('contato');
const endereco = document.getElementById('endereco');

btnProximoStepper.forEach((btn, index) => {
    btn.addEventListener('click', function (e) {
        e.preventDefault();
        switch (index) {
            case 0:
                if (dados && contato) {
                    bullets[0].classList.add('ativo');
                    progressChecks[0].classList.add('ativo');
                    progressTexts[0].classList.add('ativo');
                    boxProximo(dados, contato);
                }

                break;
            case 1:
                if (contato && endereco) {
                    bullets[1].classList.add('ativo');
                    progressChecks[1].classList.add('ativo');
                    progressTexts[1].classList.add('ativo');
                    boxProximo(contato, endereco);
                }

                break;
            default:
                break;
        }
    });
});

btnAnteriorStepper.forEach((btn, index) => {
    btn.addEventListener('click', function (event) {
        event.preventDefault();
        switch (index) {
            case 0:
                if (contato && dados) {
                    bullets[0].classList.add('ativo');
                    progressChecks[0].classList.add('ativo');
                    progressTexts[0].classList.add('ativo');
                    boxProximo(contato, dados);
                }

                break;
            case 1:
                if (endereco && contato) {
                    bullets[1].classList.remove('ativo');
                    progressChecks[1].classList.remove('ativo');
                    progressTexts[1].classList.remove('ativo');
                    boxProximo(endereco, contato);
                }

                break;
            default:
                break;
        }
    });
});
