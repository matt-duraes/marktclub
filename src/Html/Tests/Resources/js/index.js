const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);
const ppe = console.log.bind(console);

window.addEventListener('load', () => {
    const LINK = $('#LINK').value;
    const inputDiretorio = $$('.input_diretorio');
    const inputClasse = $$('.input_classe');

    if (!inputClasse) {
        return;
    }
    /*
    |--------------------------------------------------------------------------
    | ACAO ABRIR GRUPO
    |--------------------------------------------------------------------------
    */
    const botaoAbrirDiretorio = $$('.botao_abrir_diretorio');
    botaoAbrirDiretorio.forEach(botao => {
        botao.addEventListener('click', () => {
            const bloco = botao.closest('.bloco_diretorio');
            bloco.classList.toggle('menu_fechado');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | ACAO PARA FAZER TESTE
    |--------------------------------------------------------------------------
    */
    inputDiretorio.forEach(input => {
        input.addEventListener('change', () => {
            const bloco = input.closest('.bloco_diretorio');
            const lista = bloco.querySelectorAll('input');
            marcarDesmarcarTodos(lista, input.checked);
        });
    });

    inputClasse.forEach(input => {
        input.addEventListener('change', () => {
            const bloco = input.closest('.bloco_diretorio');
            const inputTodo = bloco.querySelector('.input_diretorio');
            const inputTotal = bloco.querySelectorAll('.bloco_menu input').length;
            const inputMarcado = bloco.querySelectorAll('.bloco_menu input:checked').length;
            inputTodo.checked = inputTotal == inputMarcado;
        });
    });

    const marcarDesmarcarTodos = (lista, valor) => {
        lista.forEach(input => {
            input.checked = valor;
        });
    };

    /*
    |--------------------------------------------------------------------------
    | FAZER TESTE
    |--------------------------------------------------------------------------
    */
    const botaoTestar = $('#botao_fazer_teste');
    const botaoCancelar = $('#botao_cancelar_teste');
    const blocoCancelar = $('#bloco_cancelar');
    const blocoLoading = $('#bloco_loading');
    const blocoHeader = $('#bloco_header');
    const blocoOk = $('#bloco_ok');
    const blocoNumeroPassou = $('#bloco_numero_passou');
    const blocoNumeroFalhou = $('#bloco_numero_falhou');
    const blocoNumeroAtual = $('#bloco_numero_atual');
    const blocoNumeroTotal = $('#bloco_numero_total');
    const blocoNomeAtual = $('#bloco_nome_atual');
    const blocoConteudo = $('#bloco_conteudo');
    const botaoGeralTodos = $('#botao_geral_todos');
    const botaoGeralPassou = $('#botao_geral_passou');
    const botaoGeralFalhou = $('#botao_geral_falhou');

    botaoTestar.addEventListener('click', () => {
        executarTeste();
    });

    let fazendoTeste = false;
    const executarTeste = async () => {
        const lista = $$('.input_classe:checked');
        const quantidade = lista.length;

        if (quantidade == 0) {
            return;
        }
        fazendoTeste = true;
        blocoHeader.classList.add('display_none');
        blocoOk.classList.add('display_none');
        botaoTestar.classList.add('display_none');
        blocoLoading.classList.remove('display_none');
        blocoConteudo.innerHTML = '';
        botaoGeralTodos.classList.remove('ativo');
        botaoGeralPassou.classList.remove('ativo');
        botaoGeralFalhou.classList.remove('ativo');

        blocoNumeroTotal.innerText = quantidade;

        let i = 0;
        let input;
        for (; i < quantidade; ++i) {
            if (!fazendoTeste) {
                fecharLoading();
                return;
            }
            input = lista[i];

            const titulo = input.closest('.grupo').querySelector('label p').innerText || '';
            blocoNomeAtual.innerText = titulo;
            blocoNumeroAtual.innerText = i + 1;

            const classe = input.getAttribute('data-class');
            const diretorio = input.getAttribute('data-diretorio');
            const body = new FormData();
            body.append('acao', 'teste');
            body.append('classe', classe);
            body.append('diretorio', diretorio);
            const resposta = await fetch(LINK + '/__tests', {
                method: 'POST',
                body,
            });

            const retorno = await resposta.text();
            let json;
            try {
                json = JSON.parse(retorno);
            } catch (error) {
                json = undefined;
            }

            try {
                if (json) {
                    if (json.test.falhou.length == 0) {
                        input.checked = false;
                    }
                    adicionarJson(json);
                } else {
                    adicionarErro(titulo, diretorio + '\\' + classe, retorno, undefined);
                }
            } catch (error) {
                adicionarErro(titulo, diretorio + '\\' + classe, retorno, error);
            }
        }
        fecharLoading();
    };
    const adicionarJson = dado => {
        let classe = 'article_todos';
        const numeroTodos = dado.test.todos.length;
        const numeroPassou = dado.test.passou.length;
        const numeroFalhou = dado.test.falhou.length;
        if (numeroPassou > 0) {
            classe += ' article_passou';
        }
        if (numeroFalhou > 0) {
            classe += ' article_falhou';
        } else {
            classe += ' display_none';
        }
        let html = `
            <article class="article ${classe}">
                <header>
                    <h1>${dado.arquivo}</h1>
                    <p>${dado.class}</p>
                </header>
            `;
        const falhou = pegarTeste(dado.test.falhou);
        const passou = pegarTeste(dado.test.passou);
        const todos = pegarTeste(dado.test.todos);

        let teste = '';
        let botao = '';
        if (numeroTodos > 0) {
            botao += `<div class="botao botao_interno_todos">Todos</div>`;
            teste += `<div class="bloco_teste bloco_todos teste_numero_todos display_none">${todos}</div>`;
        }
        if (numeroPassou > 0) {
            botao += `<div class="botao botao_interno_passou">Passou</div>`;
            teste += `<div class="bloco_teste bloco_passou display_none">${passou}</div>`;
        }
        if (numeroFalhou > 0) {
            botao += `<div class="botao botao_interno_falhou ativo">Falhou</div>`;
            teste += `<div class="bloco_teste bloco_falhou">${falhou}</div>`;
        }

        html += `<div class="bloco_botao">${botao}</div>`;
        html += teste;
        html += `</article>`;
        blocoConteudo.insertAdjacentHTML('beforeend', html);
    };
    const pegarTeste = lista => {
        let html = '';
        lista.forEach(dado => {
            let header = '',
                teste = '',
                resposta = '';
            if (dado.tipo == 'test') {
                header = `
                    <h2>${dado.nome}</h2>
                    <div class="status">${dado.status}</div>
                    <div class="linha"></div>
                    <div class="metodo metodo_${dado.metodo}">${dado.metodo}</div>
                    <p class="url">${dado.url}</p>
                `;
                teste = pegarCadaMetodo(dado.test);
                resposta = pegarResposta(dado);
            } else if (dado.tipo == 'erro') {
                header = `
                    <h2>${dado.nome}</h2>
                    <div class="linha"></div>
                    <p class="mensagem">${dado.mensagem}</p>
                    <p class="texto">Arquivo: ${dado.arquivo}</p>
                    <p class="texto">Linha: ${dado.linha}</p>
                `;
                teste = pegarRespostaErro(dado);
            } else {
                header = `<h2>${dado.nome}</h2>`;
            }
            html += `
                <div class="teste">
                    <header>
                        ${header}
                    </header>
                    <ul>
                        ${teste}
                        ${resposta}
                    </ul>
                </div>
            `;
        });
        return html;
    };
    const pegarCadaMetodo = teste => {
        let html = '';
        teste.forEach(dado => {
            html += `<li class="linha_teste ${dado.status} teste_numero_${dado.status}"><span>${dado.status}</span><p>${dado.mensagem}</p></li>`;
        });
        return html;
    };
    const pegarResposta = dado => {
        const header = JSON.stringify(dado.header) !== undefined ? JSON.stringify(dado.header, null, 2) : '';
        const parametro = JSON.stringify(dado.parametro) !== undefined ? JSON.stringify(dado.parametro, null, 2) : '';
        const body = JSON.stringify(dado.body) !== undefined ? JSON.stringify(dado.body, null, 2) : '';
        const json = JSON.stringify(dado.json) !== undefined ? JSON.stringify(dado.json, null, 2) : '';
        let resposta = dado.resposta;
        if (typeof dado.resposta === 'object' && JSON.stringify(dado.resposta) !== undefined) {
            resposta = JSON.stringify(dado.resposta, null, 2);
        }

        return `
            <li class="linha_resposta">
                <div class="botao_mostrar botao_mostrar_request">Mostrar request</div>
                <ul class="display_none">
                    <li class="pre">
                        <span>Header:</span>
                        <pre>${header}</pre>
                    </li>
                    <li class="pre">
                        <span>Parametros:</span>
                        <pre>${parametro}</pre>
                    </li>
                    <li class="pre">
                        <span>Body:</span>
                        <pre>${body}</pre>
                    </li>
                    <li class="pre">
                        <span>JSON:</span>
                        <pre>${json}</pre>
                    </li>
                    <li class="pre">
                        <span>Resposta:</span>
                        <pre>${resposta}</pre>
                    </li>
                </ul>
            </li>
        `;
    };
    const pegarRespostaErro = dado => {
        return `
            <li class="linha_resposta linha_erro">
                <ul class="teste_numero_falhou">
                    <li class="pre">
                        <span>Body:</span>
                        <pre>${dado.trace}</pre>
                    </li>
                </ul>
            </li>
        `;
    };

    const adicionarErro = (titulo, classe, retorno, e) => {
        let erro = '';
        if (e !== undefined) {
            erro = `
                <h2>Erro:</h2>
                <pre>${e}</pre>
            `;
        }
        let html = `
            <article class="article article_todos article_falhou">
                <header>
                    <h1>${titulo}</h1>
                    <p>Tests\\${classe}</p>
                </header>
                <div class="bloco_erro bloco_todos bloco_falhou teste_numero_todos">
                    <h2 class="teste_numero_falhou">Resposta:</h2>
                    <iframe srcdoc="${pegarHtmlIframe(retorno)}"></iframe>
                    ${erro}
                </div>
            </article>
        `;
        blocoConteudo.insertAdjacentHTML('beforeend', html);
    };
    const pegarHtmlIframe = html => {
        return html.replace(/\"/g, '&quot;');
    };

    botaoCancelar.addEventListener('click', () => {
        blocoLoading.classList.add('display_none');
        blocoCancelar.classList.remove('display_none');
        fazendoTeste = false;
    });
    const fecharLoading = () => {
        fazendoTeste = false;
        setarDadoHeader();
        blocoCancelar.classList.add('display_none');
        botaoTestar.classList.remove('display_none');
        blocoLoading.classList.add('display_none');
    };
    const setarDadoHeader = () => {
        blocoHeader.classList.remove('display_none');
        const passou = blocoConteudo.querySelectorAll('article.article .teste_numero_todos .teste_numero_passou');
        const falhou = blocoConteudo.querySelectorAll('article.article .teste_numero_todos .teste_numero_falhou');
        blocoNumeroPassou.innerText = passou.length;
        blocoNumeroFalhou.innerText = falhou.length;
        if (falhou.length == 0) {
            blocoOk.classList.remove('display_none');
            return;
        }
        botaoGeralFalhou.classList.add('ativo');
    };

    /*
    |--------------------------------------------------------------------------
    | ACAO CLIQUE VISUALIZACAO
    |--------------------------------------------------------------------------
    */
    botaoGeralTodos.addEventListener('click', () => {
        mostrarListaErro('todos');
    });
    botaoGeralPassou.addEventListener('click', () => {
        mostrarListaErro('passou');
    });
    botaoGeralFalhou.addEventListener('click', () => {
        mostrarListaErro('falhou');
    });
    const mostrarListaErro = acao => {
        botaoGeralTodos.classList.remove('ativo');
        botaoGeralPassou.classList.remove('ativo');
        botaoGeralFalhou.classList.remove('ativo');
        if (acao == 'todos') {
            botaoGeralTodos.classList.add('ativo');
        } else if (acao == 'passou') {
            botaoGeralPassou.classList.add('ativo');
        } else if (acao == 'falhou') {
            botaoGeralFalhou.classList.add('ativo');
        }

        const lista = $$('article.article');
        const classe = 'article_' + acao;
        lista.forEach(article => {
            if (!article.classList.contains(classe)) {
                article.classList.add('display_none');
                return;
            }
            article.classList.remove('display_none');
            const bloco = article.querySelector('.bloco_' + acao);
            const blocoTodos = article.querySelector('.bloco_todos');
            const blocoPassou = article.querySelector('.bloco_passou');
            const blocoFalhou = article.querySelector('.bloco_falhou');
            if (blocoTodos) {
                blocoTodos.classList.add('display_none');
            }
            if (blocoPassou) {
                blocoPassou.classList.add('display_none');
            }
            if (blocoFalhou) {
                blocoFalhou.classList.add('display_none');
            }
            bloco.classList.remove('display_none');
            const botao = article.querySelector('.botao_interno_' + acao);
            const botaoTodos = article.querySelector('.botao_interno_todos');
            const botaoPassou = article.querySelector('.botao_interno_passou');
            const botaoFalhou = article.querySelector('.botao_interno_falhou');

            if (botaoTodos) {
                botaoTodos.classList.remove('ativo');
            }
            if (botaoPassou) {
                botaoPassou.classList.remove('ativo');
            }
            if (botaoFalhou) {
                botaoFalhou.classList.remove('ativo');
            }
            if (botao) {
                botao.classList.add('ativo');
            }
        });
    };

    blocoConteudo.addEventListener('click', e => {
        if (e.target.classList.contains('botao_mostrar_request') || e.target.closest('.botao_mostrar_request')) {
            const bloco = e.target.closest('.linha_resposta').querySelector('ul');
            if (bloco) {
                bloco.classList.toggle('display_none');
            }
        } else if (e.target.classList.contains('botao_interno_todos') || e.target.closest('.botao_interno_todos')) {
            mudarBlocoResposta(e.target.closest('article'), 'todos');
        } else if (e.target.classList.contains('botao_interno_passou') || e.target.closest('.botao_interno_passou')) {
            mudarBlocoResposta(e.target.closest('article'), 'passou');
        } else if (e.target.classList.contains('botao_interno_falhou') || e.target.closest('.botao_interno_falhou')) {
            mudarBlocoResposta(e.target.closest('article'), 'falhou');
        }
    });
    const mudarBlocoResposta = (article, acao) => {
        const botao = article.querySelector('.botao_interno_' + acao);
        const botaoTodos = article.querySelector('.botao_interno_todos');
        const botaoPassou = article.querySelector('.botao_interno_passou');
        const botaoFalhou = article.querySelector('.botao_interno_falhou');
        if (botaoTodos) {
            botaoTodos.classList.remove('ativo');
        }
        if (botaoPassou) {
            botaoPassou.classList.remove('ativo');
        }
        if (botaoFalhou) {
            botaoFalhou.classList.remove('ativo');
        }
        botao.classList.add('ativo');

        const bloco = article.querySelector('.bloco_' + acao);
        const blocoTodos = article.querySelector('.bloco_todos');
        const blocoPassou = article.querySelector('.bloco_passou');
        const blocoFalhou = article.querySelector('.bloco_falhou');
        if (blocoTodos) {
            blocoTodos.classList.add('display_none');
        }
        if (blocoPassou) {
            blocoPassou.classList.add('display_none');
        }
        if (blocoFalhou) {
            blocoFalhou.classList.add('display_none');
        }
        bloco.classList.remove('display_none');
    };
});
