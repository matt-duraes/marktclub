// @template "site"
// @system "Alerta"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/passo_passo"
// @resource "site/dependente"

window.addEventListener('load', () => {
    const parent = document.querySelector('body');
    parent.addEventListener('click', function (event) {
        if (event.target.classList.contains('remove')) {
            const linhaDependente = event.target.closest('.linha_dependente');
            linhaDependente.remove();
        }
    });

    const tipoInputs = document.getElementsByName('tipo');
    tipoInputs.forEach(function (tipoInput) {
        tipoInput.addEventListener('change', function () {
            let valor = this.value;
            let blocoCentralPlano = document.getElementById('bloco_central_plano');
            blocoCentralPlano.style.display = 'flex';
            let enfermaria30 = blocoCentralPlano.querySelector('.botao_input.enfermaria_30');
            let enfermaria50 = blocoCentralPlano.querySelector('.botao_input.enfermaria_50');
            let apartamento = blocoCentralPlano.querySelector('.botao_input.apartamento');
            let enfermaria = blocoCentralPlano.querySelector('.botao_input.enfermaria');

            enfermaria30.style.display = 'none';
            enfermaria50.style.display = 'none';
            if (valor === 'estadual' || valor === 'nacional') {
                enfermaria.style.display = 'block';
                apartamento.style.display = 'block';
            } else if (valor === 'regional') {
                enfermaria.style.display = 'none';
                apartamento.style.display = 'none';
                enfermaria30.style.display = 'block';
                enfermaria50.style.display = 'block';
            }
        });
    });

    const rjInput = document.querySelector('#bloco_tipo_amil .botao_input.amil_s60qc_rj');
    const jundiaiInput = document.querySelector('#bloco_tipo_amil .botao_input.amil_s60qc_jundiai');
    const spInput = document.querySelector('#bloco_tipo_amil .botao_input.amil_s60qc_sp');

    const regiaoInputsAmil = document.getElementsByName('regiao');
    regiaoInputsAmil.forEach(function (regiaoInput) {
        regiaoInput.addEventListener('change', function () {
            let valor = this.value;
            let blocoTipoAmil = document.getElementById('bloco_tipo_amil');
            blocoTipoAmil.style.display = 'flex';

            if (valor == 'brasilia') {
                rjInput.style.display = 'none';
                jundiaiInput.style.display = 'none';
                spInput.style.display = 'none';
            } else if (valor == 'rio-de-janeiro' && rjInput && jundiaiInput && spInput) {
                rjInput.style.display = 'block';
                jundiaiInput.style.display = 'none';
                spInput.style.display = 'none';
            } else if (valor == 'sao-paulo' && rjInput && jundiaiInput && spInput) {
                rjInput.style.display = 'none';
                jundiaiInput.style.display = 'block';
                spInput.style.display = 'block';
            }
        });
    });

    const tipoInputsAmil = document.getElementsByName('tipo');
    const acomodacaoInputAmil = document.getElementsByName('acomodacao')[0];

    tipoInputsAmil.forEach(function (tipoInput) {
        tipoInput.addEventListener('change', function () {
            let valor = this.value;
            let coletivo = [
                'amil_s60qc_rj',
                'amil_s80qc',
                'amil_s380qc',
                'amil_s450qc',
                'amil_s60qc_jundiai',
                'amil_s60qc_sp',
            ];
            let individual = ['amil_s80qp', 'amil_s380qp', 'amil_s450qp', 'amil_s750r1', 'amil_s750r2'];

            if (valor && coletivo.includes(valor) && acomodacaoInputAmil) {
                acomodacaoInputAmil.value = 'coletivo';
            } else if (valor && individual.includes(valor) && acomodacaoInputAmil) {
                acomodacaoInputAmil.value = 'individual';
            }
        });
    });

    const botaoEnviarSimulacao = document.querySelector('#enviar_contratacao');
    if (botaoEnviarSimulacao) {
        botaoEnviarSimulacao.addEventListener('click', function (e) {
            e.preventDefault();

            let simulacao = btnSubmit.getAttribute('data-simulacao');
            setTimeout(function () {
                window.location.assign(LINK + '/saude/contratacao/' + simulacao);
            }, 200);
        });
    }
});
