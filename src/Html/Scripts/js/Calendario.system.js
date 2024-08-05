let fwCalendarioCarregarFuncao = function () {
    let fwCalendario = document.getElementById('fw_calendario');

    /*/
    |--------------------------------------------------------------------------
    | ANIMAR MES ANTERIOR
    |--------------------------------------------------------------------------
    /*/
    document.querySelector('#fw_calendario .fw_calendario_lista').addEventListener('swiped-right', function () {
        fwCalendarioAnimarPrev();
    });
    document.querySelector('#fw_calendario .fw_calendario_prev').addEventListener('click', function () {
        fwCalendarioAnimarPrev();
    });

    let fwCalendarioAnimarPrev = function () {
        if (!fwCalendario.classList.contains('fw_calendario_animando')) {
            fwCalendario.classList.add('fw_calendario_animando');

            let lista = document.querySelector('#fw_calendario .fw_calendario_lista');
            lista.classList.add('fw_calendario_animar_prev');

            setTimeout(() => {
                fwCalendarioFimTransicao('prev');
            }, 300);
        }
    };

    /*/
    |--------------------------------------------------------------------------
    | ANIMAR MES POSTERIOR
    |--------------------------------------------------------------------------
    /*/
    document.querySelector('#fw_calendario .fw_calendario_lista').addEventListener('swiped-left', function () {
        fwCalendarioAnimarNext();
    });
    document.querySelector('#fw_calendario .fw_calendario_next').addEventListener('click', function () {
        fwCalendarioAnimarNext();
    });

    let fwCalendarioAnimarNext = function () {
        if (!fwCalendario.classList.contains('fw_calendario_animando')) {
            fwCalendario.classList.add('fw_calendario_animando');

            let lista = document.querySelector('#fw_calendario .fw_calendario_lista');
            lista.classList.add('fw_calendario_animar_next');

            setTimeout(() => {
                fwCalendarioFimTransicao('next');
            }, 300);
        }
    };

    /*/
    |--------------------------------------------------------------------------
    | FUNCAO PARA FINAL DE TRANSICAO
    |--------------------------------------------------------------------------
    /*/
    let fwCalendarioFimTransicao = function (acao) {
        fwCalendario.classList.remove('fw_calendario_animando');
        fwCalendarioTrocarData(acao);
    };

    let fwCalendarioTrocarData = function (acao) {
        let mes = fwCalendario.querySelector('.fw_calendario_mes_select').value;
        let ano = fwCalendario.querySelector('.fw_calendario_ano_select').value;

        if (acao == 'prev') {
            mes--;

            if (mes == 0) {
                mes = 12;
                ano--;
            } else if (mes < 10) {
                mes = '0' + mes;
            }
        } else if (acao == 'next') {
            mes++;

            if (mes == 13) {
                mes = 1;
                ano++;
            } else if (mes < 10) {
                mes = '0' + mes;
            }
        }

        if (fwCalendarioEventoRemoverData()) {
            let data = ano + '-' + mes + '-01';
            let valor = '';
            let valorDe = '';
            let valorAte = '';

            let inputValorId = fwCalendario.getAttribute('data-fwcalendarioid');
            let inputValor = document.getElementById(inputValorId);
            if (inputValor) {
                let valorData = inputValor.value.split(' ')[0];
                if (Calendario.validarData(valorData, true)) {
                    valor = Calendario.converterData(valorData);
                }
            }

            let inputDeId = inputValor.getAttribute('data-fwcalendariode');
            let inputDe = document.getElementById(inputDeId);
            if (inputDe) {
                let valorDataDe = inputDe.value.split(' ')[0];
                if (Calendario.validarData(valorDataDe, true)) {
                    valorDe = Calendario.converterData(valorDataDe);
                }
            }

            let inputAteId = inputValor.getAttribute('data-fwcalendarioate');
            let inputAte = document.getElementById(inputAteId);
            if (inputAte) {
                let valorDataAte = inputAte.value.split(' ')[0];
                if (Calendario.validarData(valorDataAte, true)) {
                    valorAte = Calendario.converterData(valorDataAte);
                }
            }

            return Calendario._colocarListaDataHtml('anterior', data, valor, valorDe, valorAte).then(() => {
                return Calendario._colocarListaDataHtml('atual', data, valor, valorDe, valorAte).then(() => {
                    return Calendario._colocarListaDataHtml('proximo', data, valor, valorDe, valorAte).then(() => {
                        fwCalendarioEventoSelecionarData();
                        return Calendario._setarHora().then(() => {
                            let lista = document.querySelector('#fw_calendario .fw_calendario_lista');
                            if (lista && lista.classList.contains('fw_calendario_animar_prev')) {
                                lista.classList.remove('fw_calendario_animar_prev');
                            } else if (lista && lista.classList.contains('fw_calendario_animar_next')) {
                                lista.classList.remove('fw_calendario_animar_next');
                            }
                        });
                    });
                });
            });
        }
    };

    /*/
    |--------------------------------------------------------------------------
    | CHANGE MES E ANO
    |--------------------------------------------------------------------------
    /*/
    document
        .querySelector('#fw_calendario .fw_calendario_mes_select')
        .addEventListener('change', fwCalendarioTrocarData);
    document
        .querySelector('#fw_calendario .fw_calendario_ano_select')
        .addEventListener('change', fwCalendarioTrocarData);
};

