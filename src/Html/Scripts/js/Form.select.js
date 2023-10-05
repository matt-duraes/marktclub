const bodyFormSelect = document.querySelector('body');
bodyFormSelect.insertAdjacentHTML('beforeend', `<div id="fw_form_select" class="fw_form_hide"></div>`);

let selectChange = {};
let fwFormSelectAbertoAtual, fwFormSelectListaTexto, fwFormSelectValorAtual;
const fwFormBlocoGeralSelect = document.getElementById('fw_form_select');

const formValue = (input, valor, obrigatorio) => {
    obrigatorio = obrigatorio == undefined ? false : true;
    const bloco = input.closest('.bloco_input');
    if (!bloco) {
        return;
    }
    const mensagemFooter = bloco.querySelector('.input_mensagem');
    if (obrigatorio && input.classList.contains('input_obrigatorio') && mensagemFooter && valor == '') {
        mensagemFooter.innerText = 'Campo obrigatório';
        mensagemFooter.classList.add('ativo');
    } else if (mensagemFooter && (!obrigatorio || valor != '')) {
        mensagemFooter.innerText = '';
        mensagemFooter.classList.remove('ativo');
        input.classList.remove('input_obrigatorio_ativo');
    }

    if (bloco.classList.contains('input_select')) {
        formSelectValue(input, valor);
        return;
    } else if (bloco.classList.contains('input_url')) {
        valor = valor.replace(/^(http:\/\/|https:\/\/)/i, '');
    }
    input.value = valor;
};
/**
 * Mudar o valor do select
 *
 * @param {element} select O Select que deseja mudar o valor
 * @param {string} valor O valor novo para o select
 */
const formSelectValue = (select, valor) => {
    const bloco = select.closest('.input_select');
    const inputValue = bloco.querySelector('.input_select_value');
    const inputTexto = bloco.querySelector('.input_select_texto');
    const texto = bloco.querySelector('.option .lista[data-value="' + valor + '"]');
    if (!texto || valor == '') {
        inputTexto.value = '';
        inputValue.value = '';
        return;
    }
    inputTexto.value = texto.innerText;
    inputValue.value = valor;
};
/**
 * Adiciona um loading no select
 *
 * @param {element} select O Select que deseja mudar o valor
 */
const formSelectLoading = select => {
    const bloco = select.closest('.input_select');
    const blocoUl = bloco.querySelector('ul');
    const blocoTitulo = bloco.querySelector('.input_select_texto');
    select.value = '';
    blocoTitulo.value = '';
    blocoUl.innerHTML = `<li class="lista " data-value>Carregando</li>`;
};
/**
 * Muda a lista de option do select
 *
 * @param {element} select O Select que deseja mudar o valor
 * @param {object} lista Objeto com a nova lista {"indice":"valor"}
 * @param {string} valor Valor padrão para setar no select
 */
const formSelectOption = (select, lista, valor) => {
    const bloco = select.closest('.input_select');
    const blocoUl = bloco.querySelector('ul');
    const blocoTitulo = bloco.querySelector('.input_select_texto');
    let html = '';
    let indice, valorIndice, valorTitulo;
    for (indice in lista) {
        if (valorIndice == undefined) {
            valorIndice = indice;
            valorTitulo = indice == '' ? '' : lista[indice];
        }
        html += `<li class="lista " data-value="${indice}">${lista[indice]}</li>`;
    }
    select.value = valorIndice;
    blocoTitulo.value = valorTitulo;
    blocoUl.innerHTML = html;
    if (valor != undefined) {
        formValue(select, valor);
    }
    if (!fwFormBlocoGeralSelect.classList.contains('fw_form_hide') && fwFormSelectAbertoAtual) {
        // Recarregar
        fwFormSelectAbrirListaOption(fwFormSelectAbertoAtual, false, false, true);
    }
};

/**
 * Remove os acentos da string
 *
 * @param {string} texto Texto que deseja remover o acento
 * @returns {string}
 */
const fwFormSelectRemoverAcento = texto => {
    texto = texto.toUpperCase().replace(/[àáâã]/i, 'a');
    texto = texto.replace(/[èéê]/i, 'e');
    texto = texto.replace(/[íìî]/i, 'i');
    texto = texto.replace(/[óòôõ]/i, 'o');
    texto = texto.replace(/[úùû]/i, 'u');
    texto = texto.replace(/[Ç]/, 'C');
    return texto.replace(/[ç]/, 'c');
};

/**
 * Buscar por aproximação
 *
 * @param {elemento} select O elemento HTML com o select
 * @returns
 */
