/* eslint-disable camelcase */
// @template "site"
// @system "Alerta"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/passo_passo"
// @resource "site/dependente"

window.addEventListener('load', () => {
    const ativarElementos = $('body');
    const operadora = $('#operadora').value;
    let regiaoSelecionada = '';
    let planoSelecionado = '';
    let acomodacao = '';

    //mostrar unimed vitoria
    ativarElementos.addEventListener('click', e => {
        if (e.target.classList.contains('remover')) {
            const linhaDependente = event.target.closest('.linha_dependente');
            linhaDependente.remove();
        }
    });

    // Exibir campos de acomodação para planos != AMIL
    const tipoInputs = document.getElementsByName('tipo');
    tipoInputs.forEach(function (tipoInput) {
        tipoInput.addEventListener('change', function () {
            let valor = this.value;
            let blocoCentralPlano = $('#bloco_central_plano');
            blocoCentralPlano.style.display = 'flex';

            let enfermaria30 = $('.botao_input.enfermaria_30');
            let enfermaria50 = $('.botao_input.enfermaria_50');
            let apartamento = $('.botao_input.apartamento');
            let enfermaria = $('.botao_input.enfermaria');
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

    // Escolha de região para plano AMIl
    const escolherRegiao = () => {
        // seleção de região
        const regiaoInputsAmil = document.getElementsByName('regiao');
        const planosRj = $('#bloco_tipo_amil .planos_rio_de_janeiro');
        const planosSp = $('#bloco_tipo_amil .planos_sao_paulo');
        const planosBsb = $('#bloco_tipo_amil .planos_brasilia');

        // adiciona classe ativa e remove classe inativa
        const ativarElemento = elemento => {
            elemento.classList.remove('plano_inativo');
            elemento.classList.add('plano_ativo');
        };

        // adiciona classe inativa e remove classe ativa
        const desativarElemento = elemento => {
            elemento.classList.remove('plano_ativo');
            elemento.classList.add('plano_inativo');
        };

        regiaoInputsAmil.forEach(function (regiaoInput) {
            regiaoInput.addEventListener('click', function () {
                let blocoTipoAmil = $('#bloco_tipo_amil');
                blocoTipoAmil.style.display = 'flex';

                regiaoSelecionada = this.value;
                desativarElemento(planosBsb);
                desativarElemento(planosRj);
                desativarElemento(planosSp);

                if (regiaoSelecionada === 'brasilia') {
                    ativarElemento(planosBsb);
                } else if (regiaoSelecionada === 'rio_de_janeiro') {
                    ativarElemento(planosRj);
                } else if (regiaoSelecionada === 'sao_paulo') {
                    ativarElemento(planosSp);
                }
            });
        });
    };

    const definirAcomodacao = () => {
        let acomodacoes = document.getElementsByName('acomodacao');
        acomodacoes.forEach(acomodacaoSelecionada => {
            acomodacaoSelecionada.addEventListener('click', e => {
                acomodacao = e.target.value;
            });
        });
    };

    if (operadora == 'amil') {
        escolherRegiao();
    }

    // Faz a verificação dos planos e define acomodação
    const inputsPlanos = document.getElementsByName('tipo');
    inputsPlanos.forEach(function (plano) {
        plano.addEventListener('change', function () {
            planoSelecionado = this.value;
            if (operadora == 'amil') {
                let coletivo = [
                    'amil_s80qc',
                    'amil_s380qc',
                    'amil_s450qc',
                    'amil_s60qc_rj',
                    'amil_s60qc_jundiai',
                    'amil_s60qc_sp',
                ];
                acomodacao = 'individual';
                if (coletivo.includes(planoSelecionado)) {
                    acomodacao = 'coletivo';
                }
            }
            if (operadora == 'unimed-florianopolis') {
                definirAcomodacao();
            }
        });
    });

    if (operadora == 'unimed-vitoria') {
        definirAcomodacao();
    }

    const fazerSimulacao = $('#fazerSimulacao');
    fazerSimulacao.addEventListener('click', async e => {
        // recebe dados das data de nascimento e trata eles para envio
        let dtNascimentoTitular = $('#input_data_nascimento').value;
        let dependentes = $$('input#input_dependente.input_geral.input_data');
        let dtNascimentoDependentes = Array.from(dependentes, dataDependente => dataDependente.value);

        // deixa operadora com padrão para recebimento
        let operadoraSelecionada = operadora;
        switch (operadora) {
            case 'unimed-florianopolis':
                operadoraSelecionada = operadora.replace('-', '_');
                break;
            case 'unimed-vitoria':
                operadoraSelecionada = 'unimed';
                break;
        }

        // monstagem de query para envio de simulação
        const query = `&operadora=${operadoraSelecionada}&regiaoSelecionada=${regiaoSelecionada}&planoSelecionado=${planoSelecionado}&dtNascimentoTitular=${dtNascimentoTitular}&dtNascimentoDependentes=${dtNascimentoDependentes}&acomodacao=${acomodacao}`;
        const resposta = await ajaxGet(LINK + '/saude/realizar-simulacao?' + query);
        if (resposta === false) {
            return;
        }
        buscarSimulacao(resposta.dado);
    });

    const buscarSimulacao = dado => {
        // destructuring na resposta.dado para evitar repetição de dado.valor
        const { id, valor_titular, valor_dependentes, valor_total } = dado;
        // convertendo para array
        const arrayDeValores = Object.values(valor_dependentes);
        $('.preco_titular').innerHTML = valor_titular;
        //deixando troca de estilo dinâmica
        for (let x = 0; x < arrayDeValores.length; x++) {
            let dependente = `.preco_dependente${x}`;
            let containerDependente = $(`${dependente}`).parentElement;
            containerDependente.classList.remove('valor_dependente');
            $(`${dependente}`).innerHTML = arrayDeValores[x];
        }
        $('.preco_total').innerHTML = valor_total;
        enviarSimulacao(id);
    };

    const enviarSimulacao = id_simulacao => {
        const botaoEnviarSimulacao = $('#enviar_contratacao');
        if (botaoEnviarSimulacao) {
            botaoEnviarSimulacao.addEventListener('click', function (e) {
                e.preventDefault();
                setTimeout(function () {
                    window.location.assign($('#LINK').value + '/saude/contratacao/' + id_simulacao);
                }, 200);
            });
        }
    };
});
