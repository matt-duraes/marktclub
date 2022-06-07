const bodyFormGeral = document.querySelector('body');

let fwFormInputDataAberto = false;
const fwFormInArray = function (needle, haystack) {
    const tamanho = haystack.length;
    let i;
    for (i = 0; i < tamanho; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
};

const fwFormKeyGeral = [
    'Delete',
    'Tab',
    'Shift',
    'Control',
    'Alt',
    'Escape',
    'Meta',
    'CapsLock',
    'End',
    'Home',
    'ArrowLeft',
    'ArrowUp',
    'ArrowRight',
    'ArrowDown',
    'Backspace',
    'F1',
    'F2',
    'F3',
    'F4',
    'F5',
    'F6',
    'F7',
    'F8',
    'F9',
    'F10',
    'F11',
    'F12',
];
const fwFormKeyControl = ['a', 'x', 'c', 'v', 'z', 'A', 'X', 'C', 'V', 'Z'];

fwFormJsonParse = json => {
    try {
        JSON.parse(json);
        return JSON.parse(json);
    } catch (e) {
        return json;
    }
};

/*
|--------------------------------------------------------------------------
| IMAGEM
|--------------------------------------------------------------------------
*/
const LINK_FORM = document.querySelector('#LINK') ? document.querySelector('#LINK').value : '';
const GaleriaFormImagem = new Galeria(
    document.querySelector('#bloco_app_add'),
    '.fw_form_imagem_galeria',
    '.fw_form_imagem .fw_imagem_visualizar'
);
/**
 * Verifica se existe form de imagem
 */
const fwFormImagemEscolherImagem = async (
    Upload,
    figure,
    input,
    botaoDeletar,
    botaoVisualizar,
    blocoIcone,
    blocoFigure
) => {
    const botao = await Upload.botao();
    botao.addEventListener('click', () => {
        const hash = Upload.id();
        const link = Upload.arquivo();

        blocoFigure.style.backgroundImage = `url(${link})`;
        input.value = hash;
        blocoIcone.classList.add('fw_imagem_hide');
        botaoDeletar.classList.remove('fw_imagem_hide');
        botaoVisualizar.classList.remove('fw_imagem_hide');
        if (figure.classList.contains('fw_form_imagem_galeria')) {
            GaleriaFormImagem.atualizar(figure, { imagem: link });
        } else {
            GaleriaFormImagem.add(figure, { imagem: link });
        }
        Upload.fechar();
    });
};
const fwFormImagemRemoverImagem = (figure, input, botaoDeletar, botaoVisualizar, blocoIcone, blocoFigure) => {
    input.value = '';
    blocoIcone.classList.remove('fw_imagem_hide');
    botaoDeletar.classList.add('fw_imagem_hide');
    botaoVisualizar.classList.add('fw_imagem_hide');
    blocoFigure.style.backgroundImage = '';
    GaleriaFormImagem.remover(figure);
};
const fwFormImagem = document.querySelectorAll('.form_geral .fw_form_imagem');
if (fwFormImagem.length > 0) {
    fwFormImagem.forEach(bloco => {
        const input = bloco.querySelector('input');
        const blocoIcone = bloco.querySelector('.fw_imagem_conteudo .fw_imagem_icone');
        const blocoFigure = bloco.querySelector('.fw_imagem_conteudo .fw_imagem_figure');
        const botaoUpload = bloco.querySelector('.fw_imagem_upload');
        const botaoVisualizar = bloco.querySelector('.fw_imagem_visualizar');
        const botaoDeletar = bloco.querySelector('.fw_imagem_remover');
        const grupo = bloco.getAttribute('data-diretorio');

        const Upload = new ArquivoUpload(grupo);
        fwFormImagemEscolherImagem(Upload, bloco, input, botaoDeletar, botaoVisualizar, blocoIcone, blocoFigure);
        botaoUpload.addEventListener('click', () => {
            Upload.abrir();
        });
        botaoDeletar.addEventListener('click', async () => {
            const mensagem = await Alerta.confirmar(
                'Remover imagem',
                'Tem certeza que deseja remover essa imagem?',
                '!'
            );
            if (mensagem) {
                fwFormImagemRemoverImagem(bloco, input, botaoDeletar, botaoVisualizar, blocoIcone, blocoFigure);
            }
        });
    });
}

/*/
|--------------------------------------------------------------------------
| GERANDO O AUTOCOMPLETE PERSONALIZADO
|--------------------------------------------------------------------------
/*/

let autocompleteInputAtual,
    autocompleteValorAtual,
    autocompleteAction = {},
    autocompleteUltimaBuscaRealizada,
    AutocompleteController;
const fwFormAutocompleteSelecionar = (input, option) => {
    let liHover = option.querySelector('li.hover');
    if (!liHover) {
        liHover = option.querySelectorAll('li.lista');
        if (liHover.length == 0) {
            return fwFormAutocompleteFechar();
        }
        liHover = liHover[0];
    }
    const valor = liHover.getAttribute('data-value');
    input.value = valor;
    return fwFormAutocompleteFechar();
};

const fwFormAutocompleteBuscarSugestao = input => {
    const bloco = input.closest('.input_autocomplete');
    const id = bloco.getAttribute('id');
    const valor = input.value;
    let request = bloco.getAttribute('data-request');
    if (request) {
        request = fwFormJsonParse(window.atob(request));
    }
    autocompleteValorAtual = valor;
    const link = autocompleteAction[id] || '';
    if (link == '') {
        fwFormAutocompleteFechar();
        return false;
    }
    fwFormAutocompleteLoading(input);

    autocompleteUltimaBuscaRealizada = valor;
    setTimeout(() => {
        if (autocompleteUltimaBuscaRealizada == valor) {
            fwFormAutocompleteAjaxAbort().then(signal => {
                let body = new FormData();
                if (typeof request === 'object') {
                    Object.entries(request).forEach(([key, id]) => {
                        body.append(key, document.getElementById(id).value);
                    });
                }
                body.append('pesquisa', valor);
                fetch(link, {
                    body,
                    method: 'POST',
                    signal,
                })
                    .then(response => {
                        response
                            .json()
                            .then(response => {
                                if (response.erro == false) {
                                    fwFormAutocompleteHtml(response.dado.lista);
                                } else {
                                    fwFormAutocompleteFechar();
                                }
                            })
                            .catch(() => {
                                fwFormAutocompleteFechar();
                            });
                    })
                    .catch(() => {
                        fwFormAutocompleteFechar();
                    });
            });
        }
    }, 200);
};
const fwFormAutocompleteAjaxAbort = async () => {
    if (AutocompleteController) {
        AutocompleteController.abort();
    }
    AutocompleteController = new AbortController();
    return AutocompleteController.signal;
};
const fwFormAutocompleteProximoOption = (option, input) => {
    const lista = option.querySelectorAll('li');
    const quantidade = lista.length;

    fwFormPosicionarCursorInputSelecionado(input);

    let blocoAtual = option.querySelector('li.hover');
    if (!blocoAtual) {
        lista[0].classList.add('hover');
        fwFormAutocompletePosicionarScroll('proximo');
        return true;
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
    fwFormAutocompletePosicionarScroll('proximo');
};
const fwFormAutocompleteAnteriorOption = (option, input) => {
    const lista = option.querySelectorAll('li');
    const quantidade = lista.length;

    fwFormPosicionarCursorInputSelecionado(input);

    let blocoAtual = option.querySelector('li.hover');
    if (!blocoAtual) {
        lista[quantidade - 1].classList.add('hover');
        fwFormAutocompletePosicionarScroll('anterior');
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
    fwFormAutocompletePosicionarScroll('anterior');
};
const fwFormAutocompletePosicionarScroll = acao => {
    const liHover = blocoGeralAutocomplete.querySelector('li.hover');
    const posicaoLiHover = liHover.getBoundingClientRect();
    const liHeight = posicaoLiHover.height;
    const liTop = liHover.offsetTop;
    const blocoUl = blocoGeralAutocomplete.querySelector('ul.option');
    const posicaoBlocoUl = blocoUl.getBoundingClientRect();
    const blocoUlHeight = posicaoBlocoUl.height;
    const scrollTop = blocoUl.scrollTop;
    const scrollFim = scrollTop + blocoUlHeight;
    let posicaoComparacao = liTop;
    if (acao == 'proximo') {
        posicaoComparacao = liTop + liHeight;
    }
    if (acao == 'anterior' && (posicaoComparacao < scrollTop || posicaoComparacao > scrollFim)) {
        blocoUl.scrollTop = liTop;
    } else if (acao == 'proximo' && (posicaoComparacao < scrollTop || posicaoComparacao > scrollFim)) {
        blocoUl.scrollTop = liTop - (blocoUlHeight - liHeight);
    }
};
const fwFormAutocompleteHtml = lista => {
    let html = '';
    Object.entries(lista).forEach(([key, value], i) => {
        html +=
            `
            <li "data-ind="` +
            i +
            `" data-value="` +
            key +
            `" title="` +
            key +
            `" class="lista">
                ` +
            value +
            `
            </li>
        `;
    });
    if (html != '') {
        blocoGeralAutocomplete.innerHTML = '<ul class="option">' + html + '</ul>';
    } else {
        fwFormAutocompleteFechar();
    }
};
const fwFormAutocompleteFechar = () => {
    blocoGeralAutocomplete.innerHTML = '';
    blocoGeralAutocomplete.style.display = 'none';
    if (AutocompleteController) {
        AutocompleteController.abort();
    }
};
const fwFormAutocompleteLoading = input => {
    const option = blocoGeralAutocomplete.querySelector('ul.option');
    autocompleteInputAtual = input;
    if (option) {
        return option.classList.add('loading_geral');
    }
    blocoGeralAutocomplete.innerHTML = `
        <ul class="option loading_geral"><li class="loading">CARREGANDO SUGESTÕES</li></ul>
    `;

    const posicaoInput = input.getBoundingClientRect();
    const alturaScroll = document.querySelector('html').scrollTop;
    const windowHeight = window.innerHeight;

    blocoGeralAutocomplete.style.display = 'block';
    blocoGeralAutocomplete.style.width = posicaoInput.width + 'px';
    blocoGeralAutocomplete.style.left = posicaoInput.left + 'px';

    const blocoUl = blocoGeralAutocomplete.querySelector('ul');
    const posicaoBlocoUl = blocoUl.getBoundingClientRect();

    if (posicaoInput.top + posicaoInput.height > windowHeight - posicaoBlocoUl.height - 30) {
        blocoGeralAutocomplete.style.top = posicaoInput.top + alturaScroll - posicaoBlocoUl.height + 'px';
    } else {
        blocoGeralAutocomplete.style.top = posicaoInput.top + alturaScroll + posicaoInput.height + 'px';
    }
};

/*/
|--------------------------------------------------------------------------
| CLICK GERAL NO BODY
|--------------------------------------------------------------------------
/*/
bodyFormGeral.addEventListener('click', e => {
    if (
        autocompleteInputAtual &&
        !e.target.classList.contains('input_autocomplete') &&
        !e.target.closest('#bloco_geral_autocomplete') &&
        e.target != autocompleteInputAtual
    ) {
        fwFormAutocompleteFechar();
    }
    const blocoCalendario = document.getElementById('fw_calendario');
    if (fwFormInputDataAberto && blocoCalendario && blocoCalendario.style.display == 'none') {
        if (fwFormInputDataAberto.classList.contains('input_obrigatorio') && fwFormInputDataAberto.value == '') {
            fwFormInputDataAberto.classList.add('input_obrigatorio_ativo');
            const inputMensagem = fwFormInputDataAberto.closest('.bloco_input').querySelector('.input_mensagem');
            inputMensagem.classList.add('ativo');
            inputMensagem.innerText = 'Campo obrigatório';
        }
        fwFormInputDataAberto = false;
    }
});

/*
|--------------------------------------------------------------------------
| FUNÇÕES EXTERNAS
|--------------------------------------------------------------------------
|
| Funções para serem chamadas em outros lugares
|
*/
const fwFormSelectEmpty = input => {
    const bloco = input.closest('.input_select');
    const inputTexto = bloco.querySelector('.input_select_texto');
    input.value = '';
    inputTexto.value = '';
};

fwFormValue = (input, valor) => {
    if (!input) {
        return false;
    }
    const bloco = input.closest('.bloco_input');
    if (bloco && bloco.classList.contains('input_select')) {
        const inputTexto = bloco.querySelector('.input_select_texto');
        const li = bloco.querySelector('ul li[data-value=' + valor + ']');
        if (li && inputTexto) {
            inputTexto.value = li.innerText;
            input.value = valor;
        }
    } else if (bloco) {
        input.value = valor;
    }
};
fwFormInputMensagemOk = input => {
    input.classList.remove('input_erro');
    const blocoInputErro = input.closest('.bloco_input');
    const mensagemInputErro = blocoInputErro.querySelector('.input_mensagem');
    mensagemInputErro.classList.remove('ativo');
    mensagemInputErro.innerHTML = '';
};
fwFormInputMensagemErro = (input, mensagemTexto) => {
    input.classList.add('input_erro');
    const blocoInputErro = input.closest('.bloco_input');
    const mensagemInputErro = blocoInputErro.querySelector('.input_mensagem');
    mensagemInputErro.classList.add('ativo');
    mensagemInputErro.innerHTML = mensagemTexto;
};
fwFormBloquear = input => {
    const bloco = input.closest('.bloco_input');
    const blocoBloqueado = bloco.querySelector('.input_bloqueado');
    bloco.classList.add('bloco_bloqueado');
    blocoBloqueado.style.display = 'block';
    input.value = '';
    input.setAttribute('readonly', true);
};
fwFormDesbloquear = input => {
    const bloco = input.closest('.bloco_input');
    const blocoBloqueado = bloco.querySelector('.input_bloqueado');
    blocoBloqueado.style.display = 'none';
    bloco.classList.remove('bloco_bloqueado');
    input.removeAttribute('readonly');
};
fwFormValidarTamanho = async form => {
    const listaInput = form.querySelectorAll('.input_contador');
    let inputErroStatus = false;
    let caracterMaximo, caracterDigitado;
    [].forEach.call(listaInput, input => {
        caracterDigitado = parseInt(input.value.trim().length);
        caracterMaximo = parseInt(input.getAttribute('data-contador'));
        if (caracterDigitado > caracterMaximo) {
            inputErroStatus = true;
            input.focus();
            const bloco = input.closest('.bloco_input');
            const label = bloco.querySelector('label');
            let texto = 'Você possui um campo com mais caracter que o permitido.';
            if (label) {
                texto = 'O campo "' + label.innerText + '" está com mais caracter que o permitido.';
            }
            Alerta.notificacao(texto, false);
            return false;
        }
    });
    return {
        erro: inputErroStatus,
    };
};

/*
|--------------------------------------------------------------------------
| WINDOWS LOAD
|--------------------------------------------------------------------------
*/
window.addEventListener('load', () => {
    fwFormLoading(document.querySelector('body'));
});

fwFormLoading = bloco => {
    fwFormLoadingSelect(bloco);
    fwFormLoadingCor(bloco);

    const inputSwitchLista = bloco.querySelectorAll('.bloco_switch input[type=checkbox]');
    const inputSenhaLista = bloco.querySelectorAll('.bloco_senha');
    const inputUrlLista = bloco.querySelectorAll('.input_url');
    const textareaResizeEnterFalseLista = bloco.querySelectorAll('.input_textarea_enter_false .textarea_resize');
    const textareaResizeLista = bloco.querySelectorAll('.textarea_resize');
    const inputContadorLista = bloco.querySelectorAll('.input_contador');
    const inputObrigatorioLista = bloco.querySelectorAll(`
        .input_input input.input_obrigatorio,
        .input_textarea textarea.input_obrigatorio
    `);
    const inputDataLista = bloco.querySelectorAll('.input_data');
    const inputCardLista = bloco.querySelectorAll('.input_card .card_botao');
    const listaInputSeparador = bloco.querySelectorAll('.bloco_separador input');
    const listaAutocomplete = bloco.querySelectorAll('.input_autocomplete input');

    const blocoGeralAutocomplete = document.getElementById('bloco_geral_autocomplete');
    if (listaAutocomplete.length > 0 && blocoGeralAutocomplete) {
        let fwFormAutocompleteTempBloco, fwFormAutocompleteTempAction, fwFormAutocompleteTempId;
        [].forEach.call(listaAutocomplete, input => {
            fwFormAutocompleteTempBloco = input.closest('.input_autocomplete');
            fwFormAutocompleteTempAction = fwFormAutocompleteTempBloco.getAttribute('data-action');
            fwFormAutocompleteTempId = fwFormAutocompleteTempBloco.getAttribute('id');

            if (typeof fwFormAutocompleteTempAction === 'string' && fwFormAutocompleteTempAction != '') {
                autocompleteAction[fwFormAutocompleteTempId] = fwFormAutocompleteTempAction;
                fwFormAutocompleteTempBloco.removeAttribute('data-action');
            }
            // input.removeEventListener('focus');
            input.addEventListener('focus', e => {
                const valor = input.value;
                autocompleteInputAtual = input;
                autocompleteValorAtual = valor;
            });
            input.addEventListener('keyup', e => {
                const valor = input.value;
                const option = blocoGeralAutocomplete.querySelector('.option');
                if (valor == '') {
                    return fwFormAutocompleteFechar();
                } else if ((e.key == 'ArrowDown' || e.key == 'ArrowUp') && !option) {
                    e.preventDefault();
                    return fwFormAutocompleteBuscarSugestao(input);
                } else if (e.key == 'Enter' && option) {
                    e.preventDefault();
                    return fwFormAutocompleteSelecionar(input, option);
                } else if (e.key == 'Escape') {
                    return fwFormAutocompleteFechar();
                } else if (valor == autocompleteValorAtual && input == autocompleteInputAtual) {
                    return true;
                }
                return fwFormAutocompleteBuscarSugestao(input);
            });
            input.addEventListener('keydown', e => {
                const option = blocoGeralAutocomplete.querySelector('.option');
                if (e.key == 'Enter' && option) {
                    e.preventDefault();
                } else if (e.key == 'ArrowDown' && option) {
                    e.preventDefault();
                    return fwFormAutocompleteProximoOption(option, input);
                } else if (e.key == 'ArrowUp' && option) {
                    e.preventDefault();
                    return fwFormAutocompleteAnteriorOption(option, input);
                } else if (e.key == 'Tab') {
                    return fwFormAutocompleteFechar();
                }
            });
        });

        blocoGeralAutocomplete.addEventListener('mousemove', e => {
            let li = e.target;
            if (!li.classList.contains('lista')) {
                if (li.closest('.lista') && li.closest('#bloco_geral_autocomplete')) {
                    li = li.closest('.lista');
                } else {
                    li = undefined;
                }
            }
            if (li) {
                const liHoverAtual = blocoGeralAutocomplete.querySelector('li.hover');
                if (liHoverAtual) {
                    liHoverAtual.classList.remove('hover');
                }
                li.classList.add('hover');
            }
        });

        blocoGeralAutocomplete.addEventListener('click', e => {
            let li = e.target;
            if (!li.classList.contains('lista')) {
                if (li.closest('.lista') && li.closest('#bloco_geral_autocomplete')) {
                    li = li.closest('.lista');
                } else {
                    li = undefined;
                }
            }
            if (li) {
                autocompleteInputAtual.value = li.getAttribute('data-value');
                autocompleteInputAtual.focus();
                return fwFormAutocompleteFechar();
            }
        });
    }

    /*/
    |--------------------------------------------------------------------------
    | GERANCIA OS INPUT COM SEPARADOR
    |--------------------------------------------------------------------------
    /*/
    if (listaInputSeparador.length > 0) {
        [].forEach.call(listaInputSeparador, input => {
            input.addEventListener('focus', () => {
                const bloco = input.closest('.bloco_separador');
                bloco.classList.add('input_focus');
            });
            input.addEventListener('blur', () => {
                const bloco = input.closest('.bloco_separador');
                bloco.classList.remove('input_focus');
            });
        });
    }

    /*/
    |--------------------------------------------------------------------------
    | GERANCIA OS INPUT DE CARD
    |--------------------------------------------------------------------------
    /*/
    if (inputCardLista.length > 0) {
        [].forEach.call(inputCardLista, card => {
            card.addEventListener('click', () => {
                const valor = card.getAttribute('data-value');
                const bloco = card.closest('.input_card');
                const hover = bloco.querySelector('.card_botao.hover');
                const input = bloco.querySelector('input');
                if (!input) {
                    return false;
                }
                if (hover) {
                    hover.classList.remove('hover');
                }
                card.classList.add('hover');
                input.value = valor;
            });
        });
    }

    /*/
    |--------------------------------------------------------------------------
    | INPUT DATA
    |--------------------------------------------------------------------------
    /*/
    if (inputDataLista.length > 0) {
        inputDataLista.forEach(input => {
            input.addEventListener('focus', function () {
                this.select();
            });
        });
    }

    /*/
    |--------------------------------------------------------------------------
    | INPUT OBRIGATÓRIO
    |--------------------------------------------------------------------------
    /*/
    if (inputObrigatorioLista.length > 0) {
        inputObrigatorioLista.forEach(input => {
            input.addEventListener('focus', input => {
                if (!input.target.classList.contains('input_obrigatorio_ativo')) {
                    return false;
                }
                input.target.classList.remove('input_obrigatorio_ativo');
                const bloco = input.target.closest('.bloco_input');
                const mensagem = bloco.querySelector('.input_mensagem');
                if (mensagem) {
                    setTimeout(() => {
                        mensagem.innerText = '';
                    }, 300);
                    mensagem.classList.remove('ativo');
                }
            });
            input.addEventListener('blur', input => {
                const data = input.target.classList.contains('input_data');
                if (input.target.value == '' && !data) {
                    input.target.classList.add('input_obrigatorio_ativo');
                    const bloco = input.target.closest('.bloco_input');
                    const mensagem = bloco.querySelector('.input_mensagem');
                    if (mensagem) {
                        mensagem.innerText = 'Campo obrigatório.';
                        mensagem.classList.add('ativo');
                    }
                } else if (data) {
                    fwFormInputDataAberto = input.target;
                }
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | CONTADOR DE CARACTER
    |--------------------------------------------------------------------------
    */
    if (inputContadorLista.length > 0) {
        const inputContadorInicial = input => {
            let blocoInput = input.closest('.bloco_input_select');
            if (!blocoInput) {
                blocoInput = input.closest('.bloco_input');
            }

            const blocoContador = blocoInput.querySelector('.bloco_input_contador');
            if (!blocoContador) {
                return false;
            }

            const caracterDigitado = input.value.trim().length;
            const blocoContadorDe = blocoContador.querySelector('.bloco_input_contador_numero:first-child');
            const caracterMaximo = parseInt(input.getAttribute('data-contador'));

            const blocoMensagem = blocoInput.querySelector('.input_mensagem');

            blocoContadorDe.innerText = caracterDigitado;
            if (caracterDigitado > caracterMaximo) {
                blocoInput.classList.add('input_quantidade_erro');
                blocoMensagem.classList.add('ativo');
                blocoMensagem.innerText = 'Campo com mais caracteres que o permitido.';
            }
        };
        const inputContadorValidar = async bloco => {
            const listaInput = bloco.querySelectorAll('.input_contador');
            let contadorStatus = true;
            [].forEach.call(listaInput, input => {
                const caracterDigitado = input.value.trim().length;
                const caracterMaximo = input.getAttribute('data-contador');
                if (caracterDigitado > caracterMaximo) {
                    contadorStatus = false;
                }
            });
            return contadorStatus;
        };

        let inputContadorTextClear;
        [].forEach.call(inputContadorLista, input => {
            inputContadorInicial(input);

            input.addEventListener('focus', function () {
                let blocoInput = input.closest('.bloco_input_select');
                if (!blocoInput) {
                    blocoInput = input.closest('.bloco_input');
                }

                const blocoContador = blocoInput.querySelector('.bloco_input_contador');
                if (!blocoContador) {
                    return false;
                }

                const caracterDigitado = input.value.trim().length;
                const blocoContadorDe = blocoContador.querySelector('.bloco_input_contador_numero:first-child');
                const blocoContadorAte = blocoContador.querySelector('.bloco_input_contador_numero:last-child');
                const caracterMaximo = parseInt(input.getAttribute('data-contador'));
                blocoContadorDe.innerText = caracterDigitado;
                blocoContadorAte.innerText = caracterMaximo;

                blocoContador.classList.add('ativo');
            });
            input.addEventListener('blur', function () {
                let blocoInput = input.closest('.bloco_input_select');
                if (!blocoInput) {
                    blocoInput = input.closest('.bloco_input');
                }

                const blocoContador = blocoInput.querySelector('.bloco_input_contador');
                if (!blocoContador) {
                    return false;
                }
                blocoContador.classList.remove('ativo');
            });

            input.addEventListener('keyup', function (e) {
                let blocoInput = input.closest('.bloco_input_select');
                if (!blocoInput) {
                    blocoInput = input.closest('.bloco_input');
                }

                const blocoContador = blocoInput.querySelector('.bloco_input_contador');
                const blocoMensagem = blocoInput.querySelector('.input_mensagem');
                if (!blocoContador || !blocoMensagem) {
                    return false;
                }

                const blocoContadorDe = blocoContador.querySelector('.bloco_input_contador_numero:first-child');
                if (!blocoContador.classList.contains('ativo')) {
                    blocoContador.classList.add('ativo');
                }

                const caracterDigitado = input.value.trim().length;
                blocoContadorDe.innerHTML = caracterDigitado;
                inputContadorValidar(blocoInput).then(response => {
                    if (!response && !blocoInput.classList.contains('input_quantidade_erro')) {
                        blocoInput.classList.add('input_quantidade_erro');
                        blocoMensagem.classList.add('ativo');
                        blocoMensagem.innerText = 'Campo com mais caracteres que o permitido.';
                        clearTimeout(inputContadorTextClear);
                    } else if (response && blocoInput.classList.contains('input_quantidade_erro')) {
                        blocoInput.classList.remove('input_quantidade_erro');
                        blocoMensagem.classList.remove('ativo');
                        inputContadorTextClear = setTimeout(() => {
                            blocoMensagem.innerText = '';
                        }, 300);
                    }
                });
            });
        });
    }

    /*/
    |--------------------------------------------------------------------------
    | TEXTAREA AUTOSIZE
    |--------------------------------------------------------------------------
    /*/
    if (textareaResizeLista.length > 0) {
        const textareaHeightPadrao = 45;
        textareaResizeLista.forEach(textarea => {
            textareaFunction = textarea.oninput = function () {
                textarea.style.height = 0;
                textarea.style.height =
                    textarea.scrollHeight < textareaHeightPadrao
                        ? textareaHeightPadrao + 'px'
                        : textarea.scrollHeight + 'px';
            };
            textareaFunction();
        });
    }

    if (textareaResizeEnterFalseLista.length > 0) {
        textareaResizeEnterFalseLista.forEach(textarea => {
            textarea.addEventListener('keydown', e => {
                if (e.key == 'Enter') {
                    e.preventDefault();
                }
                if (e.shiftKey && e.key == 'Enter') {
                    let valor = textarea.value;

                    const posicaoCursorInicial = textarea.selectionStart;
                    const posicaoCursorFinal = textarea.selectionEnd;
                    const valorInicial = valor.substring(0, posicaoCursorInicial);
                    const valorFinal = valor.substring(posicaoCursorFinal);
                    const cursorFinal = posicaoCursorInicial + 1;

                    textarea.value = valorInicial + '\n' + valorFinal;
                    textarea.setSelectionRange(cursorFinal, cursorFinal);

                    textarea.style.height = 0;
                    textarea.style.height = textarea.scrollHeight < 45 ? 45 + 'px' : textarea.scrollHeight + 'px';
                }
            });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | INPUT SWITCH
    |--------------------------------------------------------------------------
    */
    if (inputSwitchLista.length > 0) {
        inputSwitchLista.forEach(input => {
            input.addEventListener('change', () => {
                if (input.checked) {
                    input.value = 'sim';
                } else {
                    input.value = '';
                }
            });
        });
    }

    /*/
    |--------------------------------------------------------------------------
    | INPUT DE SENHA
    |--------------------------------------------------------------------------
    /*/
    if (inputSenhaLista.length > 0) {
        inputSenhaLista.forEach(blocoInput => {
            blocoInput.querySelector('.botao_mostrar_senha').addEventListener('click', () => {
                const inputLista = blocoInput.querySelectorAll('input');
                const quantidade = inputLista.length;
                const type = inputLista[0].getAttribute('type');

                if (type == 'password') {
                    inputLista[0].setAttribute('type', 'text');
                    if (quantidade == 2) {
                        inputLista[1].setAttribute('type', 'text');
                    }
                    blocoInput.classList.add('mostrar_senha');
                } else {
                    inputLista[0].setAttribute('type', 'password');
                    if (quantidade == 2) {
                        inputLista[1].setAttribute('type', 'password');
                    }
                    blocoInput.classList.remove('mostrar_senha');
                }
            });
        });
    }

    /*/
    |--------------------------------------------------------------------------
    | INPUT DE URL
    |--------------------------------------------------------------------------
    /*/
    if (inputUrlLista.length > 0) {
        const removerProtocoloUrl = input => {
            const valor = input.value;
            if (/^(http:\/\/|https:\/\/)/.test(valor)) {
                input.value = valor.replace(/^(http:\/\/|https:\/\/)/i, '');
            }
        };

        inputUrlLista.forEach(inputUrl => {
            inputUrl.addEventListener('keyup', e => {
                const valor = inputUrl.value;
                if (valor.replace(/^(http:\/\/|https:\/\/)/i, '') != '') {
                    removerProtocoloUrl(inputUrl);
                }
            });
            inputUrl.addEventListener('change', e => {
                removerProtocoloUrl(inputUrl);
            });
            removerProtocoloUrl(inputUrl);
        });
    }
};
