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
    document.querySelector('.fw_bloco_galeria'),
    '.fw_form_imagem_galeria',
    '.fw_imagem_visualizar'
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

        if (typeof fwFormArquivoChange === 'function') {
            fwFormArquivoChange();
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
    if (typeof fwFormArquivoChange === 'function') {
        fwFormArquivoChange();
    }
};
fwFormArquivoLoading = bloco => {
    const fwFormImagem = bloco.querySelectorAll('.form_geral .fw_form_imagem');
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
    GaleriaFormImagem.recarregar(
        document.querySelector('.fw_bloco_galeria'),
        '.fw_form_imagem_galeria',
        '.fw_imagem_visualizar'
    );
};
fwFormArquivoLoading(document);

/*
|--------------------------------------------------------------------------
| ARQUIVO LISTA
|--------------------------------------------------------------------------
*/
const fwFormArquivoListaEscolherArquivo = async (Upload, blocoZero, lista, name) => {
    const botao = await Upload.botao();
    botao.addEventListener('click', () => {
        const hash = Upload.id();
        const link = Upload.arquivo();
        const nome = Upload.nome();
        const extensao = Upload.extensao();

        const quantidade = hash.length;
        let i;
        for (i = 0; i < quantidade; ++i) {
            fwFormArquivoListaMontarRetorno(hash[i], link[i], nome[i], extensao[i], blocoZero, lista, name);
        }

        if (typeof fwFormArquivoListaChange === 'function') {
            fwFormArquivoListaChange();
        }
        Upload.fechar();
    });
};
const fwFormArquivoListaMontarRetorno = (hash, link, nome, extensao, blocoZero, lista, name) => {
    if (lista.querySelector('.fw_arquivo_' + hash)) {
        return;
    }

    const eUmaImagem =
        extensao == 'jpg' || extensao == 'jpeg' || extensao == 'png' || extensao == 'gif' || extensao == 'svg';

    let arquivoDownloadHtml = '';
    if (!eUmaImagem) {
        arquivoDownloadHtml = `
                <a class="fw_form_arquivo_lista_icone fw_form_arquivo_lista_download" href="https://docs.google.com/viewer?url=${link}" target="_blank" rel="noopener noreferrer">
                    <svg height="12" xmlns:cc="hqttp://creativecommons.org/ns#" xmlns:dc="https://purl.org/dc/elements/1.1/" xmlns:inkscape="https://www.inkscape.org/namespaces/inkscape" xmlns:rdf="https://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="https://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="https://www.w3.org/2000/svg" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 29.3" style="enable-background:new 0 0 40 29.3;" xml:space="preserve"><g transform="translate(0,-288.53333)"><path d="M20,288.5c-13.3,0-19.3,12.3-19.6,12.9c-0.6,1.1-0.6,2.5,0,3.6c0.3,0.6,6.3,12.9,19.6,12.9s19.3-12.3,19.6-12.9 c0.6-1.1,0.6-2.5,0-3.6C39.3,300.8,33.3,288.5,20,288.5z M20,291.2c11.6,0,16.8,10.6,17.2,11.4c0.2,0.4,0.2,0.8,0,1.2 c-0.4,0.8-5.6,11.4-17.2,11.4S3.2,304.6,2.8,303.8c-0.2-0.4-0.2-0.8,0-1.2C3.2,301.7,8.4,291.2,20,291.2z"/><path d="M20,293.9c-5.1,0-9.3,4.2-9.3,9.3s4.2,9.3,9.3,9.3s9.3-4.2,9.3-9.3S25.1,293.9,20,293.9z M20,296.5c3.7,0,6.7,3,6.7,6.7 s-3,6.7-6.7,6.7s-6.7-3-6.7-6.7S16.3,296.5,20,296.5z"/></g></svg>
                </a>
            `;
    } else {
        arquivoDownloadHtml = `
                <i class="fw_form_arquivo_lista_icone fw_form_arquivo_lista_download fw_imagem_visualizar">
                    <svg height="12" xmlns:cc="hqttp://creativecommons.org/ns#" xmlns:dc="https://purl.org/dc/elements/1.1/" xmlns:inkscape="https://www.inkscape.org/namespaces/inkscape" xmlns:rdf="https://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="https://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="https://www.w3.org/2000/svg" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 29.3" style="enable-background:new 0 0 40 29.3;" xml:space="preserve"><g transform="translate(0,-288.53333)"><path d="M20,288.5c-13.3,0-19.3,12.3-19.6,12.9c-0.6,1.1-0.6,2.5,0,3.6c0.3,0.6,6.3,12.9,19.6,12.9s19.3-12.3,19.6-12.9 c0.6-1.1,0.6-2.5,0-3.6C39.3,300.8,33.3,288.5,20,288.5z M20,291.2c11.6,0,16.8,10.6,17.2,11.4c0.2,0.4,0.2,0.8,0,1.2 c-0.4,0.8-5.6,11.4-17.2,11.4S3.2,304.6,2.8,303.8c-0.2-0.4-0.2-0.8,0-1.2C3.2,301.7,8.4,291.2,20,291.2z"/><path d="M20,293.9c-5.1,0-9.3,4.2-9.3,9.3s4.2,9.3,9.3,9.3s9.3-4.2,9.3-9.3S25.1,293.9,20,293.9z M20,296.5c3.7,0,6.7,3,6.7,6.7 s-3,6.7-6.7,6.7s-6.7-3-6.7-6.7S16.3,296.5,20,296.5z"/></g></svg>
                </i>
            `;
    }

    const figureBg = eUmaImagem ? `style="background-image: url(${link})"` : '';
    const figureExtensaoHtml = !eUmaImagem ? `<p>${extensao}</p>` : '';

    if (!blocoZero.classList.contains('fw_arquivo_lista_hide')) {
        blocoZero.classList.add('fw_arquivo_lista_hide');
    }

    lista.insertAdjacentHTML(
        'afterbegin',
        `
            <div class="fw_form_arquivo_lista_arquivo fw_arquivo_${hash}">
                <input type="hidden" name="${name}[]" value="${hash}">
                <figure ${figureBg}>${figureExtensaoHtml}</figure>
                ${arquivoDownloadHtml}
                <i class="fw_form_arquivo_lista_icone fw_form_arquivo_lista_remover">
                    <svg height="19" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 48 48" x="0px" y="0px"><g data-name="Application, Delete"><path d="M13,37a4,4,0,0,0,4,4H31a4,4,0,0,0,4-4V16H13Zm2-19H33V37a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2Zm7,16H20V23h2Zm6,0H26V23h2Zm3.41-23-4-4H20.59l-4,4H9v2H39V11Zm-10-2h5.18l2,2H19.41Z"/></g></svg>
                </i>
                <p class="fw_form_arquivo_lista_arquivo_nome fw_arquivo_nome_${hash}">${nome}</p>
            </div>
        `
    );

    if (eUmaImagem) {
        const figure = lista.querySelector('.fw_arquivo_' + hash);
        if (figure.classList.contains('fw_form_imagem_galeria')) {
            GaleriaFormImagem.atualizar(figure, { imagem: link });
        } else {
            GaleriaFormImagem.add(figure, { imagem: link });
        }
    }
};

