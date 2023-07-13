// @template "login"
// @system "Loading"
// @system "Alerta"
// @system "Mascara"
// @system "Form"
// @resource "site/login/slide"

window.addEventListener('load', () => {
    const inputLogin = document.getElementById('input_login');
    const inputSenha = document.getElementById('input_senha');
    const botaoLogin = document.getElementById('botao_fazer_login');

    if (inputLogin) {
        inputLogin.addEventListener('keydown', e => {
            if (e.key == 'Enter') {
                fazerLogin();
            }
        });
        inputSenha.addEventListener('keydown', e => {
            if (e.key == 'Enter') {
                fazerLogin();
            }
        });
        botaoLogin.addEventListener('click', () => {
            fazerLogin();
        });
    }
    const fazerLogin = async () => {
        const login = inputLogin.value;
        const senha = inputSenha.value;
        const resposta = await ajaxPost(LINK + '/login', { login, senha }, 'Erro ao fazer o login, tente novamente.');
        if (false === resposta) {
            return;
        }
        window.location.assign(resposta.dado.link);
    };

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
        categoriaAtual.classList.remove('bg_cor');
        categoriaProximo.classList.add('hover');
        categoriaProximo.classList.add('bg_cor');

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
        const targetOffset = targetElement.offsetTop;

        const resultado = document.querySelector('.resultado');
        if (resultado) {
            resultado.style.animation = 'fecharResultado 1s ease forwards';
            setTimeout(() => {
                resultado.remove();
            }, 1000);
        }

        const abrir = document.getElementById('abrir');
        abrir.classList.remove('esconde');

        window.scrollTo({
            top: targetOffset - 100,
            behavior: 'smooth',
        });

        const fecharCalculadora = document.querySelector('.bloco_calculadora .container .fechar');
        fecharCalculadora.classList.add('esconde');

        setTimeout(() => {
            const blocoCalculadoraConteudo = document.querySelector('.bloco_calculadora .container .conteudo');
            blocoCalculadoraConteudo.classList.add('esconde');
            limparFormulario();
        }, 1000);
    });

    document.querySelector('body').addEventListener('click', function (e) {
        if (e.target.id === 'calcular') {
            e.preventDefault();

            const form = e.target.closest('form');
            const link = form.getAttribute('action');

            const academia = document.querySelector('input[name=academia]', form).value;
            const escola_creche = document.querySelector('input[name=escola_creche]', form).value;
            const farmacia = document.querySelector('input[name=farmacia]', form).value;
            const eletroeletronico = document.querySelector('input[name=eletroeletronico]', form).value;
            const idioma = document.querySelector('input[name=idioma]', form).value;
            const restaurante = document.querySelector('input[name=restaurante]', form).value;

            Loading.show();

            const data = {
                ajax: true,
                academia,
                escola_creche,
                farmacia,
                eletroeletronico,
                idioma,
                restaurante,
            };

            fetch(link, {
                method: 'POST',
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                },
            })
                .then(response => response.json())
                .then(resposta => {
                    if (resposta.erro === false) {
                        const blocoCalculadoraConteudo = document.querySelector(
                            '.bloco_calculadora .container .conteudo'
                        );
                        blocoCalculadoraConteudo.classList.add('esconde');

                        const container = document.querySelector('.bloco_calculadora .container');
                        container.innerHTML += `
                            <div class="resultado">
                            <h2>
                                <span>Você economizará em média</span>
                            </h2>
                            <strong class="color_cor">R$ ${resposta.resultado} ao ano</strong>
                            <div class="novamente">
                                <p>Calcular novamente</p>
                            </div>
                            </div>
                        `;

                        const conteudoBody = document.body.getBoundingClientRect().top;
                        const resultadoRect = document
                            .querySelector('#bloco_login .bloco_calculadora .container .resultado')
                            .getBoundingClientRect().top;

                        window.scrollTo({
                            top: resultadoRect - conteudoBody,
                            behavior: 'smooth',
                        });
                        limparFormulario();
                    } else {
                        Alerta.mensagem(resposta.titulo, resposta.texto);
                    }
                })
                .catch(error => {
                    Alerta.mensagem('Erro', error.message);
                })
                .finally(() => {
                    Loading.hide();
                });

            return false;
        }
    });

    document.querySelector('body').addEventListener('click', function (e) {
        if (e.target.classList.contains('novamente')) {
            const blocoCalculadoraConteudo = document.querySelector('.bloco_calculadora .container .conteudo');
            blocoCalculadoraConteudo.classList.remove('esconde');

            const abrir = document.getElementById('abrir');
            abrir.classList.add('esconde');

            const resultado = document.querySelector('.bloco_calculadora .container .resultado');
            if (resultado) {
                resultado.remove();
            }

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
        }
    });

    const navbarToggler = document.querySelector('#header_principal div.navbar_hamburguer');
    const navbarMenu = document.querySelector('#header_principal nav .menu');
    const navbarFechar = document.querySelector('#header_principal nav .menu .fechar');

    navbarToggler.addEventListener('click', () => {
        navbarMenu.classList.toggle('show');
        document.body.classList.toggle('show');
    });

    if (navbarFechar) {
        navbarFechar.addEventListener('click', () => {
            navbarMenu.classList.toggle('show');
            document.body.classList.toggle('show');
        });
    }
});
