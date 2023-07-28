/* eslint-disable camelcase */
// @template "site"
// @system "Alerta"
// @system "Form"
// @system "Loading"
// @system "Mascara"
// @resource "site/dependente"

window.addEventListener('load', () => {
    const ativarElementos = $('body');
    const operadora = $('#operadora').value;
    let regiaoSelecionada = '';
    let planoSelecionado = '';
    let acomodacao = '';

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Tab') {
            e.preventDefault();
        }
    });

    const listaGeral = document.querySelectorAll('.bloco_passo_passo_geral');

    if (listaGeral.length == 0) {
        return;
    }

    listaGeral.forEach((bloco, i) => {
        const blocoConteudo = bloco.querySelector('.bloco_conteudo');
        const conteudoLista = bloco.querySelectorAll('.bloco_conteudo .conteudo');
        const quantidadeConteudo = conteudoLista.length - 1;
        const itemLista = bloco.querySelectorAll('.bloco_progresso .item');
        blocoConteudo.classList.add('bloco_conteudo_item_' + conteudoLista.length);

        conteudoLista.forEach((conteudo, i2) => {
            let id = conteudo.getAttribute('id') || '';
            if (id == '') {
                id = 'id_passo_passo_' + i + '_' + i2;
                conteudo.setAttribute('id', id);
            }
            const item = itemLista[i2];
            item.setAttribute('data-id', id);
            item.setAttribute('data-numero', i2);

            let linha;
            if (i2 == 0) {
                linha = '</div><div class="linha_direita"></div>';
                item.classList.add('atual');
                const bola = item.querySelector('.bola');
                const numero = item.querySelector('span');
                const texto = item.querySelector('p');
                bola.classList.add('cor_border');
                numero.classList.add('cor_color');
                texto.classList.add('cor_color');
            } else if (i2 == quantidadeConteudo) {
                linha = '<div class="linha_esquerda">';
            } else {
                linha = '<div class="linha_esquerda"></div><div class="linha_direita"></div>';
            }
            item.insertAdjacentHTML('afterbegin', linha);
        });
        bloco.classList.add('carregado');
    });

    // ANTERIOR
    const botaoAnterior = document.querySelectorAll('.botao_passa_passo_anterior');
    if (botaoAnterior.length > 0) {
        botaoAnterior.forEach(botao => {
            botao.addEventListener('click', () => {
                botaoFazerSimulacao.classList.remove('fazer_simulacao');
                irParaPassoAnterior(botao);
            });
        });
    }
    const irParaPassoAnterior = botao => {
        const bloco = botao.closest('.bloco_passo_passo_geral');
        const itemLista = bloco.querySelectorAll('.bloco_progresso .item');
        const itemAtual = bloco.querySelector('.bloco_progresso .item.atual');
        const numero = parseInt(itemAtual.getAttribute('data-numero')) - 1;
        const novoNumero = parseInt(numero) + 1;

        montarNovoItem(bloco, itemLista, numero, novoNumero);
    };

    // PROXIMO
    let botaoFazerSimulacao = $('#fazerSimulacao');
    const botaoProximo = document.querySelectorAll('.botao_passa_passo_proximo');
    if (botaoProximo.length > 0) {
        botaoProximo.forEach(botao => {
            botao.addEventListener('click', e => {
                let teste = e.target.id;
                if (teste == 'fazerSimulacao') {
                    let dataNascimento = document.querySelector('#input_data_nascimento');
                    if (typeof dataNascimento.value == 'undefined' || dataNascimento.value == '') {
                        Alerta.notificacao('Preencha os campos obrigatórios', false);
                        return;
                    }
                    fazerSimulacao();
                    irParaProximoPasso(botao);
                } else {
                    irParaProximoPasso(botao);
                }
            });
        });
    }

    const irParaProximoPasso = botao => {
        const bloco = botao.closest('.bloco_passo_passo_geral');
        const itemLista = bloco.querySelectorAll('.bloco_progresso .item');
        const itemAtual = bloco.querySelector('.bloco_progresso .item.atual');
        const numero = parseInt(itemAtual.getAttribute('data-numero')) + 1;
        const novoNumero = parseInt(numero) + 1;

        montarNovoItem(bloco, itemLista, numero, novoNumero);
    };

    const montarNovoItem = (bloco, lista, numero, novoNumero) => {
        const atual = lista[numero];

        let jaFoiAtual = false;
        let blocoBola, blocoNumero, blocoTexto, blocoLinhaEsquerda, blocoLinhaDireita;
        lista.forEach(item => {
            blocoBola = item.querySelector('.bola');
            blocoNumero = item.querySelector('.bola span');
            blocoTexto = item.querySelector('p');
            blocoLinhaEsquerda = item.querySelector('.linha_esquerda');
            blocoLinhaDireita = item.querySelector('.linha_direita');

            if (item == atual) {
                item.classList.add('atual');
                item.classList.remove('concluido');

                blocoBola.classList.add('cor_border');
                blocoNumero.classList.add('cor_color');
                blocoTexto.classList.add('cor_color');
                if (blocoLinhaEsquerda) {
                    blocoLinhaEsquerda.classList.add('cor_bg');
                }
                if (blocoLinhaDireita) {
                    blocoLinhaDireita.classList.remove('cor_bg');
                }
                jaFoiAtual = true;
            } else if (jaFoiAtual) {
                item.classList.remove('atual');
                item.classList.remove('concluido');

                blocoBola.classList.remove('cor_border');
                blocoNumero.classList.remove('cor_color');
                blocoTexto.classList.remove('cor_color');
                if (blocoLinhaEsquerda) {
                    blocoLinhaEsquerda.classList.remove('cor_bg');
                }
                if (blocoLinhaDireita) {
                    blocoLinhaDireita.classList.remove('cor_bg');
                }
            } else {
                item.classList.add('concluido');
                item.classList.remove('atual');

                blocoBola.classList.add('cor_border');
                blocoNumero.classList.add('cor_color');
                blocoTexto.classList.add('cor_color');
                if (blocoLinhaEsquerda) {
                    blocoLinhaEsquerda.classList.add('cor_bg');
                }
                if (blocoLinhaDireita) {
                    blocoLinhaDireita.classList.add('cor_bg');
                }
            }
        });
        const blocoScroll = bloco.querySelector('.bloco_scroll');
        blocoScroll.className = 'bloco_scroll passo_' + novoNumero;
    };

    ativarElementos.addEventListener('click', e => {
        if (e.target.classList.contains('remove')) {
            const linhaDependente = e.target.closest('.linha_dependente');
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
    async function fazerSimulacao() {
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
    }
});
