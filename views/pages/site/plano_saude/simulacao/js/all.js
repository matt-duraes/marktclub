/* eslint-disable camelcase */
// @template "site"
// @system "Alerta"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/passo_passo"
// @resource "site/dependente"

window.addEventListener('load', () => {
    const parent = document.querySelector('body');
    const operadora = document.querySelector('#operadora').value;
    let regiaoSelecionada = '';
    let planoSelecionado = '';
    let acomodacao = '';

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
            regiaoSelecionada = valor;
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
            planoSelecionado = valor;
            let individual = ['amil_s80qp', 'amil_s380qp', 'amil_s450qp', 'amil_s750r1', 'amil_s750r2'];
            if (planoSelecionado.includes(individual) == true) {
                acomodacao = 'individual';
            } else {
                acomodacao = 'coletivo';
            }

            if (valor && coletivo.includes(valor) && acomodacaoInputAmil) {
                acomodacaoInputAmil.value = 'coletivo';
            } else if (valor && individual.includes(valor) && acomodacaoInputAmil) {
                acomodacaoInputAmil.value = 'individual';
            }
        });
    });

    const fazerSimulacao = document.querySelector('#fazerSimulacao');

    fazerSimulacao.addEventListener('click', async e => {
        let dtNascimentoTitular = document.querySelector('#input_data_nascimento').value;
        let dependentes = document.querySelectorAll('input#input_dependente.input_geral.input_data');
        let dtNascimentoDependentes = [];
        alert(dtNascimentoTitular);
        dependentes.forEach(dataDependente => {
            dtNascimentoDependentes.push(dataDependente.value);
        });
        if (dtNascimentoDependentes != '') {
            dtNascimentoDependentes.shift();
        }

        const query = `&operadora=${operadora}&regiaoSelecionada=${regiaoSelecionada}&planoSelecionado=${planoSelecionado}&dtNascimentoTitular=${dtNascimentoTitular}&dtNascimentoDependentes=${dtNascimentoDependentes}&acomodacao=${acomodacao}`;
        const resposta = await ajaxGet(LINK + '/saude/realizar-simulacao?' + query);
        if (resposta === false) {
            return;
        }

        buscarSimulacao(resposta.dado);
    });
    const buscarSimulacao = dado => {
        const { data_simulacao, valor_titular, valor_dependentes, valor_total } = dado;
        $('.preco_titular').innerHTML = valor_titular;
        $('.preco_dependente').innerHTML = valor_dependentes;
        $('.preco_total').innerHTML = valor_total;
        enviarSimulacao(data_simulacao);
    };
    const enviarSimulacao = data_simulacao => {
        const botaoEnviarSimulacao = document.querySelector('#enviar_contratacao');
        if (botaoEnviarSimulacao) {
            botaoEnviarSimulacao.addEventListener('click', function (e) {
                e.preventDefault();
                setTimeout(function () {
                    window.location.assign(
                        document.querySelector('#LINK').value + '/saude/contratacao/' + data_simulacao
                    );
                }, 200);
            });
        }
    };
});