fwCalendarioInputAtual = '';
/*/
|--------------------------------------------------------------------------
| ABRE UM FORMULÁRIO
|--------------------------------------------------------------------------
/*/
fwCalendarioInputListar = function (lista, option) {
    if (!lista) {
        return false;
    }

    let quantidade = lista.length;
    if (quantidade == 0) {
        return false;
    }

    let i, id;
    for (i = 0; i < quantidade; ++i) {
        if (typeof option.de == 'string' && typeof option.ate == 'string') {
            lista[i].setAttribute('data-fwcalendariodeate', 1);
            lista[i].setAttribute('data-fwcalendariode', option.de);
            lista[i].setAttribute('data-fwcalendarioate', option.ate);
        }
        if (typeof option.dataMinima == 'string' && Calendario.validarData(option.dataMinima)) {
            lista[i].setAttribute('data-fwcalendariominima', option.dataMinima);
        }
        if (typeof option.dataMaxima == 'string' && Calendario.validarData(option.dataMaxima)) {
            lista[i].setAttribute('data-fwcalendariomaxima', option.dataMaxima);
        }
        if (typeof option.hora == 'boolean') {
            lista[i].setAttribute('data-fwcalendariohora', option.hora ? 1 : 0);
        }

        id = lista[i].getAttribute('id');
        if (id == null) {
            id = 'fwcalendario_' + Math.floor(Math.random() * 99999999999);
            lista[i].setAttribute('id', id);
        }

        lista[i].addEventListener('focus', fwCalendarioAbrir);
        lista[i].addEventListener('keydown', fwCalendarioKeyDown);
        lista[i].addEventListener('keyup', fwCalendarioKeyUp);
    }
};
fwCalendarioAbrir = function () {
    if (window.innerWidth <= 1000) {
        this.blur();
        document.querySelector('body').style['overflow-y'] = 'hidden';
    }
    Calendario._abrir(this);
};
fwCalendarioKeyDown = function (e) {
    if (e.keyCode == 9) {
        Calendario._fechar();
        return false;
    } else if (e.keyCode == 27) {
        this.blur();
        Calendario._fechar();
        return false;
    }
};
fwCalendarioKeyUp = function (e) {
    let valor = this.value;
    if (valor.length >= 10) {
        Calendario._abrir(this);
    }
};
fwCalendarioFecharBody = function (e) {
    let calendario = e.target.closest('#fw_calendario');

    if (!calendario && fwCalendarioInputAtual != e.target) {
        Calendario._fechar();
    }
};
fwCalendarioFechar = function () {
    Calendario._fechar();
};
fwCalendarioFecharHoje = function () {
    let date = new Date();
    let dia = date.getDate();
    if (dia < 10) {
        dia = '0' + dia;
    }
    let mes = date.getMonth() + 1;
    if (mes < 10) {
        mes = '0' + mes;
    }
    let ano = date.getFullYear();

    let hoje = dia + '/' + mes + '/' + ano;

    let fwCalendario = document.getElementById('fw_calendario');
    let inputId = fwCalendario.getAttribute('data-fwcalendarioid');

    let input = document.getElementById(inputId);
    if (input == null) {
        return false;
    }

    Calendario._fechar();

    input.value = hoje;

    let deAte = input.getAttribute('data-fwcalendariodeate') == 1 ? true : false;
    if (deAte) {
        fwCalendarioValidarDeAte(input);
    } else {
        fwCalendarioProximoInput(input);
    }
};
fwCalendarioFecharAgora = function () {
    let date = new Date();
    let dia = date.getDate();
    if (dia < 10) {
        dia = '0' + dia;
    }
    let mes = date.getMonth() + 1;
    if (mes < 10) {
        mes = '0' + mes;
    }
    let ano = date.getFullYear();

    let hora = date.getHours();
    if (hora < 10) {
        hora = '0' + hora;
    }
    let minuto = date.getMinutes();
    if (minuto < 10) {
        minuto = '0' + minuto;
    }
    let segundo = date.getSeconds();
    if (segundo < 10) {
        segundo = '0' + segundo;
    }

    let agora = dia + '/' + mes + '/' + ano + ' ' + hora + ':' + minuto + ':' + segundo;

    let fwCalendario = document.getElementById('fw_calendario');
    let inputId = fwCalendario.getAttribute('data-fwcalendarioid');

    let input = document.getElementById(inputId);
    if (input == null) {
        return false;
    }

    Calendario._fechar();

    input.value = agora;

    let deAte = input.getAttribute('data-fwcalendariodeate') == 1 ? true : false;
    if (deAte) {
        fwCalendarioValidarDeAte(input);
    } else {
        fwCalendarioProximoInput(input);
    }
};
fwCalendarioFecharOk = function () {
    let fwCalendario = document.getElementById('fw_calendario');
    let inputId = fwCalendario.getAttribute('data-fwcalendarioid');

    let input = document.getElementById(inputId);
    if (input == null) {
        return false;
    }

    let deAte = input.getAttribute('data-fwcalendariodeate') == 1 ? true : false;
    let marcado;

    if (deAte) {
        let de = input.getAttribute('data-fwcalendariode');
        let ate = input.getAttribute('data-fwcalendarioate');
        let id = input.getAttribute('id');

        if (id == de) {
            marcado = document.querySelector('.fw_calendario_numero_bg_de > .fw_calendario_numero_marcado');
        } else if (id == ate) {
            marcado = document.querySelector('.fw_calendario_numero_bg_ate > .fw_calendario_numero_marcado');
        }
    } else {
        marcado = document.querySelector('.fw_calendario_numero_marcado');
    }

    if (!marcado) {
        return false;
    }

    let valor = marcado.getAttribute('data-fwcalendario');
    if (!Calendario.validarData(valor)) {
        return false;
    }
    valor = Calendario.converterData(valor, true);

    let hora = input.getAttribute('data-fwcalendariohora') == 1 ? true : false;
    let valorHora;
    if (hora) {
        let blocoHora = document.querySelector('.fw_calendario_hora_minuto_marcado');
        if (!blocoHora) {
            return false;
        }
        if (blocoHora.classList.contains('fw_calendario_hora_minuto_manual_input')) {
            valorHora = blocoHora.value;
        } else {
            valorHora = blocoHora.getAttribute('data-fwcalendariohora');
        }

        if (!Calendario.validarHora(valorHora)) {
            return false;
        }

        valor += ' ' + valorHora + ':00';
        input.value = valor;
    } else {
        input.value = valor;
    }

    if (deAte) {
        fwCalendarioValidarDeAte(input);
    } else {
        Calendario._fechar();
        fwCalendarioProximoInput(input);
    }
};
fwCalendarioValidarDeAte = function (input) {
    if (input.getAttribute('data-fwcalendariodeate') != 1) {
        return false;
    }

    let de = input.getAttribute('data-fwcalendariode');
    let ate = input.getAttribute('data-fwcalendarioate');
    let id = input.getAttribute('id');

    let inputDe = document.getElementById(de);
    let inputAte = document.getElementById(ate);

    if (!inputDe || !inputAte) {
        return false;
    }

    let valorDe = inputDe.value;
    let valorAte = inputAte.value;

    let dataDe, dataAte;

    let hora = input.getAttribute('data-fwcalendariohora') == 1 ? true : false;
    if (hora) {
        dataDe = Calendario.converterData(valorDe.split(' ')[0]) + ' ' + valorDe.split(' ')[1];
        dataAte = Calendario.converterData(valorAte.split(' ')[0]) + ' ' + valorAte.split(' ')[1];
    } else {
        dataDe = Calendario.converterData(valorDe);
        dataAte = Calendario.converterData(valorAte);
    }

    if (id == de) {
        inputAte.focus();
        if (dataDe > dataAte) {
            inputAte.value = '';
        }
    } else if (id == ate) {
        if (dataDe > dataAte) {
            inputDe.value = '';
            inputDe.focus();
        } else {
            Calendario._fechar();
            fwCalendarioProximoInput(input);
        }
    }
};
fwCalendarioDataEntreDeAte = function (input) {
    if (input.getAttribute('data-fwcalendariodeate') != 1) {
        return false;
    }

    let lista = document.querySelectorAll('.fw_calendario_numero_disponivel');
    let listaQuantidade = lista.length;
    if (listaQuantidade == 0) {
        return false;
    }

    let id = input.getAttribute('id');
    let de = input.getAttribute('data-fwcalendariode');
    let ate = input.getAttribute('data-fwcalendarioate');

    let blocoDe = document.getElementById(de);
    let blocoAte = document.getElementById(ate);
    if (!blocoDe || !blocoAte) {
        return false;
    }

    let valorDe = blocoDe.value.split(' ')[0];
    if (Calendario.validarData(valorDe, true)) {
        valorDe = Calendario.converterData(valorDe);
    } else {
        valorDe = false;
    }

    let valorAte = blocoAte.value.split(' ')[0];
    if (Calendario.validarData(valorAte, true)) {
        valorAte = Calendario.converterData(valorAte);
    } else {
        valorAte = false;
    }

    let marcadoDe = document.querySelector('.fw_calendario_numero_bg_de > .fw_calendario_numero_marcado');
    if (!marcadoDe && valorDe) {
        marcadoDe = document.querySelector('.fw_calendario_numero[data-fwcalendario="' + valorDe + '"]');
    }
    let marcadoAte = document.querySelector('.fw_calendario_numero_bg_ate > .fw_calendario_numero_marcado');
    if (!marcadoAte) {
        marcadoAte = document.querySelector('.fw_calendario_numero[data-fwcalendario="' + valorAte + '"]');
    }

    let dataDe = marcadoDe.getAttribute('data-fwcalendario').split(' ')[0];
    let dataAte = marcadoAte.getAttribute('data-fwcalendario').split(' ')[0];

    if (dataDe > dataAte && id == de) {
        marcadoAte.classList.remove('fw_calendario_numero_marcado');
        marcadoAte.closest('.fw_calendario_td').classList.remove('fw_calendario_numero_bg_ate');
        marcadoAte.closest('.fw_calendario_td').classList.remove('fw_calendario_numero_bg_ativo');
    } else if (
        !marcadoAte.classList.contains('fw_calendario_numero_marcado') ||
        !marcadoAte.classList.contains('fw_calendario_numero_bg_ate')
    ) {
        marcadoAte.classList.add('fw_calendario_numero_marcado');
        marcadoAte.closest('.fw_calendario_td').classList.add('fw_calendario_numero_bg_ate');
        marcadoAte.closest('.fw_calendario_td').classList.add('fw_calendario_numero_bg_ativo');
    }
    if (dataDe > dataAte && id == ate) {
        marcadoDe.classList.remove('fw_calendario_numero_marcado');
        marcadoDe.closest('.fw_calendario_td').classList.remove('fw_calendario_numero_bg_de');
        marcadoDe.closest('.fw_calendario_td').classList.remove('fw_calendario_numero_bg_ativo');
    } else if (
        !marcadoDe.classList.contains('fw_calendario_numero_marcado') ||
        !marcadoDe.classList.contains('fw_calendario_numero_bg_de')
    ) {
        marcadoDe.classList.add('fw_calendario_numero_marcado');
        marcadoDe.closest('.fw_calendario_td').classList.add('fw_calendario_numero_bg_de');
        marcadoDe.closest('.fw_calendario_td').classList.add('fw_calendario_numero_bg_ativo');
    }

    let listaAtivo = document.querySelectorAll('.fw_calendario_numero_bg_ativo');
    let listaAtivoQuantidade = listaAtivo.length;

    let td;
    if (listaAtivoQuantidade > 0) {
        let i1;
        for (i1 = 0; i1 < listaAtivoQuantidade; ++i1) {
            listaAtivo[i1].classList.remove('fw_calendario_numero_bg_ativo');
        }
    }

    let i2, dataTeste;
    for (i2 = 0; i2 < listaQuantidade; ++i2) {
        dataTeste = lista[i2].getAttribute('data-fwcalendario');
        if (dataTeste > dataDe && dataTeste < dataAte) {
            td = lista[i2].closest('.fw_calendario_td');
            if (td) {
                td.classList.add('fw_calendario_numero_bg_ativo');
            }
        }
    }

    if (
        marcadoDe.classList.contains('fw_calendario_numero_marcado') &&
        marcadoAte.classList.contains('fw_calendario_numero_marcado') &&
        !marcadoDe.closest('.fw_calendario_td').classList.contains('fw_calendario_numero_bg_ativo') &&
        marcadoAte.classList.contains('fw_calendario_numero_marcado')
    ) {
        marcadoDe.closest('.fw_calendario_td').classList.add('fw_calendario_numero_bg_ativo');
    }
    if (
        marcadoDe.classList.contains('fw_calendario_numero_marcado') &&
        marcadoAte.classList.contains('fw_calendario_numero_marcado') &&
        !marcadoAte.closest('.fw_calendario_td').classList.contains('fw_calendario_numero_bg_ativo') &&
        marcadoDe.classList.contains('fw_calendario_numero_marcado')
    ) {
        marcadoAte.closest('.fw_calendario_td').classList.add('fw_calendario_numero_bg_ativo');
    }
};
fwCalendarioProximoInput = function (input) {
    let form = input.closest('form');
    if (!form) {
        return false;
    }
    let lista = form.querySelectorAll('input, textarea, select, button');
    let quantidade = lista.length;

    if (quantidade == 0) {
        return false;
    }

    let i, proximo;
    for (i = 0; i < quantidade; ++i) {
        if (lista[i] == input) {
            proximo = lista[i + 1] || false;
            break;
        }
    }

    if (proximo) {
        proximo.focus();
    }
};