const fwFormSelectBuscarPorAproximacao = select => {
    const liAtual = fwFormBlocoGeralSelect.querySelector('li.hover');
    if (liAtual) {
        liAtual.classList.remove('hover');
    }
    const valorTexto = select.querySelector('.input_select_texto').value.trim();
    if (valorTexto == '') {
        return '';
    }
    const valorLimpo = fwFormSelectRemoverAcento(valorTexto);
    const expressao = new RegExp('^' + valorTexto, 'i');
    const expressaoLimpa = new RegExp('^' + valorLimpo, 'i');
    let i = 0;
    for (const [key, value] of Object.entries(fwFormSelectListaTexto)) {
        if (expressao.test(value) || expressaoLimpa.test(fwFormSelectRemoverAcento(value))) {
            fwFormSelectMarcarLiAproximacao(i);
            return false;
        }
        i++;
    }
};

/**
 * Marca a li com valor mais proximo do digitado
 *
 * @param {int} i Numero da li que será marcada
 */
const fwFormSelectMarcarLiAproximacao = i => {
    const liAtual = fwFormBlocoGeralSelect.querySelector('li.hover');
    const liNova = fwFormBlocoGeralSelect.querySelectorAll('li')[i] || null;
    if (liAtual) {
        liAtual.classList.remove('hover');
    }
    if (liNova) {
        liNova.classList.add('hover');
    }
    fwFormSelectPosicionaScrollNoLiHover();
};

/**
 * Abre a lista de option
 *
 * @param {*} select
 * @param {*} hover
 * @param {*} buscar
 * @param {*} setarCursor
 */
const fwFormSelectAbrirListaOption = (select, hover, buscar, setarCursor) => {
    fwFormSelectAbertoAtual = select;
    fwFormSelectValorAtual = select.querySelector('input.input_select_texto').value;

    const listaOption = select.querySelectorAll('ul.option li');
    const valorReal = select.querySelector('input.input_select_value').value;
    if (hover) {
        hover = 'hover';
    } else {
        hover = '';
    }

    const inputTexto = select.querySelector('input.input_select_texto');
    const inputValue = select.querySelector('input.input_select_value');
    const inputMensagem = select.querySelector('.input_mensagem');
    if (setarCursor) {
        fwFormPosicionarCursorInputSelecionado(inputTexto);
    }

    inputTexto.classList.remove('input_obrigatorio_ativo');
    if (inputMensagem) {
        inputMensagem.innerText = '';
        inputMensagem.classList.remove('ativo');
    }

    let texto, valor, selected, classe;
    let html = '';
    let listaTexto = [];
    listaOption.forEach((li, i) => {
        valor = li.getAttribute('data-value');
        texto = li.innerText;
        if (valorReal == valor) {
            selected = 'selected ' + hover;
        } else {
            selected = '';
        }
        if (li.classList.contains('disabled')) {
            classe = 'disabled';
        } else if (li.classList.contains('loading')) {
            classe = 'loading';
        } else {
            classe = 'lista';
        }
        html +=
            `
            <li title="` +
            texto +
            `" class="` +
            classe +
            ` ` +
            selected +
            `"
            data-ind="` +
            i +
            `" data-value="` +
            valor +
            `">` +
            texto +
            `</li>
        `;
        listaTexto[valor] = texto;
    });
    fwFormSelectListaTexto = listaTexto;
    if (html != '') {
        fwFormBlocoGeralSelect.innerHTML = '<ul class="option">' + html + '</ul>';
        fwFormBlocoGeralSelect.setAttribute('data-id', inputValue.getAttribute('id'));
        fwFormSelectPosicionarUl(select);
        fwFormSelectPosicionarScrollCentral();

        if (buscar) {
            fwFormSelectBuscarPorAproximacao(select);
        }
    }
};

/**
 * Passa para o proximo li ao clicar na seta pra baixo
 *
 * @param {elemento} select O bloco do select escolhido
 * @param {*} option O option aberto
 */
const fwFormSelecionarProximoOption = (select, option) => {
    const inputTexto = select.querySelector('input[type=text]');
    fwFormPosicionarCursorInputSelecionado(inputTexto);

    const lista = option.querySelectorAll('li');
    const quantidade = lista.length;

    let blocoAtual = option.querySelector('li.hover');
    if (!blocoAtual) {
        lista[0].classList.add('hover');
        fwFormSelectPosicionarScroll('proximo');
        return;
    }
    const indiceAtual = parseInt(blocoAtual.getAttribute('data-ind')) + 1;
    let blocoNovo;
    if (indiceAtual >= quantidade) {
        blocoNovo = lista[0];
    } else {
        blocoNovo = lista[indiceAtual];
    }
    blocoAtual.classList.remove('hover');
    blocoNovo.classList.add('hover');
    fwFormSelectPosicionarScroll('proximo');
};

