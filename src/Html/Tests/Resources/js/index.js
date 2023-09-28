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
    const blocoRetorno = $('#bloco_retorno');
    const blocoConteudo = $('#bloco_conteudo');
    const blocoNumeroAtual = $('#bloco_numero_atual');
    const blocoNumeroTotal = $('#bloco_numero_total');

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
        botaoTestar.classList.add('display_none');
        blocoRetorno.classList.add('carregando');
        blocoConteudo.innerHTML = '';

        blocoNumeroTotal.innerText = quantidade;

        let i = 0;
        let input;
        for (; i < quantidade; ++i) {
            if (!fazendoTeste) {
                fecharLoading();
                return;
            }

            blocoNumeroAtual.innerText = i + 1;
            input = lista[i];
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
            const json = JSON.parse(retorno);
            const titulo = input.closest('.grupo').querySelector('label p').innerText || '';
            try {
                if (json) {
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
        let html = `
            <article class="article">
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
        if (dado.test.todos.length > 0) {
            botao += `<div class="botao botao_todos ativo">Todos</div>`;
            teste += `<div class="bloco_teste todos">${todos}</div>`;
        }
        if (dado.test.passou.length > 0) {
            botao += `<div class="botao botao_passou">Passou</div>`;
            teste += `<div class="bloco_teste passou display_none">${passou}</div>`;
        }
        if (dado.test.falhou.length > 0) {
            botao += `<div class="botao batao_falhou">Falhou</div>`;
            teste += `<div class="bloco_teste falhou display_none">${falhou}</div>`;
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
            html += `<li class="linha_teste ${dado.status} teste_${dado.status}"><span>${dado.status}</span><p>${dado.mensagem}</p></li>`;
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

    const adicionarErro = (titulo, classe, retorno, e) => {
        let erro = '';
        if (e !== undefined) {
            erro = `
                <h2>Erro:</h2>
                <pre>${e}</pre>
            `;
        }
        let html = `
            <article class="article">
                <header>
                    <h1>${titulo}</h1>
                    <p>Tests\\${classe}</p>
                </header>
                <div class="bloco_erro teste_falhou">
                    <h2>Resposta:</h2>
                    <iframe srcdoc="${pegarHtmlIframe(retorno)}"></iframe>
                    ${erro}
                </div>
            </article>
        `;
        blocoConteudo.insertAdjacentHTML('beforeend', html);
    };
    const pegarHtmlIframe = html => {
        html = html.replace(/\"/g, '&quot;');
        if (!html.includes('<html') || html.includes('PRE PRINT EXIT') || html.includes('VAR_DUMP EXIT')) {
            return `<style>* {color: #FFF;}</style> ${html}`;
        }
        return html;
    };

    $('.input_classe').checked = true;
    setTimeout(() => {
        executarTeste();
    }, 100);

    botaoCancelar.addEventListener('click', () => {
        fecharLoading();
    });
    const fecharLoading = () => {
        fazendoTeste = false;
        botaoTestar.classList.remove('display_none');
        blocoRetorno.classList.remove('carregando');
    };
});