/*/
|--------------------------------------------------------------------------
| SETA A POSSICAO COM O SCROLL
|--------------------------------------------------------------------------
/*/
fwCalendarioPosicao = function () {
    Calendario._setarPosicao();
};

/*/
|--------------------------------------------------------------------------
| MARCA UMA HORA AO CLICAR
|--------------------------------------------------------------------------
/*/
fwCalendarioEventoSelecionarHora = function () {
    let botao = document.getElementById('fw_calendario').querySelectorAll('.fw_calendario_hora_minuto');
    if (botao.length == 0) {
        return true;
    }

    let quantidade = botao.length;
    let i;
    for (i = 0; i < quantidade; ++i) {
        botao[i].addEventListener('click', fwCalendarioSelecionarHora);
    }
};
fwCalendarioSelecionarHora = function () {
    if (typeof this === null) {
        return false;
    }

    if (this.classList.contains('fw_calendario_hora_minuto_manual_input')) {
        return false;
    }

    let manualInput = document.getElementById('fw_calendario').querySelector('.fw_calendario_hora_minuto_manual_input');
    if (manualInput.classList.contains('fw_calendario_hora_minuto_marcado')) {
        manualInput.classList.remove('fw_calendario_hora_minuto_marcado');
        manualInput.value = '';
        document.getElementById('fw_calendario').querySelector('.fw_calendario_hora_minuto_manual').style.display =
            'block';
    }

    let marcado = document.getElementById('fw_calendario').querySelectorAll('.fw_calendario_hora_minuto_marcado');
    let marcadoQuantidade = marcado.length;

    if (marcadoQuantidade > 0) {
        let i;
        for (i = 0; i < marcadoQuantidade; ++i) {
            marcado[i].classList.remove('fw_calendario_hora_minuto_marcado');
        }
    }

    if (this.classList.contains('fw_calendario_hora_minuto_manual')) {
        this.style.display = 'none';
        manualInput.classList.add('fw_calendario_hora_minuto_marcado');
        manualInput.focus();
    } else {
        this.classList.add('fw_calendario_hora_minuto_marcado');
    }
};