fwFormArquivoListaLoading = bloco => {
    const fwFormArquivoLista = bloco.querySelectorAll('.form_geral .fw_form_arquivo_lista');
    if (fwFormArquivoLista.length > 0) {
        fwFormArquivoLista.forEach(bloco => {
            const botaoUpload = bloco.querySelector('.fw_form_arquivo_lista_upload');
            const blocoLista = bloco.querySelector('.fw_form_arquivo_lista_lista');
            const grupo = bloco.getAttribute('data-diretorio');
            const name = bloco.getAttribute('data-name');
            const blocoZero = bloco.querySelector('.fw_form_arquivo_lista_zero');

            const Upload = new ArquivoUpload(grupo, null, true);
            fwFormArquivoListaEscolherArquivo(Upload, blocoZero, blocoLista, name);
            botaoUpload.addEventListener('click', () => {
                Upload.abrir();
            });
            blocoLista.addEventListener('click', async e => {
                if (
                    !e.target.closest('.fw_form_arquivo_lista_remover') &&
                    !e.target.classList.contains('fw_form_arquivo_lista_remover')
                ) {
                    return;
                }
                if (await Alerta.confirmar('Remover arquivo', 'Tem certeza que deseja remover essa arquivo?', '!')) {
                    const blocoArquivo = e.target.closest('.fw_form_arquivo_lista_arquivo');
                    blocoArquivo.parentNode.removeChild(blocoArquivo);
                    if (blocoLista.querySelectorAll('.fw_form_arquivo_lista_arquivo').length == 0) {
                        blocoZero.classList.remove('fw_arquivo_lista_hide');
                    }
                    if (typeof fwFormArquivoListaChange === 'function') {
                        fwFormArquivoListaChange();
                    }
                }
            });
        });
    }
    GaleriaFormImagem.recarregar(
        document.querySelector('.fw_bloco_galeria'),
        '.fw_form_imagem_galeria',
        '.fw_imagem_visualizar'
    );
};
fwFormArquivoListaLoading(document);
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
        textareaResizeLista.forEach(textarea => {
            textareaFunction = textarea.oninput = function () {
                const quantidadeLinhaAtual = parseInt(textarea.value.split('\n').length);
                const numeroLinhaMaxima = parseInt(textarea.getAttribute('data-numero-linha'));
                const blocoCss = window.getComputedStyle(textarea);
                const restoAltura =
                    parseInt(blocoCss.paddingTop) +
                    parseInt(blocoCss.paddingBottom) +
                    parseInt(blocoCss.borderTopWidth) +
                    parseInt(blocoCss.borderBottomWidth);
                const linhaParaCalculo =
                    quantidadeLinhaAtual > numeroLinhaMaxima ? numeroLinhaMaxima : quantidadeLinhaAtual;
                textarea.style.height = `calc(${restoAltura}px + ${linhaParaCalculo * 1.5}em)`;
            };
            textareaFunction();
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
                    input.value = 'nao';
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