/**
 * Passa para o li anterior ao clicar na seta pra cima
 *
 * @param {elemento} select O bloco do select escolhido
 * @param {*} option O option aberto
 */
const fwFormSelecionarAnteriorOption = (select, option) => {
    const inputTexto = select.querySelector('input[type=text]');
    fwFormPosicionarCursorInputSelecionado(inputTexto);

    const lista = option.querySelectorAll('li');
    const quantidade = lista.length;

    let blocoAtual = option.querySelector('li.hover');
    if (!blocoAtual) {
        lista[quantidade - 1].classList.add('hover');
        fwFormSelectPosicionarScroll('anterior');
        return false;
    }
    const indiceAtual = parseInt(blocoAtual.getAttribute('data-ind')) + 1;
    let blocoNovo;
    if (indiceAtual == 1) {
        blocoNovo = lista[quantidade - 1];
    } else {
        blocoNovo = lista[indiceAtual - 2];
    }
    blocoAtual.classList.remove('hover');
    blocoNovo.classList.add('hover');
    fwFormSelectPosicionarScroll('anterior');
};

/**
 * Seta o scroll para não deixar o li hover sair da tela
 *
 * @param {string} acao Acao podendo ser proximo ou anterior
 */
const fwFormSelectPosicionarScroll = acao => {
    const liHover = fwFormBlocoGeralSelect.querySelector('li.hover');
    liHover.scrollIntoView();
    // const posicaoLiHover = liHover.getBoundingClientRect();
    // const liHeight = posicaoLiHover.height;
    // const liTop = liHover.offsetTop;
    // const blocoUl = fwFormBlocoGeralSelect.querySelector('ul.option');
    // const posicaoBlocoUl = blocoUl.getBoundingClientRect();
    // const blocoUlHeight = posicaoBlocoUl.height;
    // const scrollTop = blocoUl.scrollTop;
    // const scrollFim = scrollTop + blocoUlHeight;
    // let posicaoComparacao = liTop;
    // if (acao == 'proximo') {
    //     posicaoComparacao = liTop + liHeight;
    // }
    // if (acao == 'anterior' && (posicaoComparacao < scrollTop || posicaoComparacao > scrollFim)) {
    //     blocoUl.scrollTop = liTop;
    // } else if (acao == 'proximo' && (posicaoComparacao < scrollTop || posicaoComparacao > scrollFim)) {
    //     blocoUl.scrollTop = liTop - (blocoUlHeight - liHeight);
    // }
};

/**
 * Coloca o scroll no li selecionado
 */
const fwFormSelectPosicionaScrollNoLiHover = () => {
    const liHover = fwFormBlocoGeralSelect.querySelector('li.hover');
    const blocoUl = fwFormBlocoGeralSelect.querySelector('ul.option');
    blocoUl.scrollTop = liHover.offsetTop;
};

/**
 * Posiciona o scroll no centro do option
 */
const fwFormSelectPosicionarScrollCentral = () => {
    const liSelected = fwFormBlocoGeralSelect.querySelector('li.selected');
    if (!liSelected) {
        return;
    }
    const blocoUl = fwFormBlocoGeralSelect.querySelector('ul.option');
    const blocoUlHeight = blocoUl.getBoundingClientRect().height;
    blocoUl.scrollTop = liSelected.offsetTop - blocoUlHeight / 2 + 19;
};

/**
 * Posiciona o option em cima ou em baixo do input
 */
const fwFormSelectPosicionarUl = select => {
    const inputSelect = select.querySelector('.input_select_texto');
    const posicaoSelect = inputSelect.getBoundingClientRect();
    const alturaScroll = document.querySelector('html').scrollTop;
    const windowHeight = window.innerHeight;

    fwFormBlocoGeralSelect.classList.remove('fw_form_hide');
    fwFormBlocoGeralSelect.style.width = posicaoSelect.width + 'px';
    fwFormBlocoGeralSelect.style.left = posicaoSelect.left + 'px';

    const blocoUl = fwFormBlocoGeralSelect.querySelector('ul');
    const posicaoBlocoUl = blocoUl.getBoundingClientRect();

    if (posicaoSelect.top + posicaoSelect.height > windowHeight - posicaoBlocoUl.height - 30) {
        fwFormBlocoGeralSelect.style.top = posicaoSelect.top + alturaScroll - (posicaoBlocoUl.height + 10) + 'px';
    } else {
        fwFormBlocoGeralSelect.style.top = posicaoSelect.top + alturaScroll + posicaoSelect.height + 'px';
    }
};

