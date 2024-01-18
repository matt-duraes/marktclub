const $ = document.querySelector.bind(document);
const $$ = document.querySelectorAll.bind(document);
const ppe = console.log.bind(console);

window.addEventListener('load', () => {
    const LINK = $('#LINK').value;

    /*
    |--------------------------------------------------------------------------
    | ACAO PARA FAZER TESTE
    |--------------------------------------------------------------------------
    */
    const inputTodo = $('#input_marcar_todos');
    const inputClasse = $$('.input_classe');

    inputClasse.forEach(input => {
        input.addEventListener('change', () => {
            const inputTotal = document.querySelectorAll('.bloco_menu input').length;
            const inputMarcado = document.querySelectorAll('.bloco_menu input:checked').length;
            inputTodo.checked = inputTotal == inputMarcado;
        });
    });
    inputTodo.addEventListener('change', () => {
        const valor = inputTodo.checked;
        inputClasse.forEach(input => {
            input.checked = valor;
        });
    });

    // /*
    // |--------------------------------------------------------------------------
    // | FAZER TESTE
    // |--------------------------------------------------------------------------
    // */
    const botaoComecar = $('#botao_comecar');
    const botaoCancelar = $('#botao_cancelar');
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

    let executando = false;
    const listaRetorno = {
        retorno: {},
    };

    botaoComecar.addEventListener('click', () => {
        executar();
    });

    botaoCancelar.addEventListener('click', () => {
        blocoLoading.classList.add('display_none');
        blocoCancelar.classList.remove('display_none');
        executando = false;
    });
    const executar = async () => {
        const lista = $$('.input_classe:checked');
        const quantidade = lista.length;

        if (quantidade == 0) {
            return;
        }

        listaRetorno.retorno = {};
        executando = true;
        blocoHeader.classList.add('display_none');
        blocoOk.classList.add('display_none');
        botaoComecar.classList.add('display_none');
        blocoLoading.classList.remove('display_none');
        blocoConteudo.innerHTML = '';

        blocoNomeAtual.innerText = 'Configurando sistema';
        blocoNumeroAtual.innerText = 0;
        blocoNumeroTotal.innerText = quantidade;

        botaoGeralTodos.classList.remove('ativo');
        botaoGeralPassou.classList.remove('ativo');
        botaoGeralFalhou.classList.remove('ativo');

        const bodyConf = new FormData();
        bodyConf.append('acao', 'configurar');
        await fetch(LINK + '/__base', {
            method: 'POST',
            body: bodyConf,
        });

        let i = 0;
        for (; i < quantidade; ++i) {
            if (!executando) {
                fecharLoading();
                return;
            }
            const input = lista[i];
            const tabela = input.value;
            blocoNomeAtual.innerText = tabela;
            blocoNumeroAtual.innerText = i + 1;

            const bodyCriar = new FormData();
            bodyCriar.append('acao', 'criar');
            bodyCriar.append('tabela', tabela);
            const resposta = await fetch(LINK + '/__base', {
                method: 'POST',
                body: bodyCriar,
            });

            const retorno = await resposta.text();
            validarRetorno(tabela, retorno, 'criar');
        }

        i = 0;
        for (; i < quantidade; ++i) {
            if (!executando) {
                fecharLoading();
                return;
            }
            const input = lista[i];
            const tabela = input.value;

            const bodyCriar = new FormData();
            bodyCriar.append('acao', 'relacionar');
            bodyCriar.append('tabela', tabela);
            const resposta = await fetch(LINK + '/__base', {
                method: 'POST',
                body: bodyCriar,
            });

            const retorno = await resposta.text();
            validarRetorno(tabela, retorno, 'relacionar');
        }
        montarHtmlRetorno();
        fecharLoading();
    };

    const validarRetorno = (tabela, retorno, acao) => {
        let json;
        let iframe = false;
        try {
            json = JSON.parse(retorno);
        } catch (error) {
            iframe = true;
            retorno = retorno.replace(/\"/g, '&quot;');
            json = undefined;
        }
        if (listaRetorno.retorno[tabela] == undefined) {
            listaRetorno.retorno[tabela] = {
                tabela,
                relacionar: {},
                criar: {},
            };
        }
        if (json == undefined || json.status == undefined) {
            listaRetorno.retorno[tabela][acao] = { sucesso: false, iframe, mensagem: retorno };
            return;
        } else if (json.status == 'erro') {
            listaRetorno.retorno[tabela][acao] = { sucesso: false, iframe, mensagem: json.mensagem };
            return;
        }
        listaRetorno.retorno[tabela][acao] = { sucesso: true };
    };
    const montarHtmlRetorno = () => {
        let html = '';
        const lista = listaRetorno.retorno;
        for (const item in lista) {
            const criarStatus = lista[item].criar.sucesso;
            const relacionarStatus = lista[item].relacionar.sucesso;

            const criarIframe = lista[item].criar.iframe;
            const relacionarIframe = lista[item].relacionar.iframe;

            const status = criarStatus && relacionarStatus ? true : false;
            const statusClasse = status ? 'passou' : 'falhou';
            const statusTexto = status ? 'Passou' : 'Falhou';
            let criarErro = '';
            if (!criarStatus && criarIframe) {
                criarErro = `<iframe srcdoc="${lista[item].criar.mensagem}"></iframe>`;
            } else if (!criarStatus) {
                criarErro = `<pre class="erro"><span>Criar tabela:</span><br>${lista[item].criar.mensagem}</pre>`;
            }
            let relacionarErro = '';
            if (!relacionarStatus && relacionarIframe) {
                relacionarErro = `<iframe srcdoc="${lista[item].relacionar.mensagem}"></iframe>`;
            } else if (!relacionarStatus) {
                relacionarErro = `<pre class="erro"><span>Criar tabela:</span><br>${lista[item].relacionar.mensagem}</pre>`;
            }
            const displayClasse = status ? 'display_none' : '';
            html += `
                <article class="article ${displayClasse} ${statusClasse}">
                    <header>
                        <h1>${lista[item].tabela}</h1>
                    </header>
                    <div class="retorno ${statusClasse}"><span>${statusTexto}</span><p>Criar tabela</p></div>
                    ${criarErro}
                    ${relacionarErro}
                </article>
            `;
        }
        blocoConteudo.insertAdjacentHTML('afterbegin', html);
    };

    const fecharLoading = () => {
        executando = false;
        setarDadoHeader();
        blocoCancelar.classList.add('display_none');
        botaoComecar.classList.remove('display_none');
        blocoLoading.classList.add('display_none');
    };
    const setarDadoHeader = () => {
        blocoHeader.classList.remove('display_none');
        const passou = blocoConteudo.querySelectorAll('article.article.passou');
        const falhou = blocoConteudo.querySelectorAll('article.article.falhou');
        blocoNumeroPassou.innerText = passou.length;
        blocoNumeroFalhou.innerText = falhou.length;
        if (falhou.length == 0) {
            blocoOk.classList.remove('display_none');
            return;
        }
        botaoGeralFalhou.classList.add('ativo');
    };
    botaoGeralTodos.addEventListener('click', () => {
        botaoGeralTodos.classList.add('ativo');
        botaoGeralPassou.classList.remove('ativo');
        botaoGeralFalhou.classList.remove('ativo');
        blocoOk.classList.add('display_none');
        $$('#bloco_conteudo .article').forEach(item => {
            item.classList.remove('display_none');
        });
    });
    botaoGeralPassou.addEventListener('click', () => {
        botaoGeralTodos.classList.remove('ativo');
        botaoGeralPassou.classList.add('ativo');
        botaoGeralFalhou.classList.remove('ativo');
        blocoOk.classList.add('display_none');
        $$('#bloco_conteudo .article.passou').forEach(item => {
            item.classList.remove('display_none');
        });
        $$('#bloco_conteudo .article.falhou').forEach(item => {
            item.classList.add('display_none');
        });
    });
    botaoGeralFalhou.addEventListener('click', () => {
        botaoGeralTodos.classList.remove('ativo');
        botaoGeralPassou.classList.remove('ativo');
        botaoGeralFalhou.classList.add('ativo');
        blocoOk.classList.add('display_none');
        $$('#bloco_conteudo .article.falhou').forEach(item => {
            item.classList.remove('display_none');
        });
        $$('#bloco_conteudo .article.passou').forEach(item => {
            item.classList.add('display_none');
        });
    });
});