/*/
|--------------------------------------------------------------------------
| MARCA UMA DATA AO CLICAR
|--------------------------------------------------------------------------
/*/
fwCalendarioEventoRemoverData = function () {
    let botao = document.getElementById('fw_calendario').querySelectorAll('.fw_calendario_numero_disponivel');
    if (botao.length == 0) {
        return true;
    }

    let quantidade = botao.length;
    let i;
    for (i = 0; i < quantidade; ++i) {
        botao[i].removeEventListener('click', fwCalendarioSelecionarData);
    }

    return true;
};
fwCalendarioEventoSelecionarData = function () {
    let botao = document.getElementById('fw_calendario').querySelectorAll('.fw_calendario_numero_disponivel');
    if (botao.length == 0) {
        return true;
    }

    let quantidade = botao.length;
    let i;
    for (i = 0; i < quantidade; ++i) {
        botao[i].addEventListener('click', fwCalendarioSelecionarData);
    }
};
fwCalendarioSelecionarData = function () {
    if (typeof this === null) {
        return false;
    }

    let fwCalendario = document.getElementById('fw_calendario');

    let id = fwCalendario.getAttribute('data-fwcalendarioid');
    if (!id) {
        return false;
    }

    let input = document.getElementById(id);
    if (!input) {
        return false;
    }

    let deAte = input.getAttribute('data-fwcalendariodeate') == 1 ? true : false;

    let data = this.getAttribute('data-fwcalendario');
    if (!data || !Calendario.validarData(data)) {
        return false;
    }
    data = Calendario.converterData(data, true);

    let hora = input.getAttribute('data-fwcalendariohora') == 1 ? true : false;

    let marcado, marcadoQuantidade;
    if (!deAte) {
        marcado = document.getElementById('fw_calendario').querySelectorAll('.fw_calendario_numero_marcado');
        marcadoQuantidade = marcado.length;

        if (marcadoQuantidade > 0) {
            let i;
            for (i = 0; i < marcadoQuantidade; ++i) {
                marcado[i].classList.remove('fw_calendario_numero_marcado');
            }
        }
    }

    if (!hora && !deAte) {
        if (window.innerWidth <= 1000) {
            this.classList.add('fw_calendario_numero_marcado');
        } else {
            input.value = data;
            Calendario._fechar();
        }
    } else if (deAte) {
        let de = input.getAttribute('data-fwcalendariode');
        let ate = input.getAttribute('data-fwcalendarioate');
        if (!de || !ate) {
            return false;
        }
        let inputDe = document.getElementById(de);
        let inputAte = document.getElementById(ate);

        if (!inputDe || !inputAte) {
            return false;
        }

        if (id == de) {
            marcado = document.querySelector('.fw_calendario_numero_bg_de > .fw_calendario_numero_marcado');

            if (marcado) {
                marcado.classList.remove('fw_calendario_numero_marcado');
                marcado.closest('.fw_calendario_numero_bg_de').classList.remove('fw_calendario_numero_bg_de');
            }

            this.classList.add('fw_calendario_numero_marcado');
            this.closest('.fw_calendario_td').classList.add('fw_calendario_numero_bg_de');
        } else if (id == ate) {
            marcado = document.querySelector('.fw_calendario_numero_bg_ate > .fw_calendario_numero_marcado');

            if (marcado) {
                marcado.classList.remove('fw_calendario_numero_marcado');
                marcado.closest('.fw_calendario_numero_bg_ate').classList.remove('fw_calendario_numero_bg_ate');
            }

            this.classList.add('fw_calendario_numero_marcado');
            this.closest('.fw_calendario_td').classList.add('fw_calendario_numero_bg_ate');
        }

        if (!hora) {
            input.value = data;
            fwCalendarioValidarDeAte(input);
        } else {
            fwCalendarioDataEntreDeAte(input);
        }
    } else if (hora && !deAte) {
        this.classList.add('fw_calendario_numero_marcado');
    }
};

fwCalendarioInArray = function (needle, haystack) {
    let tamanho = haystack.length;
    let i;
    for (i = 0; i < tamanho; i++) {
        if (haystack[i] == needle) return true;
    }
    return false;
};