const fwFormSelectSelecionarOption = (select, option) => {
    if (!option) {
        return;
    }
    const blocoHover = option.querySelector('li.hover');
    const inputTexto = select.querySelector('.input_select_texto');
    const inputValue = select.querySelector('.input_select_value');

    if (!blocoHover || !select) {
        inputTexto.value = '';
        inputValue.value = '';
        fwFormBlocoSelectFechar();
        return false;
    }

    const blocoSelected = option.querySelector('li.selected');
    if (blocoSelected) {
        blocoSelected.classList.remove('selected');
    }

    blocoHover.classList.add('selected');
    blocoHover.classList.remove('hover');

    let texto = blocoHover.getAttribute('title').trim();
    const value = blocoHover.getAttribute('data-value').trim();
    if (value == '') {
        texto = '';
    }

    inputTexto.value = texto;
    inputValue.value = value;

    fwFormBlocoSelectFechar();
};

/**
 * Fecha o option e valida se o valor está ok
 */
const fwFormBlocoSelectFechar = async () => {
    if (!fwFormSelectAbertoAtual) {
        return false;
    }
    const inputTexto = fwFormSelectAbertoAtual.querySelector('.input_select_texto');
    const inputValue = fwFormSelectAbertoAtual.querySelector('.input_select_value');

    if (inputTexto.value != '') {
        let valorExiste = false;
        const valorTexto = inputTexto.value.trim();
        for (const [key, value] of Object.entries(fwFormSelectListaTexto)) {
            if (value != '' && value == valorTexto) {
                inputTexto.value = value;
                inputValue.value = key;
                valorExiste = true;
                break;
            }
        }
        if (!valorExiste) {
            inputTexto.value = '';
            inputValue.value = '';
        }
    } else if (inputTexto.value == '' || inputValue.value == '') {
        inputTexto.value = '';
        inputValue.value = '';
    }

    if (fwFormSelectValorAtual != inputTexto.value) {
        inputValue.dispatchEvent(new Event('formChange'));
    }

    await fwFormSelectObrigatorio(fwFormSelectAbertoAtual);
    fwFormSelectAbertoAtual = null;
    fwFormBlocoGeralSelect.setAttribute('data-id', '');
    fwFormBlocoGeralSelect.style = '';
    fwFormBlocoGeralSelect.innerHTML = '';
};

/**
 * Verifica se o input é obrigatório
 *
 * @param {elemento} input Input que deseja verificar se é obrigatório
 * @returns {Promise}
 */
const fwFormSelectObrigatorio = function (input) {
    return new Promise((resolve, reject) => {
        if (!input) {
            reject(false);
        }
        if (!input.classList.contains('input_select')) {
            input = input.closest('.input_select');
        }
        if (!input) {
            reject(false);
        }
        const inputValue = input.querySelector('.input_select_value');
        const inputTexto = input.querySelector('.input_select_texto');
        const inputMensagem = input.querySelector('.input_mensagem');

        if (
            inputValue &&
            inputTexto &&
            inputMensagem &&
            inputTexto.classList.contains('input_obrigatorio') &&
            inputValue.value == ''
        ) {
            inputTexto.classList.add('input_obrigatorio_ativo');
            inputMensagem.innerText = 'Campo obrigatório';
            inputMensagem.classList.add('ativo');
        } else if (
            inputValue &&
            inputMensagem &&
            inputTexto &&
            inputTexto.classList.contains('input_obrigatorio') &&
            inputValue.value != ''
        ) {
            inputTexto.classList.remove('input_obrigatorio_ativo');
            inputMensagem.innerText = '';
            inputMensagem.classList.remove('ativo');
        }
        resolve(false);
    });
};

/**
 * Posiciona o cursor no input
 */
const fwFormPosicionarCursorInputSelecionado = input => {
    const posicaoCursor = input.selectionStart;
    setTimeout(() => {
        input.selectionStart = posicaoCursor;
        input.selectionEnd = posicaoCursor;
    }, 1);
};

/**
 * Fecha o option caso clique no body
 */
bodyFormSelect.addEventListener('click', e => {
    if (
        fwFormSelectAbertoAtual &&
        !e.target.classList.contains('input_select_texto') &&
        !e.target.closest('#fw_form_select') &&
        e.target.closest('.input_select') != fwFormSelectAbertoAtual
    ) {
        fwFormBlocoSelectFechar();
    }
});

