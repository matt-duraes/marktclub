// @template "site"
// @system "Alerta"
// @system "Icone"
// @system "Pagina"
// @system "Funcao"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/passo_passo"

window.addEventListener('load', () => {
    btnProximoStepper.forEach((btn, index) => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            switch (index) {
                case 0:
                    let valor = document.querySelector('#bloco_simulacao input[name="valor"]').value;
                    let prazo = document.querySelector('#bloco_simulacao input[name="prazo"]').value;
                    let taxa = document.querySelector('#bloco_simulacao input[name="tipo"]').value;

                    bullets[0].classList.add('ativo');
                    progressChecks[0].classList.add('ativo');
                    progressTexts[0].classList.add('ativo');

                    boxProximo(
                        document.getElementById('bloco_simulacao'),
                        document.getElementById('resultado_simulacao')
                    );

                    document.getElementById('simulacao_valor').textContent = 'R$ 200';
                    document.querySelectorAll('.valor_emprestimo').forEach(e => (e.textContent = 'R$ 100'));
                    document.querySelectorAll('.valor_prazo').forEach(e => (e.textContent = prazo));
                    document.getElementById('texto_valor_juros').textContent = '1,20 a.m.';
                    document.querySelectorAll('.valor_parcela').forEach(e => (e.textContent = 'R$ 200'));

                    valorFinal = valor;
                    prazoFinal = prazo;
                    parcelaFinal = '23';
                    break;
                case 1:
                    bullets[1].classList.add('ativo');
                    progressChecks[1].classList.add('ativo');
                    progressTexts[1].classList.add('ativo');

                    boxProximo(document.getElementById('resultado_simulacao'), document.getElementById('contato'));

                    break;
                case 2:
                    bullets[2].classList.add('ativo');
                    progressChecks[2].classList.add('ativo');
                    progressTexts[2].classList.add('ativo');

                    boxProximo(document.getElementById('contato'), document.getElementById('bloco_solicitacao'));

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
                    bullets[0].classList.remove('ativo');
                    progressChecks[0].classList.remove('ativo');
                    progressTexts[0].classList.remove('ativo');
                    boxAnterior(
                        document.getElementById('resultado_simulacao'),
                        document.getElementById('bloco_simulacao')
                    );
                    break;
                case 1:
                    bullets[1].classList.remove('ativo');
                    progressChecks[1].classList.remove('ativo');
                    progressTexts[1].classList.remove('ativo');

                    boxAnterior(document.getElementById('contato'), document.getElementById('resultado_simulacao'));

                    break;
                case 2:
                    bullets[2].classList.remove('ativo');
                    progressChecks[2].classList.remove('ativo');
                    progressTexts[2].classList.remove('ativo');

                    boxAnterior(document.getElementById('bloco_solicitacao'), document.getElementById('contato'));

                    break;
                default:
                    break;
            }
        });
    });

    const btnSubmit = document.querySelector('#enviar_solicitacao');
    btnSubmit.addEventListener('click', function (e) {
        event.preventDefault();

        bullets[3].classList.add('ativo');
        progressChecks[3].classList.add('ativo');
        progressTexts[3].classList.add('ativo');

        setTimeout(function () {
            Alerta.mensagem('Envio do formulário com sucesso!');
        }, 800);
    });
});