fwCalendarioKeyGeral = [
    9, 16, 17, 18, 27, 33, 34, 35, 36, 37, 38, 39, 40, 173, 112, 113, 114, 115, 116, 117, 118, 119, 120, 121, 122, 123,
];
fwCalendarioKeyCtrl = [65, 67, 86, 88, 90];
fwCalendarioKeyNumero = [];
for (x = 48; x <= 57; x++) {
    fwCalendarioKeyNumero.push(x);
}
for (x = 96; x <= 105; x++) {
    fwCalendarioKeyNumero.push(x);
}
fwCalendarioMascaraHora = function (e) {
    let input = document.querySelector('.fw_calendario_hora_minuto_manual_input');
    if (!input) {
        return false;
    }

    let tecla = e.keyCode;

    let posicaoInicio = input.selectionStart;
    let posicaoFinal = input.selectionEnd;

    if (tecla == 8 && posicaoInicio == posicaoFinal && posicaoInicio == 3) {
        input.setSelectionRange(2, 2);
        e.preventDefault();
        return false;
    } else if (
        e.ctrlKey ||
        e.metaKey ||
        fwCalendarioInArray(tecla, fwCalendarioKeyGeral) ||
        fwCalendarioInArray(tecla, fwCalendarioKeyCtrl)
    ) {
        return false;
    } else if (!fwCalendarioInArray(tecla, fwCalendarioKeyNumero) && !fwCalendarioInArray(tecla, [8, 46])) {
        e.preventDefault();
        return false;
    }

    e.preventDefault();
    let valorTemporario = input.value;
    let valorTemporarioTamanho = valorTemporario.length;

    let valor = '';
    if (tecla == 8) {
        if (posicaoInicio != posicaoFinal && posicaoInicio > 0) {
            valor =
                valorTemporario.substr(0, posicaoInicio - 1) +
                valorTemporario.substr(posicaoFinal, valorTemporarioTamanho);
        } else if (posicaoInicio != posicaoFinal && posicaoInicio == 0) {
            valor = valorTemporario.substr(posicaoFinal, valorTemporarioTamanho);
        } else if (posicaoInicio == valorTemporarioTamanho) {
            posicaoInicio = 99999999;
            valor = valorTemporario.substr(0, posicaoFinal - 1);
        } else {
            valor =
                valorTemporario.substr(0, posicaoInicio - 1) +
                valorTemporario.substr(posicaoInicio, valorTemporarioTamanho);
        }
    } else {
        let novoNumero = String.fromCharCode(tecla);

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
    }

    let valorLimpo = valor.replace(/[^0-9]/g, '');
    let valorLimpoTamanho = valorLimpo.length;

    let hora = valorLimpo.substr(0, 2);
    let primeiroNumeroHora = valorLimpo.substr(0, 1);
    let minuto = valorLimpo.substr(2, 2);
    let primeiroNumeroMinuto = valorLimpo.substr(2, 1);

    if (valorLimpoTamanho == 0) {
        input.value = '';
    } else if (
        valorLimpoTamanho == 5 ||
        primeiroNumeroHora > 2 ||
        primeiroNumeroMinuto > 5 ||
        hora > 23 ||
        minuto > 59
    ) {
        return false;
    }

    let i;
    let valorNovo = '';
    for (i = 0; i < valorLimpoTamanho; ++i) {
        if (i == 2) {
            valorNovo += ':';
        }
        if (i == 2 && tecla != 8) {
            posicaoInicio++;
        }
        valorNovo += valorLimpo[i];
    }
    input.value = valorNovo;

    if (tecla != 8) {
        input.setSelectionRange(posicaoInicio + 1, posicaoInicio + 1);
    } else {
        input.setSelectionRange(posicaoInicio - 1, posicaoInicio - 1);
    }
};

class Calendario {
    constructor() {
        throw new Error('A class Calendario não pode ser instanciada.');
    }

    static async _tratarOption(option) {
        if (
            typeof option.input == 'undefined' &&
            (typeof option.de == 'undefined' || typeof option.ate == 'undefined')
        ) {
            this._erro = 'Você deve passar um option.input ou option.de e option.ate.';
            return false;
        }

        let lista;
        if (option.input) {
            lista = document.querySelectorAll(option.input);
        } else {
            lista = document.querySelectorAll('#' + option.de + ', ' + '#' + option.ate);
        }

        let blocoGeral = document.getElementById('bloco_fw_calendario');
        if (blocoGeral == null) {
            document.querySelector('body').insertAdjacentHTML('beforeend', '<div id="bloco_fw_calendario"></div>');
            blocoGeral = document.getElementById('bloco_fw_calendario');
        }
        if (option.callback) {
            this._callback = option.callback;
        }
        this._blocoGeral = blocoGeral;

        fwCalendarioInputListar(lista, option);
    }

    static init(option) {
        this._tratarOption(option);

        if (this._erro) {
            return false;
        }
    }

    static _abrir(elemento) {
        fwCalendarioInputAtual = elemento;

        this._elemento = elemento;

        let hora = elemento.getAttribute('data-fwcalendariohora') == 1 ? true : false;
        let dataMinima = elemento.getAttribute('data-fwcalendariominima');
        let dataMaxima = elemento.getAttribute('data-fwcalendariomaxima');
        let deAte = elemento.getAttribute('data-fwcalendariodeate') == 1 ? true : false;
        let de = elemento.getAttribute('data-fwcalendariode');
        let ate = elemento.getAttribute('data-fwcalendarioate');

        this._option = {
            hora: hora,
            dataMinima: dataMinima,
            dataMaxima: dataMaxima,
            deAte: deAte,
            de: document.getElementById(de),
            ate: document.getElementById(ate),
        };

        setTimeout(() => {
            document.addEventListener('mouseup', fwCalendarioFecharBody);
        }, 200);

        this._carregarHtml();
    }
    static _fechar() {
        document.querySelector('body').style['overflow-y'] = 'auto';
        let fwCalendario = document.getElementById('fw_calendario');

        if (fwCalendario.classList.contains('fw_calendario_de_ate')) {
            fwCalendario.classList.remove('fw_calendario_de_ate');
        }
        if (fwCalendario.classList.contains('fw_calendario_hora')) {
            fwCalendario.classList.remove('fw_calendario_hora');
        }
        fwCalendario.removeAttribute('data-fwcalendarioid');

        document.removeEventListener('mouseup', fwCalendarioFecharBody);

        fwCalendario.style.display = 'none';
        if (this._callback) {
            this._callback();
        }
    }
    static staticFechar() {
        this._fechar();
    }

