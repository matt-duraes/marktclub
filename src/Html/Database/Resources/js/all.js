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

    botaoComecar.addEventListener('click', () => {
        executar();
    });

    let executando = false;
    const executar = async () => {
        const lista = $$('.input_classe:checked');
        const quantidade = lista.length;

        if (quantidade == 0) {
            return;
        }
        executando = true;
        blocoHeader.classList.add('display_none');
        blocoOk.classList.add('display_none');
        botaoComecar.classList.add('display_none');
        blocoLoading.classList.remove('display_none');
        blocoConteudo.innerHTML = '';

        blocoNomeAtual.innerText = 'Configurando sistema';
        blocoNumeroAtual.innerText = 0;
        blocoNumeroTotal.innerText = quantidade;

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

            blocoNomeAtual.innerText = input.value;
            blocoNumeroAtual.innerText = i + 1;

            const bodyCriar = new FormData();
            bodyCriar.append('acao', 'criar');
            bodyCriar.append('tabela', input.value);
            const resposta = await fetch(LINK + '/__base', {
                method: 'POST',
                body: bodyCriar,
            });

            const retorno = await resposta.text();
            validarRetorno(retorno);
        }

        i = 0;
        for (; i < quantidade; ++i) {
            if (!executando) {
                fecharLoading();
                return;
            }
            const input = lista[i];

            const bodyCriar = new FormData();
            bodyCriar.append('acao', 'relacionar');
            bodyCriar.append('tabela', input.value);
            const resposta = await fetch(LINK + '/__base', {
                method: 'POST',
                body: bodyCriar,
            });

            const retorno = await resposta.text();
            validarRetorno(retorno);
        }
        fecharLoading();
    };

    const validarRetorno = retorno => {
        let json;
        try {
            json = JSON.parse(retorno);
        } catch (error) {
            json = undefined;
        }
        if (json == undefined || json.status == undefined) {
            return;
        } else if (json.status == 'erro') {
            return;
        }
    };

    const fecharLoading = () => {
        executando = false;
        setarDadoHeader();
        blocoCancelar.classList.add('display_none');
        botaoComecar.classList.remove('display_none');
        blocoLoading.classList.add('display_none');
    };
    const setarDadoHeader = () => {
        // blocoHeader.classList.remove('display_none');
        // const passou = blocoConteudo.querySelectorAll('article.article .teste_numero_todos .teste_numero_passou');
        // const falhou = blocoConteudo.querySelectorAll('article.article .teste_numero_todos .teste_numero_falhou');
        // blocoNumeroPassou.innerText = passou.length;
        // blocoNumeroFalhou.innerText = falhou.length;
        // if (falhou.length == 0) {
        //     blocoOk.classList.remove('display_none');
        //     return;
        // }
        // botaoGeralFalhou.classList.add('ativo');
    };
});
