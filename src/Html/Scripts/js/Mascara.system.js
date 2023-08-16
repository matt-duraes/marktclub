let fwMascaraValorAtual = '';
window.addEventListener('load', function () {
    fwMascaraPadrao = undefined;
    fwMascaraKeyNumero = ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'];
    fwMascaraKeyGeral = [
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
    fwMascaraKeyCtrl = ['a', 'x', 'c', 'v', 'z', 'A', 'X', 'C', 'V', 'Z'];
    fwMascaraLoading(document);
});

fwMascaraLoading = bloco => {
    const listaInput = bloco.querySelectorAll('*[data-mascara]');
    listaInput.forEach(input => {
        input.addEventListener('keydown', fwMascaraKeyDownEvento);
        input.addEventListener('keyup', fwMascaraKeyUpEvento);
        input.addEventListener('focus', fwMascaraFocusEvento);
        input.addEventListener('blur', fwMascaraBlurEvento);
    });
};

fwMascaraInArray = function (needle, haystack) {
    let tamanho = haystack.length;
    let i;
    for (i = 0; i < tamanho; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
};
fwMascaraKeyUpEvento = function (e) {
    const input = this;
    const mascara = input.getAttribute('data-mascara');
    const tecla = e.key;
    if (
        (mascara == 'numero' &&
            (fwMascaraInArray(tecla, fwMascaraKeyNumero) || fwMascaraInArray(tecla, ['Delete', 'Backspace']))) ||
        (mascara == 'dinheiro' && fwMascaraInArray(tecla, fwMascaraKeyNumero)) ||
        (mascara == 'numero_virgula' &&
            (fwMascaraInArray(tecla, [',', 'Delete', 'Backspace']) || fwMascaraInArray(tecla, fwMascaraKeyNumero))) ||
        (mascara == 'numero_ponto' &&
            (fwMascaraInArray(tecla, ['.', 'Delete', 'Backspace']) || fwMascaraInArray(tecla, fwMascaraKeyNumero)))
    ) {
        return true;
    } else if (mascara == 'dinheiro' && fwMascaraInArray(tecla, ['Delete', 'Backspace'])) {
        return fwMascaraMontarDinheiro(input, 'focus');
    } else if (fwMascaraInArray(tecla, ['Delete', 'Backspace'])) {
        return fwMascaraMontarNormal(input, 'focus');
    }
};
fwMascaraKeyDownEvento = function (e) {
    let input = this;
    let mascara = input.getAttribute('data-mascara');
    let valor = input.value;
    let valorLimpo = valor.replace(/[^0-9]/g, '');
    let mascaraTamanho = mascara.length;
    let valorTamanho = valor.length;

    if (fwMascaraInArray(mascara, ['numero_virgula', 'numero_ponto'])) {
        mascaraTamanho = 99999;
    } else if (mascara == 'numero') {
        mascaraTamanho = input.getAttribute('data-mascara-tamanho') || '9999';
        mascaraTamanho = mascaraTamanho.replace('{', '').replace('}', '').replace(/ /g, '');
        if (mascaraTamanho.indexOf(',') !== -1) {
            mascaraTamanho = mascaraTamanho.split(',')[1];
        }
        mascaraTamanho = parseInt(mascaraTamanho);
    } else if (mascara == 'dinheiro') {
        mascaraTamanho = 9999;
    } else if (mascara == 'fixo') {
        mascaraTamanho = 14;
    } else if (mascara == 'celular') {
        mascaraTamanho = 15;
    } else if (mascara == 'telefone' && valorLimpo[2] == 9) {
        mascaraTamanho = 15;
    } else if (mascara == 'telefone') {
        mascaraTamanho = 14;
    }

    let tecla = e.key;
    let objetoSelecionado = document.getSelection();
    let textoSelecionado = objetoSelecionado.toString() || '';
    let posicaoCursorInicial = input.selectionStart;
    let posicaoCursorFinal = input.selectionEnd;
    let valorSelecionado = textoSelecionado || posicaoCursorInicial != posicaoCursorFinal;
    let valorAnteriorCursor = valor[posicaoCursorInicial - 1] || 0;
    let valorProximoCursor = valor[posicaoCursorInicial] || 0;

    if ((e.ctrlKey || e.metaKey) && fwMascaraInArray(tecla, ['v', 'V'])) {
        setTimeout(() => {
            fwMascaraMontar(input).then(resposta => {
                if (!resposta) {
                    input.value = '';
                }
            });
        }, 20);
    } else if (
        fwMascaraInArray(tecla, ['Backspace', 'Delete']) &&
        fwMascaraInArray(mascara, ['numero_virgula', 'numero_ponto'])
    ) {
        return true;
    } else if (tecla == 'Backspace' && !valorSelecionado && !/^[0-9]$/.test(valorAnteriorCursor) && valorLimpo != '') {
        input.setSelectionRange(posicaoCursorInicial - 1, posicaoCursorInicial - 1);
        e.preventDefault();
    } else if (tecla == 'Delete' && !valorSelecionado && !/^[0-9]$/.test(valorProximoCursor)) {
        e.preventDefault();
    } else if (mascara == 'dinheiro' && fwMascaraInArray(tecla, fwMascaraKeyNumero)) {
        fwMascaraAcaoDinheiro(input, tecla);
        e.preventDefault();
    } else if (fwMascaraInArray(tecla, fwMascaraKeyGeral) || e.ctrlKey || e.metaKey) {
        return true;
    } else if (mascaraTamanho <= valorTamanho && !valorSelecionado) {
        e.preventDefault();
    } else if (
        (mascara == 'numero_ponto' && tecla == '.' && valor.indexOf('.') !== -1) ||
        (mascara == 'numero_virgula' && tecla == ',' && valor.indexOf(',') !== -1)
    ) {
        e.preventDefault();
    } else if (
        (mascara == 'numero_virgula' &&
            ((tecla == ',' && valor != '') || fwMascaraInArray(tecla, fwMascaraKeyNumero))) ||
        (mascara == 'numero_ponto' && ((tecla == '.' && valor != '') || fwMascaraInArray(tecla, fwMascaraKeyNumero))) ||
        (mascara == 'numero' && fwMascaraInArray(tecla, fwMascaraKeyNumero))
    ) {
        return true;
    } else if (fwMascaraInArray(tecla, fwMascaraKeyNumero)) {
        fwMascaraAcaoNormal(input, tecla);
        e.preventDefault();
    } else {
        e.preventDefault();
    }
};
fwMascaraAcaoNormal = function (input, tecla) {
    let posicaoInicio = input.selectionStart;
    let posicaoFinal = input.selectionEnd;

    let novoNumero = tecla;

    let valorTemporario = input.value;
    let valorTemporarioTamanho = valorTemporario.length;
    let valor = '';

    if (posicaoInicio != posicaoFinal && posicaoInicio > 0) {
        valor =
            valorTemporario.substr(0, posicaoInicio) +
            novoNumero +
            valorTemporario.substr(posicaoFinal, valorTemporarioTamanho);
    } else if (posicaoInicio != posicaoFinal && posicaoInicio == 0) {
        valor = novoNumero + valorTemporario.substr(posicaoFinal, valorTemporarioTamanho);
    } else if (posicaoInicio == valorTemporarioTamanho) {
        posicaoInicio = 99999999;
        valor = valorTemporario + novoNumero;
    } else {
        valor =
            valorTemporario.substr(0, posicaoInicio) +
            novoNumero +
            valorTemporario.substr(posicaoInicio, valorTemporarioTamanho);
    }

    let valorLimpo = valor.replace(/[^0-9]/g, '');

    let valorLimpoTamanho = valorLimpo.length;
    if (valorLimpoTamanho == 0) {
        return false;
    }

    let mascara = input.getAttribute('data-mascara');
    if (mascara == 'fixo') {
        mascara = '(00) 0000-0000';
    } else if (mascara == 'celular') {
        mascara = '(00) 00000-0000';
    } else if (mascara == 'telefone' && valorLimpo[2] == 9) {
        mascara = '(00) 00000-0000';
    } else if (mascara == 'telefone') {
        mascara = '(00) 0000-0000';
    }

    let mascaraTamanho = mascara.length;
    let i;
    let valorNovo = '';
    let contar = 0;
    for (i = 0; i < mascaraTamanho; ++i) {
        if (mascara[i] != '0') {
            valorNovo += mascara[i];
        } else if (valorLimpo[contar] != undefined) {
            valorNovo += valorLimpo[contar];
            contar++;
        } else {
            break;
        }
    }

    if (valorNovo.length <= mascaraTamanho) {
        input.value = valorNovo;
        if (valorNovo[posicaoInicio] == novoNumero) {
            input.setSelectionRange(posicaoInicio + 1, posicaoInicio + 1);
        } else if (valorNovo[posicaoInicio + 1] == novoNumero) {
            input.setSelectionRange(posicaoInicio + 2, posicaoInicio + 2);
        }

        return true;
    }

    return false;
};
fwMascaraAcaoDinheiro = function (input, tecla) {
    let posicaoInicio = input.selectionStart;
    let posicaoFinal = input.selectionEnd;

    let novoNumero = tecla;

    let valorTemporario = input.value;
    let valorTemporarioTamanho = valorTemporario.length;
    let valor = '';

    if (posicaoInicio != posicaoFinal && posicaoInicio > 0) {
        valor =
            valorTemporario.substr(0, posicaoInicio) +
            novoNumero +
            valorTemporario.substr(posicaoFinal, valorTemporarioTamanho);
    } else if (posicaoInicio != posicaoFinal && posicaoInicio == 0) {
        valor = novoNumero + valorTemporario.substr(posicaoFinal, valorTemporarioTamanho);
    } else if (posicaoInicio == valorTemporarioTamanho) {
        posicaoInicio = 99999999;
        valor = valorTemporario + novoNumero;
    } else {
        valor =
            valorTemporario.substr(0, posicaoInicio) +
            novoNumero +
            valorTemporario.substr(posicaoInicio, valorTemporarioTamanho);
    }

    let valorLimpo = valor.replace(/[^0-9]/g, '');
    let valorLimpoTamanho = valorLimpo.length;

    if (valorLimpoTamanho < 3) {
        input.value = valorLimpo;
        return true;
    }

    let valorDecimal =
        valorLimpo.substr(0, valorLimpoTamanho - 2) + '.' + valorLimpo.substr(valorLimpoTamanho - 2, valorLimpoTamanho);
    let valorReal = Number(valorDecimal).toLocaleString('pt-BR');
    let explode = valorReal.split(',');

    let valorNovo = '';
    if (explode[1] == undefined) {
        valorNovo = explode[0] + ',00';
    } else if (explode[1].length == 1) {
        valorNovo = explode.join(',') + '0';
    } else {
        valorNovo = explode.join(',');
    }

    let valorInicialPonto = valor.replace(/[0-9]/g, '').length;
    let valorNovoPonto = valorNovo.replace(/[0-9]/g, '').length;
    let posicaoCursor = posicaoInicio + (valorNovoPonto - valorInicialPonto) + 1;

    input.value = valorNovo;
    input.setSelectionRange(posicaoCursor, posicaoCursor);

    return true;
};

fwMascaraFocusEvento = function () {
    fwMascaraValorAtual = this.value;
    fwMascaraMascaraEvento(this, 'focus');
};
fwMascaraBlurEvento = function () {
    if (this.value != fwMascaraValorAtual) {
        this.dispatchEvent(new Event('formChange'));
    }
    fwMascaraMascaraEvento(this, 'blur');
};
fwMascaraMascaraEvento = function (input, tipo) {
    input.classList.remove('mascara_erro');

    let montar = fwMascaraMontar(input, tipo);
    montar.then(resposta => {
        if (!resposta) {
            fwMascaraErro(input, tipo);
            return false;
        }
    });
};

async function fwMascaraMontar(input, tipo) {
    let mascara = input.getAttribute('data-mascara');

    if (mascara == 'dinheiro') {
        return fwMascaraMontarDinheiro(input, tipo);
    } else if (mascara == 'numero') {
        return fwMascaraValidarNumero(input, tipo);
    } else if (mascara == 'numero_virgula') {
        return fwMascaraValidarNumeroVirgula(input);
    } else if (mascara == 'numero_ponto') {
        return fwMascaraValidarNumeroPonto(input);
    } else {
        return fwMascaraMontarNormal(input, tipo);
    }
}
fwMascaraValidarNumeroVirgula = function (input) {
    const valor = input.value;
    const testarInteiro = /^[0-9]$/.test(valor);
    const testarDecimal = /^([0-9]{1,})\,([0-9]{1,})$/.test(valor);
    if (testarInteiro || testarDecimal) {
        return true;
    }
    return false;
};
fwMascaraValidarNumeroPonto = function (input) {
    const valor = input.value;
    const testarInteiro = /^[0-9]$/.test(valor);
    const testarDecimal = /^([0-9]{1,})\.([0-9]{1,})$/.test(valor);
    if (testarInteiro || testarDecimal) {
        return true;
    }
    return false;
};
fwMascaraValidarNumero = function (input, tipo) {
    mascaraTamanho = input.getAttribute('data-mascara-tamanho') || false;
    if (false === mascaraTamanho || tipo == 'focus') {
        return true;
    }
    mascaraTamanho = mascaraTamanho.replace('{', '').replace('}', '').replace(/ /g, '');
    if (mascaraTamanho.indexOf(',') !== -1) {
        mascaraTamanho = mascaraTamanho.split(',');
    }

    let valor = input.value;
    let valorTamanho = valor.length;

    if (mascaraTamanho.length == 1 && mascaraTamanho[0] != valorTamanho) {
        return false;
    } else if (mascaraTamanho.length == 2 && (valorTamanho < mascaraTamanho[0] || valorTamanho > mascaraTamanho[1])) {
        return false;
    }

    return true;
};
fwMascaraMontarNormal = function (input, tipo) {
    let posicaoCursor = input.selectionStart;

    let valor = input.value;
    let valorLimpo = valor.replace(/[^0-9]/g, '');
    let valorLimpoTamanho = valorLimpo.length;

    let mascara = input.getAttribute('data-mascara');
    if (mascara == 'fixo') {
        mascara = '(00) 0000-0000';
    } else if (mascara == 'celular') {
        mascara = '(00) 00000-0000';
    } else if (mascara == 'telefone' && valorLimpo[2] == 9) {
        mascara = '(00) 00000-0000';
    } else if (mascara == 'telefone') {
        mascara = '(00) 0000-0000';
    }
    let mascaraTamanho = mascara.length;

    if (valorLimpoTamanho == 0) {
        return false;
    }

    let i;
    let valorNovo = '';
    let contar = 0;

    for (i = 0; i < mascaraTamanho; ++i) {
        if (/^0$/.test(mascara[i]) && /[0-9]/.test(valorLimpo[contar])) {
            valorNovo += valorLimpo[contar];
            if (contar < valorLimpoTamanho - 1) {
                contar++;
            } else {
                break;
            }
        } else {
            valorNovo += mascara[i];
        }
    }

    input.value = valorNovo;
    if (tipo == 'focus') {
        input.setSelectionRange(posicaoCursor, posicaoCursor);
    }

    if (mascaraTamanho == valorNovo.length) {
        return true;
    }

    return false;
};
fwMascaraMontarDinheiro = function (input, tipo) {
    let posicaoCursor = input.selectionStart;
    let valor = input.value;
    let valorLimpo = valor.replace(/[^0-9]/g, '');
    let valorLimpoTamanho = valorLimpo.length;

    if (valorLimpoTamanho < 3) {
        input.value = valorLimpo;
        if (tipo == 'focus') {
            input.setSelectionRange(posicaoCursor, posicaoCursor);
        }
        return true;
    }

    let valorDecimal =
        valorLimpo.substr(0, valorLimpoTamanho - 2) + '.' + valorLimpo.substr(valorLimpoTamanho - 2, valorLimpoTamanho);
    let valorReal = Number(valorDecimal).toLocaleString('pt-BR');
    let explode = valorReal.split(',');

    let valorNovo;
    if (explode[1] == undefined) {
        valorNovo = explode[0] + ',00';
    } else if (explode[1].length == 1) {
        valorNovo = explode.join(',') + '0';
    } else {
        valorNovo = explode.join(',');
    }

    let valorInicialPonto = valor.replace(/[0-9]/g, '').length;
    let valorNovoPonto = valorNovo.replace(/[0-9]/g, '').length;

    input.value = valorNovo;
    if (tipo == 'focus' && valorInicialPonto > valorNovoPonto) {
        input.setSelectionRange(posicaoCursor - 1, posicaoCursor - 1);
    } else if (tipo == 'focus') {
        input.setSelectionRange(posicaoCursor, posicaoCursor);
    }

    if (valorNovo.length > 3) {
        return true;
    }

    return false;
};

fwMascaraErro = function (input, tipo) {
    if (input && tipo == 'blur' && input.value != '') {
        input.classList.add('mascara_erro');
    }
};
