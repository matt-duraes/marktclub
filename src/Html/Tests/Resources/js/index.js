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
            try {
                adicionarJson(await resposta.json());
            } catch (error) {
                adicionarErro(diretorio, classe, error);
            }
        }
        fecharLoading();
    };
    const adicionarJson = dado => {
        let html = `
            <article>
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
        blocoConteudo.insertAdjacentHTML('afterbegin', html);
    };
    const pegarTeste = lista => {
        let html = '';
        lista.forEach(dado => {
            let header = '';
            if (dado.tipo == 'test') {
                header = `
                    <h2>${dado.nome}</h2>
                    <div class="status">${dado.status}</div>
                    <div class="linha"></div>
                    <div class="metodo metodo_${dado.metodo}">${dado.metodo}</div>
                    <p class="url">${dado.url}</p>
                `;
            } else {
                header = `<h2>${dado.nome}</h2>`;
            }
            html += `
                <div class="teste">
                    <header>
                        ${header}
                    </header>
                    <ul>

                    </ul>
                </div>
            `;
        });
        return html;
    };

    const adicionarErro = (diretorio, classe, e) => {
        ppe(diretorio, classe, e);
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
