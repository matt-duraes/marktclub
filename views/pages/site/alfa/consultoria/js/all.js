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
    const botaoEnviarContratacao = document.querySelector('#enviar_solicitacao');
    if (botaoEnviarContratacao) {
        botaoEnviarContratacao.addEventListener('click', function (e) {
            event.preventDefault();

            setTimeout(function () {
                Alerta.mensagem('Envio do formulário com sucesso!');
            }, 800);
        });
    }
    const tipoInputs = document.getElementsByName('tipo');
    tipoInputs.forEach(function (tipoInput) {
        tipoInput.addEventListener('change', function () {
            let valor = this.value;

            let blocoPlanejamento = document.querySelector('#bloco_planejamento');
            let blocoConsultoria = document.querySelector('#bloco_consultoria_financeira');

            if (valor === 'planejamento') {
                blocoPlanejamento.classList.remove('display_none');
                blocoConsultoria.style.display = 'none';
                blocoPlanejamento.style.display = 'block';
            } else if (valor === 'consultoria') {
                blocoConsultoria.classList.remove('display_none');
                blocoPlanejamento.style.display = 'none';
                blocoConsultoria.style.display = 'block';
            }
        });
    });
});
