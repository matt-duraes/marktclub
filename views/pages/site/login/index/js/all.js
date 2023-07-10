// @template "login"
// @system "Loading"
// @system "Alerta"
// @system "Mascara"
// @system "Form"

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
});