    static _carregarHtml() {
        if (document.getElementById('fw_calendario') == null) {
            this._blocoGeral.innerHTML = `
<div id="fw_calendario">
    <div class="fw_calendario_conteudo">
        <div class="fw_calendario_header">
            <div class="fw_calendario_prev">
                <svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" version="1.1" x="0px" y="0px" viewBox="0 0 100 100"><g transform="translate(0,-952.36218)"><path style="text-indent:0;text-transform:none;direction:ltr;block-progression:tb;baseline-shift:baseline;color:#000000;enable-background:accumulate;" d="m 29.970314,1002.706 a 4.0004,4.0004 0 0 0 0.9688,2.2812 l 32.00003,37 a 4.0004,4.0004 0 1 0 6.0313,-5.25 l -29.71883,-34.375 29.71883,-34.375 a 4.0004,4.0004 0 1 0 -6.0313,-5.25 l -32.00003,37 a 4.0004,4.0004 0 0 0 -0.9688,2.9688 z" fill-opacity="1" stroke="none" marker="none" visibility="visible" display="inline" overflow="visible"/></g></svg>
            </div>
            <div class="fw_calendario_mes_ano">
                <div class="fw_calendario_mes">
                    <div class="fw_calendario_mes_texto"></div>
                    <select class="fw_calendario_mes_select" name="fw_calendario_mes">
                        <option value="1">JANEIRO</option>
                        <option value="2">FEVEREIRO</option>
                        <option value="3">MARÇO</option>
                        <option value="4">ABRIL</option>
                        <option value="5">MAIO</option>
                        <option value="6">JUNHO</option>
                        <option value="7">JULHO</option>
                        <option value="8">AGOSTO</option>
                        <option value="9">SETEMBRO</option>
                        <option value="10">OUTUBRO</option>
                        <option value="11">NOVEMBRO</option>
                        <option value="12">DEZEMBRO</option>
                    </select>
                </div>
                <div class="fw_calendario_ano">
                    <div class="fw_calendario_ano_texto"></div>
                    <select class="fw_calendario_ano_select" name="fw_calendario_ano">
                    </select>
                </div>
            </div>
            <div class="fw_calendario_next">
                <svg xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" version="1.1" x="0px" y="0px" viewBox="0 0 100 100"><g transform="translate(0,-952.36218)"><path style="text-indent:0;text-transform:none;direction:ltr;block-progression:tb;baseline-shift:baseline;color:#000000;enable-background:accumulate;" d="m 70.029671,1002.0184 a 4.0004,4.0004 0 0 0 -0.96875,-2.28122 l -32.00003,-37 a 4.0004,4.0004 0 1 0 -6.0313,5.25 l 29.71883,34.37502 -29.71883,34.375 a 4.0004,4.0004 0 1 0 6.0313,5.25 l 32.00003,-37 a 4.0004,4.0004 0 0 0 0.96875,-2.9688 z" fill-opacity="1" stroke="none" marker="none" visibility="visible" display="inline" overflow="visible"/></g></svg>
            </div>
        </div>

        <div class="fw_calendario_semana">
            <div class="fw_calendario_tr">
                <div class="fw_calendario_td"><div class="fw_calendario_semana_texto">Dom</div></div>
                <div class="fw_calendario_td"><div class="fw_calendario_semana_texto">Seg</div></div>
                <div class="fw_calendario_td"><div class="fw_calendario_semana_texto">Ter</div></div>
                <div class="fw_calendario_td"><div class="fw_calendario_semana_texto">Qua</div></div>
                <div class="fw_calendario_td"><div class="fw_calendario_semana_texto">Qui</div></div>
                <div class="fw_calendario_td"><div class="fw_calendario_semana_texto">Sex</div></div>
                <div class="fw_calendario_td"><div class="fw_calendario_semana_texto">Sab</div></div>
            </div>
            <div class="fw_calendario_hora_valor">
                <input type="text" name="hora" class="fw_calendario_hora_valor_input" placeholder="00:00" inputmode="numeric" value="">
            </div>
        </div>

        <div class="fw_calendario_main">
            <div class="fw_calendario_lista">
                <div class="fw_calendario_mes_numero">
                </div>
                <div class="fw_calendario_mes_numero">
                </div>
                <div class="fw_calendario_mes_numero">
                </div>
            </div>
            <div class="fw_calendario_hora">
            </div>
        </div>

        <div class="fw_calendario_footer">
            <div class="fw_calendario_botao_lista">
                <div class="fw_calendario_botao fw_calendario_botao_cancelar">CANCELAR</div>
                <div class="fw_calendario_botao fw_calendario_botao_hoje fw_calendario_hide">HOJE</div>
                <div class="fw_calendario_botao fw_calendario_botao_agora fw_calendario_hide">AGORA</div>
                <div class="fw_calendario_botao fw_calendario_botao_ok">CONFIRMAR</div>
            </div>
        </div>

    </div>
</div>
            `;

            this._fwCalendario = document.getElementById('fw_calendario');
            this._setarPosicao();
        } else {
            fwCalendarioEventoRemoverData();
            this._fwCalendario.style.display = 'flex';
            this._setarPosicao();
        }

        let fwCalendario = this._fwCalendario;

        window.addEventListener('scroll', fwCalendarioPosicao);
        window.addEventListener('resize', fwCalendarioPosicao);
        fwCalendario.querySelector('.fw_calendario_botao_cancelar').addEventListener('click', fwCalendarioFechar);
        fwCalendario.querySelector('.fw_calendario_botao_hoje').addEventListener('click', fwCalendarioFecharHoje);
        fwCalendario.querySelector('.fw_calendario_botao_agora').addEventListener('click', fwCalendarioFecharAgora);
        fwCalendario.querySelector('.fw_calendario_botao_ok').addEventListener('click', fwCalendarioFecharOk);

        let id = this._elemento.getAttribute('id');
        document.getElementById('fw_calendario').setAttribute('data-fwcalendarioid', id);

        if (true === this._option.hora) {
            fwCalendario.classList.add('fw_calendario_data_hora');
        } else if (fwCalendario.classList.contains('fw_calendario_data_hora')) {
            fwCalendario.classList.remove('fw_calendario_data_hora');
        }

        if (this._option.deAte) {
            fwCalendario.classList.add('fw_calendario_de_ate');
        } else if (fwCalendario.classList.contains('fw_calendario_de_ate')) {
            fwCalendario.classList.remove('fw_calendario_de_ate');
        }

        this._carregarComplementoHtml().then(result => {
            fwCalendarioCarregarFuncao();
        });
    }
    static _setarPosicao() {
        let elemento = this._elemento;
        let calendario = this._fwCalendario;

        let windowWidth = window.innerWidth;
        let windowHeight = window.innerHeight;

        let inputPosicao = elemento.getBoundingClientRect();
        let inputWidth = inputPosicao.width;
        let inputHeight = inputPosicao.height;
        let inputTop = inputPosicao.top;
        let inputLeft = inputPosicao.left;

        let calendarioPosicao = calendario.getBoundingClientRect();
        let calendarioWidth = calendarioPosicao.width;
        let calendarioHeight = calendarioPosicao.height;

        if (inputTop + inputHeight > windowHeight / 2) {
            calendario.style.top = inputTop - calendarioHeight + 'px';
        } else {
            calendario.style.top = inputTop + inputHeight + 'px';
        }

        if (inputLeft + calendarioWidth > windowWidth) {
            calendario.style.left = inputLeft - (calendarioWidth - inputWidth) + 'px';
        } else {
            calendario.style.left = inputLeft + 'px';
        }
    }

    static async _carregarComplementoHtml() {
        return this._colocarListaHora().then(() => {
            return this._colocarListaData().then(() => {
                return true;
            });
        });
    }
    static async _colocarListaHora() {
        let blocoExiste = this._fwCalendario.querySelector('.fw_calendario_hora_minuto_manual');
        if (blocoExiste) {
            return true;
        }

        let bloco = this._fwCalendario.querySelector('.fw_calendario_hora');
        let html =
            '<div class="fw_calendario_hora_minuto fw_calendario_hora_minuto_manual" data-ajuda="Clique aqui para colocar uma data manualmente">MANUAL</div>';
        html +=
            '<input class="fw_calendario_hora_minuto fw_calendario_hora_minuto_manual_input" data-ajuda="Clique aqui para editar" placeholder="MANUAL" value="">';

        let minuto = 0;
        let hora, i;

        for (i = 0; i < 24; ) {
            if (i < 10) {
                hora = '0' + i;
            } else {
                hora = i;
            }

            if (minuto == 0) {
                hora += ':00';
                minuto += 15;
            } else if (minuto == 45) {
                hora += ':' + minuto;
                minuto = 0;
                ++i;
            } else {
                hora += ':' + minuto;
                minuto += 15;
            }

            html += '<div class="fw_calendario_hora_minuto" data-fwcalendariohora="' + hora + '">' + hora + '</div>';
        }

        bloco.innerHTML = html;

        document
            .querySelector('.fw_calendario_hora_minuto_manual_input')
            .addEventListener('keydown', fwCalendarioMascaraHora);

        fwCalendarioEventoSelecionarHora();

        return true;
    }

