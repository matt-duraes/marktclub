// @template "login"
// @resource "site/login/slide"
// @system "Pagina"

window.addEventListener('load', () => {
    /*
    |--------------------------------------------------------------------------
    | SLIDE
    |--------------------------------------------------------------------------
    */
    if (document.querySelector('.area-slide')) {
        const slide = new SlideNav('.area-slide', '.embrulho-slide');
        slide.init();
        slide.addControl('.controle-slide');
    }
    /*
    |--------------------------------------------------------------------------
    | CATEGORIA
    |--------------------------------------------------------------------------
    */
    const categoriaTodas = document.querySelectorAll('.bloco_categoria .icone i');
    const categoriaQuantidade = categoriaTodas.length;

    let categoriaLoop;
    const mudarCategoriaEmLoop = () => {
        if (categoriaLoop == undefined) {
            categoriaLoop = setInterval(async () => {
                const proximo = await pegarProximaCategoria();
                mudarCategoria(proximo);
            }, 3000);
        }
    };
    const pegarProximaCategoria = () => {
        return new Promise(resolve => {
            categoriaTodas.forEach((item, i) => {
                if (item == categoriaAtual) {
                    const numero = i + 1;
                    if (numero >= categoriaQuantidade) {
                        resolve(categoriaTodas[0]);
                        return;
                    }
                    resolve(categoriaTodas[numero]);
                }
            });
        });
    };

    const eventoMouseDaCategoria = () => {
        blocoCategoria.addEventListener('mouseover', () => {
            clearInterval(categoriaLoop);
            categoriaLoop = undefined;
        });
        blocoCategoria.addEventListener('mouseout', e => {
            if (window.innerWidth > 1000) {
                mudarCategoriaEmLoop();
            }
        });
    };

    const eventoCliqueNaCategoria = () => {
        categoriaTodas.forEach(item => {
            item.addEventListener('click', () => {
                mudarCategoria(item);
            });
        });
    };

    const mudarCategoria = categoriaProximo => {
        const idAtual = categoriaAtual.getAttribute('data-id');
        const idProximo = categoriaProximo.getAttribute('data-id');
        if (idAtual == idProximo) {
            return;
        }

        const blocoAtual = document.getElementById(idAtual);
        const blocoProximo = document.getElementById(idProximo);

        categoriaAtual.classList.remove('hover');
        categoriaAtual.classList.remove('cor_bg');
        categoriaProximo.classList.add('hover');
        categoriaProximo.classList.add('cor_bg');

        blocoAtual.classList.add('animacao_atual');
        blocoProximo.style.display = 'flex';
        setTimeout(() => {
            blocoProximo.classList.add('animacao_proximo');
        }, 20);

        setTimeout(() => {
            blocoAtual.classList.remove('categoria_atual');
            blocoAtual.classList.remove('animacao_atual');
            blocoProximo.classList.remove('animacao_proximo');
            blocoProximo.classList.add('categoria_atual');

            blocoAtual.style.display = 'none';
        }, 320);

        categoriaAtual = categoriaProximo;
    };

    let categoriaAtual, blocoCategoria;
    if (categoriaQuantidade > 0) {
        categoriaAtual = categoriaTodas[0];
        blocoCategoria = document.querySelector('#bloco_categoria');
        if (window.innerWidth > 1000) {
            mudarCategoriaEmLoop();
            eventoMouseDaCategoria();
        }
        eventoCliqueNaCategoria();
    }

    //CONTADOR

    const blocoContador = document.querySelector('#bloco_contador');
    const blocoContadorNumero = document.querySelector('#bloco_contador_numero');
    document.addEventListener('scroll', () => {
        animarContador();
    });

    const animarContador = () => {
        const alturaJanela = window.innerHeight;
        const distanciaBlocoTopo = blocoContador.getBoundingClientRect().top;
        const distanciaNumeroTopo = blocoContadorNumero.getBoundingClientRect().top;

        if (distanciaBlocoTopo < alturaJanela && window.matchMedia('(min-width: 400px)').matches) {
            let margin = (alturaJanela - distanciaBlocoTopo) / 3;
            margin = margin > 200 ? 200 : margin;
            blocoContador.style.marginTop = '-' + margin + 'px';
        }
        if (
            distanciaNumeroTopo + 200 < alturaJanela &&
            !blocoContadorNumero.classList.contains('bloco_contador_ativo')
        ) {
            fazerNumerosDoContadorCorrer();
        }
    };

    const fazerNumerosDoContadorCorrer = () => {
        /*
         * Para adicionar o número de lojas e parcerias basta
         * alterar o data_target no html
         */
        blocoContadorNumero.classList.add('bloco_contador_ativo');

        const contadores = document.querySelectorAll('.contar');

        contadores.forEach(contar => {
            let contador = 0;

            const atualizarContador = () => {
                const target = +contar.getAttribute('data_target');
                const c = contador;

                const increment = target / 200;

                if (c < target) {
                    contador = c + increment;
                    const stringContador = Math.ceil(c + increment).toString();
                    contar.innerText = `${stringContador.replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1.')}`;
                    setTimeout(atualizarContador, 1);
                } else {
                    contar.innerText = target.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, '$1.');
                }
            };
            atualizarContador();
        });
    };

    animarContador();

    /*
    |--------------------------------------------------------------------------
    | CALCULADORA
    |--------------------------------------------------------------------------
    */

    function limparFormulario() {
        let formularios = document.querySelectorAll('form fieldset input');
        formularios.forEach(formulario => (formulario.value = ''));
    }

    document.getElementById('abrir').addEventListener('click', () => {
        const containerConteudo = document.querySelector('.container .conteudo');
        containerConteudo.style.animation = 'abrirCalculadora 1s ease forwards';

        const blocoCalculadoraConteudo = document.querySelector('.bloco_calculadora .container .conteudo');
        blocoCalculadoraConteudo.classList.remove('esconde');

        document.getElementById('abrir').classList.add('esconde');

        const fecharCalculadora = document.querySelector('.bloco_calculadora .container .fechar');
        fecharCalculadora.classList.remove('esconde');

        const conteudoBody = document.body.getBoundingClientRect().top;
        const conteudoRect = document
            .querySelector('#bloco_login .bloco_calculadora .container .conteudo')
            .getBoundingClientRect().top;

        window.scrollTo({
            top: conteudoRect - conteudoBody,
            behavior: 'smooth',
        });
    });

    document.getElementById('fechar').addEventListener('click', () => {
        const containerConteudo = document.querySelector('.container .conteudo');
        containerConteudo.style.animation = 'fecharCalculadora 1s ease forwards';

        const targetElement = document.querySelector('#bloco_login .bloco_calculadora .container .topo');

        const resultado = document.querySelector('.resultado');
        if (resultado) {
            resultado.style.animation = 'fecharResultado 1s ease forwards';
            setTimeout(() => {
                resultado.remove();
            }, 1000);
        }

        const abrir = document.getElementById('abrir');
        abrir.classList.remove('esconde');

        const fecharCalculadora = document.querySelector('.bloco_calculadora .container .fechar');
        fecharCalculadora.classList.add('esconde');

        setTimeout(() => {
            const blocoCalculadoraConteudo = document.querySelector('.bloco_calculadora .container .conteudo');
            blocoCalculadoraConteudo.classList.add('esconde');
            limparFormulario();
        }, 1000);
    });

    const botaoCalcular = $('#botao_calculadora');
    const inputAcademia = $('#input_calculadora_academia');
    const inputEscola = $('#input_calculadora_escola');
    const inputFarmacia = $('#input_calculadora_farmacia');
    const inputEletro = $('#input_calculadora_eletroeletronico');
    const inputIdioma = $('#input_calculadora_idioma');
    const inputRestaurante = $('#input_calculadora_restaurante');
    const blocoCalculadoraResposta = $('#bloco_calculadora_resposta');
    const blocoCalculadoraValor = $('#bloco_calculadora_valor');
    const blocoCalculadoraForm = $('#bloco_calculadora_form');
    const botaoCalculadoraResetar = $('#bloco_calculadora_resetar');
    const blocoCalculadoraGeral = $('#bloco_calculadora_geral');

    if (botaoCalcular) {
        botaoCalcular.addEventListener('click', e => {
            calcularDesconto();
        });
    }
    const calcularDesconto = () => {
        let valor = 0;
        valor += somarValorDesconto(inputAcademia, 10);
        valor += somarValorDesconto(inputEscola, 20);
        valor += somarValorDesconto(inputFarmacia, 8);
        valor += somarValorDesconto(inputEletro, 5);
        valor += somarValorDesconto(inputIdioma, 15);
        valor += somarValorDesconto(inputRestaurante, 10);

        if (valor == 0) {
            Alerta.notificacao('Você deve passar o valor de pelo menos um item para fazer o calculo.', false);
            return;
        }

        valor = new Intl.NumberFormat('pt-BR', {
            style: 'currency',
            currency: 'BRL',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2,
        }).format(valor);

        blocoCalculadoraForm.classList.add('esconde');
        blocoCalculadoraResposta.classList.remove('display_none');
        blocoCalculadoraValor.innerText = valor;

        blocoCalculadoraGeral.scrollIntoView({ behavior: 'smooth' });
        inputAcademia.value = '';
        inputEscola.value = '';
        inputFarmacia.value = '';
        inputEletro.value = '';
        inputIdioma.value = '';
        inputRestaurante.value = '';
    };
    const somarValorDesconto = (input, porcentagem) => {
        const valor = input.value.replace(/\./g, '').replace(',', '.');
        if (!/^[0-9]{1,}\.[0-9]{2}$/.test(valor)) {
            return 0;
        }
        return valor * (porcentagem / 100);
    };

    botaoCalculadoraResetar.addEventListener('click', function () {
        blocoCalculadoraForm.classList.remove('esconde');

        const abrir = document.getElementById('abrir');
        abrir.classList.add('esconde');

        blocoCalculadoraResposta.classList.add('display_none');

        const fecharCalculadora = document.querySelector('.bloco_calculadora .container .fechar');
        fecharCalculadora.classList.remove('esconde');
    });

    const carregarFuncaoContato = () => {
        const formulario = document.getElementById('formulario_contato');

        const botaoEnviarContato = document.querySelector('#botao_enviar_contato');
        botaoEnviarContato.addEventListener('click', async e => {
            e.preventDefault();
            const nome = formulario.querySelector('input[name=nome]');
            const telefone = formulario.querySelector('input[name=telefone]');
            const email = formulario.querySelector('input[name=email]');
            const mensagem = formulario.querySelector('textarea[name=mensagem]');
            const hash = formulario.querySelector('input[name=hash]');
            const validacao = formulario.querySelector('input[name=validacao]');

            const body = new FormData();
            body.append('nome', nome.value);
            body.append('telefone', telefone.value);
            body.append('email', email.value);
            body.append('mensagem', mensagem);
            body.append('hash', hash);
            body.append('validacao', validacao);

            Loading.show();

            const resposta = await fetch('/contato', {
                method: 'POST',
                body,
            });

            let json;
            try {
                json = await resposta.json();
            } catch (error) {
                json = {};
            }

            Loading.hide();
            if (resposta.status === 201) {
                Alerta.notificacao(`Em breve entraremos em contato.`, true);
                setTimeout(() => {
                    window.location.reload();
                }, 2000);
                return;
            }

            Alerta.notificacao(
                json.erro.mensagem !== undefined
                    ? json.erro.mensagem
                    : 'Ocorreu um erro ao enviar, por favor, tente novamente.',
                false
            );
        });

        const botaoFechar = document.querySelectorAll('.botao_fechar_popup');
        botaoFechar.forEach(fecha => {
            fecha.addEventListener('click', () => {
                Pagina.staticFechar();
            });
        });
    };

    const paginaContato = new Pagina('Entre em Contato', LINK + '/contato', {}, true, true, carregarFuncaoContato);
    const botaoPopupContato = document.querySelector('.abrirModalContato');

    if (botaoPopupContato) {
        botaoPopupContato.addEventListener('click', () => {
            paginaContato.abrir();
        });
    }
});