/*
|--------------------------------------------------------------------------
| CARREGA SCRIPT
|--------------------------------------------------------------------------
*/
fwFormLoadingSelect = bloco => {
    // Bloco com um input e um select
    const inputInputSelectLista = bloco.querySelectorAll(`
        .bloco_input_select .input_input input,
        .bloco_input_select .input_select .input_select_texto
    `);

    if (inputInputSelectLista.length > 0) {
        [].forEach.call(inputInputSelectLista, input => {
            input.addEventListener('focus', () => {
                const bloco = input.closest('.bloco_input_select');
                bloco.classList.add('input_focus');
            });
            input.addEventListener('blur', () => {
                const bloco = input.closest('.bloco_input_select');
                bloco.classList.remove('input_focus');
            });
        });
    }

    // Bloco com select
    const inputSelectLista = bloco.querySelectorAll('.input_select');
    if (inputSelectLista.length > 0) {
        fwFormBlocoGeralSelect.addEventListener('click', e => {
            if (e.target.classList.contains('lista')) {
                const option = fwFormBlocoGeralSelect.querySelector('.option');
                e.target.classList.add('hover');
                return fwFormSelectSelecionarOption(fwFormSelectAbertoAtual, option);
            }
        });
        fwFormBlocoGeralSelect.addEventListener('mousemove', e => {
            if (e.target.classList.contains('lista')) {
                const liHoverAtual = fwFormBlocoGeralSelect.querySelector('li.hover');
                if (liHoverAtual) {
                    liHoverAtual.classList.remove('hover');
                }
                e.target.classList.add('hover');
            }
        });
        let inputIconeSelect;
        [].forEach.call(inputSelectLista, select => {
            const inputSelect = select.querySelector('.input_select_texto');
            const change = inputSelect.getAttribute('data-onchange') || '';
            if (change) {
                const inputSelectTextoId = inputSelect.getAttribute('id');
                selectChange[inputSelectTextoId] = change;
            }
            if (inputSelect) {
                inputSelect.addEventListener('click', () => {
                    if (select != fwFormSelectAbertoAtual) {
                        return fwFormSelectAbrirListaOption(select, false, false, true);
                    }
                });
                inputSelect.addEventListener('focus', e => {
                    inputSelect.select();
                    const option = fwFormBlocoGeralSelect.querySelector('.option');
                    if (!option) {
                        return fwFormSelectAbrirListaOption(select, true);
                    }
                });
            }

            inputIconeSelect = select.querySelector('.input_icone');
            if (inputIconeSelect) {
                inputIconeSelect.addEventListener('click', e => {
                    inputSelect.focus();
                });
            }
            select.addEventListener('keydown', e => {
                const option = fwFormBlocoGeralSelect.querySelector('.option');
                let key = e.keyCode;
                if ((key == 38 || key == 40) && !option) {
                    e.preventDefault();
                    return fwFormSelectAbrirListaOption(select, true, false, true);
                } else if (key == 40 && option) {
                    e.preventDefault();
                    return fwFormSelecionarProximoOption(select, option);
                } else if (key == 38 && option) {
                    e.preventDefault();
                    return fwFormSelecionarAnteriorOption(select, option);
                } else if (key == 13 && option) {
                    e.preventDefault();
                    return fwFormSelectSelecionarOption(select, option);
                } else if (key == 9 && option) {
                    return fwFormBlocoSelectFechar();
                } else if (key == 27) {
                    return fwFormBlocoSelectFechar();
                }
            });
            select.addEventListener('keyup', e => {
                const option = fwFormBlocoGeralSelect.querySelector('.option');
                let key = e.key;

                if (key == 'Unidentified') {
                    key = 'A';
                }

                if (
                    (/^[a-zA-Z0-9à-úÀ-Ú\!\@\#\$\%\&\*\(\)\[\]\{\}\_\-\:\;\.\,\?<>\ ]{1}$/.test(key) ||
                        key == 'Backspace' ||
                        key == 'Delete') &&
                    !option
                ) {
                    return fwFormSelectAbrirListaOption(select, true, true, true);
                } else if (
                    (/^[a-zA-Z0-9à-úÀ-Ú\!\@\#\$\%\&\*\(\)\[\]\{\}\_\-\:\;\.\,\?<>\ ]{1}$/.test(key) ||
                        key == 'Backspace' ||
                        key == 'Delete') &&
                    option
                ) {
                    fwFormSelectBuscarPorAproximacao(select);
                }
            });
        });
    }
};