    static validarHora(horaCompleta) {
        if (!/^[0-2]{1}[0-9]{1}\:[0-5]{1}[0-9]{1}(\:[0-5]{1}[0-9]{1}){0,1}$/.test(horaCompleta)) {
            return false;
        }

        let hora = horaCompleta.split(':')[0];
        let minuto = horaCompleta.split(':')[1];

        if (hora > 23 || minuto > 59) {
            return false;
        }

        return true;
    }
    static validarData(data, br) {
        if (typeof data != 'string' || data == '') {
            return false;
        }

        if (br && !/^[0-3]{1}[0-9]{1}\/[0-1]{1}[0-9]{1}\/[0-9]{4}$/.test(data)) {
            return false;
        } else if (!br && !/^[0-9]{4}\-[0-1]{1}[0-9]{1}\-[0-3]{1}[0-9]{1}$/.test(data)) {
            return false;
        }

        let dia;
        let mes;
        let ano;

        if (br) {
            dia = data.substr(0, 2);
            mes = data.substr(3, 2);
            ano = data.substr(6, 4);
        } else {
            ano = data.substr(0, 4);
            mes = data.substr(5, 2);
            dia = data.substr(8, 2);
        }

        if (mes < 1 || mes > 12) {
            return false;
        } else if ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia > 30) {
            return false;
        } else if ((mes == 1 || mes == 3 || mes == 5 || mes == 7 || mes == 8 || mes == 10 || mes == 12) && dia > 31) {
            return false;
        } else if (ano % 4 != 0 && mes == 2 && dia > 28) {
            return false;
        } else if (ano % 4 == 0 && mes == 2 && dia > 29) {
            return false;
        }

