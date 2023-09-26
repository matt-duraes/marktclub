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
                adicionarErro(diretorio, classe);
            }
        }
        fecharLoading();
    };
    const adicionarJson = json => {
        ppe(json);
    };
    const adicionarErro = (diretorio, classe) => {
        ppe(diretorio, classe);
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