        return true;
    }
    static converterData(data, br) {
        let dia;
        let mes;
        let ano;

        if (br) {
            ano = data.substr(0, 4);
            mes = data.substr(5, 2);
            dia = data.substr(8, 2);

            return dia + '/' + mes + '/' + ano;
        } else {
            dia = data.substr(0, 2);
            mes = data.substr(3, 2);
            ano = data.substr(6, 4);

            return ano + '-' + mes + '-' + dia;
        }
    }

    static async _colocarListaData() {
        let option = this._option;
        let data, valor, valorDe, valorAte, hora;

        let date = new Date();
        let dia = date.getDate();
        if (dia < 10) {
            dia = '0' + dia;
        }
        let mes = date.getMonth() + 1;
        if (mes < 10) {
            mes = '0' + mes;
        }
        let ano = date.getFullYear();
        let hoje = ano + '-' + mes + '-' + dia;

        valor = '';
        data = this.converterData(this._elemento.value, false);
        if (option.deAte) {
            valorDe = this.converterData(option.de.value, false);
            valorAte = this.converterData(option.ate.value, false);
            if (!this.validarData(valorDe, false)) {
                valorDe = '';
            }
            if (!this.validarData(valorAte, false)) {
                valorAte = '';
            }
        } else if (this.validarData(data, false)) {
            valor = data;
        }

        if (!this.validarData(data, false)) {
            data = hoje;
        }

        if (valorDe != '' && valorAte != '' && valorAte < valorDe) {
            valorAte = '';
            this._option.ate.value = '';
        }

        let dataMinima = this._option.dataMinima || '';
        let dataMaxima = this._option.dataMaxima || '';

        if (dataMinima != '' && data < dataMinima) {
            data = dataMinima;
        } else if (dataMaxima != '' && data > dataMaxima) {
            data = dataMaxima;
        }

        return this._colocarListaDataHtml('anterior', data, valor, valorDe, valorAte).then(() => {
            return this._colocarListaDataHtml('atual', data, valor, valorDe, valorAte).then(() => {
                return this._colocarListaDataHtml('proximo', data, valor, valorDe, valorAte).then(() => {
                    fwCalendarioEventoSelecionarData();
                    return this._setarHora().then(() => {
                        return true;
                    });
                });
            });
        });
    }

    static async _colocarListaDataHtml(local, data, valor, valorDe, valorAte) {
        let dateHoje = new Date();
        let diaHoje = dateHoje.getDate();
        if (diaHoje < 10) {
            diaHoje = '0' + diaHoje;
        }
        let mesHoje = dateHoje.getMonth() + 1;
        if (mesHoje < 10) {
            mesHoje = '0' + mesHoje;
        }
        let anoHoje = dateHoje.getFullYear();
        let hoje = anoHoje + '-' + mesHoje + '-' + diaHoje;

        let ano = parseInt(data.split('-')[0]);
        let mes = parseInt(data.split('-')[1]);

        let bloco;
        if (local == 'anterior') {
            bloco = this._fwCalendario.querySelector('.fw_calendario_mes_numero:nth-child(1)');
            mes--;
            if (mes == 0) {
                mes = 12;
                ano--;
            }
        } else if (local == 'atual') {
            bloco = this._fwCalendario.querySelector('.fw_calendario_mes_numero:nth-child(2)');

            let selectMes = this._fwCalendario.querySelector('.fw_calendario_mes_select');
            selectMes.value = mes;
            this._fwCalendario.querySelector('.fw_calendario_mes_texto').textContent =
                selectMes.children[selectMes.selectedIndex].textContent;

            let selectAnoHtml = '';
            let anoInicial = ano - 50;
            let anoFinal = ano + 50;
            let iAno, anoSelected;
            for (iAno = anoInicial; iAno <= anoFinal; ++iAno) {
                anoSelected = '';
                if (iAno == ano) {
                    anoSelected = 'selected';
                }
                selectAnoHtml += '<option value="' + iAno + '" ' + anoSelected + '>' + iAno + '</option>';
            }

            this._fwCalendario.querySelector('.fw_calendario_ano_select').innerHTML = selectAnoHtml;
            this._fwCalendario.querySelector('.fw_calendario_ano_texto').textContent = ano;
        } else if (local == 'proximo') {
            bloco = this._fwCalendario.querySelector('.fw_calendario_mes_numero:nth-child(3)');
            mes++;
            if (mes == 13) {
                mes = 1;
                ano++;
            }
        }

        let mesFinal = mes;
        if (mes < 10) {
            mesFinal = '0' + mes;
        }

        let diaUltimo = new Date(ano, mes, 0).getDate();
        let semanaPrimeiro = new Date(ano, mes - 1, 1).getDay();
        let semanaUltimo = new Date(ano, mes - 1, diaUltimo).getDay();
        let diaUltimoAnterior = new Date(ano, mes - 1, 0).getDate();

        let diaMesProximo = 6 - semanaUltimo;

        let dataMinima = this._option.dataMinima || '';
        let dataMaxima = this._option.dataMaxima || '';

        let dataDeAte = false;
        if (valorDe != '' && valorAte != '') {
            dataDeAte = true;
        }

        let html = '';
        let iGeral = 0;
        let iAnterior, iAtual, iProximo, classe, classeTd, dataFinal, diaFinal, hojeHtml;
        for (iAnterior = 0; iAnterior < semanaPrimeiro; ++iAnterior) {
            if (iGeral == 0) {
                html += '<div class="fw_calendario_tr">';
            }
            html +=
                '<div class="fw_calendario_td"><div class="fw_calendario_numero fw_calendario_outro_mes">' +
                (diaUltimoAnterior - 6 + (iAnterior + 1)) +
                '</div></div>';
            if (iGeral == 6) {
                iGeral = 0;
                html += '</div>';
            } else {
                ++iGeral;
            }
        }
        for (iAtual = 1; iAtual <= diaUltimo; ++iAtual) {
            if (iGeral == 0) {
                html += '<div class="fw_calendario_tr">';
            }

            if (iAtual < 10) {
                diaFinal = '0' + iAtual;
            } else {
                diaFinal = iAtual;
            }

            dataFinal = ano + '-' + mesFinal + '-' + diaFinal;

            classe = '';
            classeTd = '';
            hojeHtml = '';
            if ((dataMinima != '' && dataFinal < dataMinima) || (dataMaxima != '' && dataFinal > dataMaxima)) {
                classe += ' fw_calendario_numero_bloqueado';
            } else {
                classe += ' fw_calendario_numero_disponivel';
                if (iGeral == 0 || iGeral == 6) {
                    classe += ' fw_calendario_numero_fim_semana';
                }
                if (dataFinal == hoje) {
                    classe += ' fw_calendario_numero_hoje';
                    hojeHtml = '<div class="fw_calendario_numero_hoje_texto">HOJE</div>';
                }

                if (dataFinal == valor) {
                    classe += ' fw_calendario_numero_marcado';
                }
                if (dataFinal == valorDe) {
                    classe += ' fw_calendario_numero_marcado';
                    classeTd += ' fw_calendario_numero_bg_de';
                    if (valorAte) {
                        classeTd += ' fw_calendario_numero_bg_ativo';
                    }
                }
                if (dataFinal == valorAte) {
                    classe += ' fw_calendario_numero_marcado';
                    classeTd += ' fw_calendario_numero_bg_ate';
                    if (valorDe) {
                        classeTd += ' fw_calendario_numero_bg_ativo';
                    }
                }
                if (valorDe && valorAte && dataDeAte && dataFinal > valorDe && dataFinal < valorAte) {
                    classeTd = ' fw_calendario_numero_bg_ativo';
                }
            }

            html +=
                `
                <div class="fw_calendario_td ` +
                classeTd +
                `">
                    <div class="fw_calendario_numero_bg_prev"></div>
                    <div data-fwCalendario="` +
                dataFinal +
                `" class="fw_calendario_numero ` +
                classe +
                `">` +
                iAtual +
                `</div>
                    ` +
                hojeHtml +
                `
                    <div class="fw_calendario_numero_bg_next"></div>
                </div>
            `;
            if (iGeral == 6) {
                iGeral = 0;
                html += '</div>';
            } else {
                ++iGeral;
            }
        }
        for (iProximo = 1; iProximo <= diaMesProximo; ++iProximo) {
            if (iGeral == 0) {
                html += '<div class="fw_calendario_tr">';
            }
            html +=
                '<div class="fw_calendario_td"><div class="fw_calendario_numero fw_calendario_outro_mes">' +
                iProximo +
                '</div></div>';
            if (iGeral == 6) {
                iGeral = 0;
                html += '</div>';
            } else {
                ++iGeral;
            }
        }

        bloco.innerHTML = html;
    }

    static _resetarHora() {
        let fwCalendario = this._fwCalendario;
        let manualInput = fwCalendario.querySelector('.fw_calendario_hora_minuto_manual_input');
        fwCalendario.querySelector('.fw_calendario_hora_minuto_manual').style.display = 'block';
        manualInput.classList.remove('fw_calendario_hora_minuto_marcado');
        manualInput.value = '';
        fwCalendario.querySelector('.fw_calendario_hora').scrollTop = 0;
    }
    static async _setarHora() {
        let fwCalendario = this._fwCalendario;
        let blocoMarcado = fwCalendario.querySelectorAll('.fw_calendario_hora_minuto_marcado');
        let blocoMarcadoQuantidade = blocoMarcado.length;
        if (blocoMarcadoQuantidade > 0) {
            let i;
            for (i = 0; i < blocoMarcadoQuantidade; ++i) {
                blocoMarcado[i].classList.remove('fw_calendario_hora_minuto_marcado');
            }
        }

        fwCalendario.querySelector('.fw_calendario_hora_minuto_manual').style.display = 'block';
        fwCalendario.querySelector('.fw_calendario_hora_minuto_manual_input').value = '';

        let valor = this._elemento.value;
        if (valor.indexOf(' ') === -1) {
            this._resetarHora();
            return false;
        }
        let horaCompleta = valor.split(' ')[1].replace(/ /g, '');
        if (!this.validarHora(horaCompleta)) {
            this._resetarHora();
            return false;
        }

        let hora = horaCompleta.split(':')[0];
        let minuto = horaCompleta.split(':')[1];
        if (minuto == '00' || minuto == '15' || minuto == '30' || minuto == '45') {
            let blocoHora = fwCalendario.querySelector(
                '.fw_calendario_hora_minuto[data-fwcalendariohora="' + hora + ':' + minuto + '"]'
            );
            if (typeof blocoHora != 'object') {
                return false;
            }
            blocoHora.classList.add('fw_calendario_hora_minuto_marcado');
            let scrollTop = blocoHora.offsetTop;
            fwCalendario.querySelector('.fw_calendario_hora').scrollTop = scrollTop - 30;
        } else {
            fwCalendario.querySelector('.fw_calendario_hora_minuto_manual').style.display = 'none';
            fwCalendario
                .querySelector('.fw_calendario_hora_minuto_manual_input')
                .classList.add('fw_calendario_hora_minuto_marcado');
            fwCalendario.querySelector('.fw_calendario_hora_minuto_manual_input').value = hora + ':' + minuto;
            fwCalendario.querySelector('.fw_calendario_hora').scrollTop = 0;
        }
    }
}
