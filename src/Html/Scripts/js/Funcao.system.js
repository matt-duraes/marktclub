// const $ = document.querySelector.bind(document);
const $ = (seletor, pai) => {
    const paiElemento = typeof pai === 'string' ? document.querySelector(pai) : pai;
    return (paiElemento || document).querySelector(seletor);
};
const $$ = (seletor, pai) => {
    const paiElemento = typeof pai === 'string' ? document.querySelector(pai) : pai;
    return (paiElemento || document).querySelectorAll(seletor);
};
const ppe = console.log.bind(console);

Object.defineProperty(Object.prototype, 'displayShow', {
    value() {
        let elemento = this;
        if (!(elemento instanceof NodeList)) {
            elemento = [elemento];
        }

        for (item of elemento) {
            item.classList.remove('display_none');
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'aparecer', {
    value() {
        let elemento = this;
        if (!(elemento instanceof NodeList)) {
            elemento = [elemento];
        }

        for (item of elemento) {
            item.classList.remove('display_none');
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'remover', {
    value() {
        let elemento = this;
        if (!(elemento instanceof NodeList)) {
            elemento = [elemento];
        }

        for (item of elemento) {
            item.remove();
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'displayHide', {
    value() {
        let elemento = this;
        if (!(elemento instanceof NodeList)) {
            elemento = [elemento];
        }

        for (item of elemento) {
            item.classList.add('display_none');
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'sumir', {
    value() {
        let elemento = this;
        if (!(elemento instanceof NodeList)) {
            elemento = [elemento];
        }

        for (item of elemento) {
            item.classList.add('display_none');
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'html', {
    value(html) {
        let elemento = this;
        let retornoLista = true;
        if (!(elemento instanceof NodeList)) {
            retornoLista = false;
            elemento = [elemento];
        }

        let retorno = [];
        for (item of elemento) {
            if (html == undefined) {
                retorno.push(item.innerHTML);
                continue;
            }
            item.innerHTML = html;
        }
        if (html == undefined) {
            return retornoLista ? retorno : retorno[0];
        }
        return this;
    },
    writable: true,
    configurable: true,
});

Object.defineProperty(Object.prototype, 'texto', {
    value(texto) {
        let elemento = this;
        let retornoLista = true;
        if (!(elemento instanceof NodeList)) {
            retornoLista = false;
            elemento = [elemento];
        }

        let retorno = [];
        for (item of elemento) {
            if (texto == undefined) {
                retorno.push(item.innerText);
                continue;
            }
            item.innerText = texto;
        }
        if (texto == undefined) {
            return retornoLista ? retorno : retorno[0];
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'valor', {
    value(valor) {
        let elemento = this;
        let retornoLista = true;
        if (!(elemento instanceof NodeList)) {
            retornoLista = false;
            elemento = [elemento];
        }

        let retorno = [];
        for (item of elemento) {
            if (valor == undefined) {
                retorno.push(item.value);
                continue;
            }
            formValue(item, valor);
        }
        if (valor == undefined) {
            return retornoLista ? retorno : retorno[0];
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'inicio', {
    value(html) {
        let elemento = this;
        if (elemento instanceof NodeList) {
            elemento = elemento[0];
        }

        if (typeof html === 'string') {
            elemento.insertAdjacentHTML('afterbegin', html);
        } else {
            elemento.insertBefore(html, elemento.firstChild);
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'final', {
    value(html) {
        let elemento = this;
        if (elemento instanceof NodeList) {
            elemento = elemento[0];
        }

        if (typeof html === 'string') {
            elemento.insertAdjacentHTML('beforeend', html);
        } else {
            elemento.appendChild(html);
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'css', {
    value(propriedade, valor) {
        let elemento = this;
        let retornoLista = true;
        if (!(elemento instanceof NodeList)) {
            retornoLista = false;
            elemento = [elemento];
        }
        let retorno = [];
        for (const item of elemento) {
            if (typeof propriedade == 'string' && valor == undefined) {
                const style = window.getComputedStyle(item);
                retorno.push(style.getPropertyValue(propriedade));
                continue;
            }
            if (typeof propriedade == 'string') {
                item.style[propriedade] = valor;
            } else if (typeof propriedade == 'object') {
                Object.entries(propriedade).forEach(val => {
                    item.style[val[0]] = val[1];
                });
            }
        }
        if (valor == undefined) {
            return retornoLista ? retorno : retorno[0];
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'classe', {
    value(classe, acao) {
        let elemento = this;
        if (!(elemento instanceof NodeList)) {
            elemento = [elemento];
        }
        let existe = true;
        for (const item of elemento) {
            let retorno;
            if (typeof classe == 'string') {
                retorno = fwClasseEvento(item, classe, acao);
            } else if (typeof propriedade == 'object') {
                for (const nome of classe) {
                    retorno = fwClasseEvento(item, classe, acao);
                }
            }
            if (acao == '?' && false === retorno) {
                existe = false;
            }
        }
        return acao == '?' ? existe : this;
    },
    writable: true,
    configurable: true,
});
const fwClasseEvento = (item, classe, acao) => {
    if (acao == undefined) {
        return item.classList.toggle(classe);
    } else if (true === acao) {
        return item.classList.add(classe);
    } else if (false === acao) {
        return item.classList.remove(classe);
    } else if ('?' == acao) {
        return item.classList.contains(classe);
    }
};
Object.defineProperty(Object.prototype, 'attr', {
    value(propriedade, valor) {
        let elemento = this;
        let retornoLista = true;
        if (!(elemento instanceof NodeList)) {
            retornoLista = false;
            elemento = [elemento];
        }
        let retorno = [];
        for (const item of elemento) {
            if (typeof propriedade == 'string' && valor == undefined) {
                retorno.push(item.getAttribute(propriedade));
                continue;
            }
            if (typeof propriedade == 'string') {
                item.setAttribute(propriedade, valor);
            } else if (typeof propriedade == 'object') {
                Object.entries(propriedade).forEach(val => {
                    item.setAttribute(val[0], val[1]);
                });
            }
        }
        if (valor == undefined) {
            return retornoLista ? retorno : retorno[0];
        }
        return this;
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'evento', {
    value(evento, callback) {
        let elemento = this;
        if (!(elemento instanceof NodeList)) {
            elemento = [elemento];
        }
        for (const item of elemento) {
            if (evento == 'enter') {
                item.addEventListener('keydown', e => {
                    if (e.key == 'Enter') {
                        callback(e, item);
                    }
                });
                continue;
            }
            item.addEventListener(evento, e => {
                callback(e, item);
            });
        }
    },
    writable: true,
    configurable: true,
});
Object.defineProperty(Object.prototype, 'clonar', {
    value() {
        let elemento = this;
        if (elemento instanceof NodeList) {
            elemento = elemento[0];
        }
        const clone = elemento.cloneNode(true);
        clone.removeAttribute('id');
        const listaId = clone.querySelectorAll('*[id]');
        for (const item of listaId) {
            item.removeAttribute('id');
        }
        return clone;
    },
    writable: true,
    configurable: true,
});

const FW_BLOCO_LOGIN = $('#bloco_login_relogar');
const LINK = $('#LINK') ? $('#LINK').value : undefined;
const LINK_PADRAO = $('#LINK_PADRAO') ? $('#LINK_PADRAO').value : LINK;
const BODY = $('body');

const inArray = (element, array) => {
    return array.indexOf(element) !== -1;
};

const elemento = (elemento, attr, css) => {
    const html = document.createElement(elemento);
    if (typeof attr == 'object') {
        html.attr(attr);
    }
    if (typeof css == 'object') {
        html.css(css);
    }
    return html;
};

const ajudaLoading = bloco => {
    const listaAjuda = bloco.attr('data-ajuda') != null ? [bloco] : bloco.querySelectorAll('*[data-ajuda]');
    listaAjuda.forEach(bloco => {
        bloco.addEventListener('mouseover', () => {
            const texto = bloco.getAttribute('data-ajuda');
            Ajuda.show(bloco, texto);
        });
        bloco.addEventListener('mouseout', () => {
            Ajuda.hide();
        });
    });
};

const link = () => {
    return window.location.href.replace('://', ':||').split('/')[0].replace(':||', '://');
};
const url = () => {
    return window.location.href.split('#')[0];
};
const uri = () => {
    return window.location.href.replace(/http(s)\:\/\/[a-zà-úA-ZÀ-Ú0-9\-\_\.\:]+\//, '');
};
const base64Encode = string => {
    return window.btoa(string);
};
const base64Decode = string => {
    return window.atob(string);
};

ajaxGet = async (link, body, erro, opcao) => {
    return await ajax(link, 'GET', body, erro, opcao);
};
ajaxPost = async (link, body, erro, opcao) => {
    return await ajax(link, 'POST', body, erro, opcao);
};
ajaxPut = async (link, body, erro, opcao) => {
    return await ajax(link, 'PUT', body, erro, opcao);
};
ajaxDelete = async (link, body, erro, opcao) => {
    return await ajax(link, 'DELETE', body, erro, opcao);
};
ajax = async (link, metodo, body, erro, opcao) => {
    if (opcao == undefined || !opcao instanceof Object) {
        opcao = {};
    }

    if (body != undefined && body instanceof Object && metodo == 'POST') {
        const dado = new FormData();
        Object.entries(body).forEach(valores => {
            const [indice, valor] = valores;
            if (Array.isArray(valor)) {
                valor.forEach(val => {
                    const ind = indice + '[]';
                    dado.append(ind, val);
                });
            } else {
                dado.append(indice, valor);
            }
        });
        opcao.body = dado;
    } else if (body != undefined && body instanceof Object && (metodo == 'GET' || metodo == 'PUT')) {
        let lista = [];
        Object.entries(body).forEach(valores => {
            const [indice, valor] = valores;
            lista.push(indice + '=' + encodeURI(valor));
        });
        lista = lista.join('&');
        link = link.includes('?') ? link + '&' + lista : link + '?' + lista;
    }
    opcao.method = metodo;

    const resposta = await fetch(link, opcao);
    const status = resposta.status;
    if (status == 204) {
        return true;
    }
    const mensagemErro = erro == undefined ? 'Erro a fazer a requisição, por favor, tente novamente.' : erro;
    let json;
    try {
        json = await resposta.json();
    } catch (e) {
        if (mensagemErro != '') {
            Alerta.notificacao(mensagemErro, false);
        }
        return false;
    }
    const respostaJson = json instanceof Object;
    if (!respostaJson || json.status == undefined) {
        if (mensagemErro != '') {
            Alerta.notificacao(mensagemErro, false);
        }
        return false;
    } else if (
        json.status == 'erro' &&
        json.erro != undefined &&
        json.erro.codigo != undefined &&
        json.erro.codigo == 4001 &&
        FW_BLOCO_LOGIN
    ) {
        fwLogin();
        return false;
    } else if (json.status != 'sucesso') {
        if (typeof erro === 'string' && erro == '') {
            return false;
        }
        Alerta.notificacao(
            json.erro != undefined && json.erro.mensagem != undefined ? json.erro.mensagem : mensagemErro,
            false
        );
        return false;
    }
    return json;
};

const fwLogin = () => {
    if (!FW_BLOCO_LOGIN) {
        return;
    }
    FW_BLOCO_LOGIN.classList.remove('display_none');
    setTimeout(() => {
        FW_BLOCO_LOGIN.classList.add('ativo');
    }, 40);
};

const limparFormulario = form => {
    const lista = form.querySelectorAll('.input_select_value, .input_geral');
    if (lista.length == 0) {
        return;
    }
    lista.forEach(item => {
        formValue(item, '');
    });
};

/*
|--------------------------------------------------------------------------
| FUNÇÕES DE VALIDAÇÃO
|--------------------------------------------------------------------------
|
| Funções para validar os dados padrões ainda no JS
| não deixa de ser necessário a validação no backend
|
*/
const validarInput = bloco => {
    return new Promise(resolve => {
        const lista = bloco.querySelectorAll('.input_obrigatorio');
        const quantidade = lista.length;
        let i = 0;
        let item;
        let valido = true;
        for (; i < quantidade; ++i) {
            item = lista[i];
            if (item.value == '') {
                Alerta.notificacao('O campo "' + item.getAttribute('placeholder') + '" é obrigatório.', false);
                valido = false;
                return false;
            }
        }
        resolve(valido);
    });
};
const validarJson = function (json) {
    if (typeof json == 'string') {
        try {
            JSON.parse(json);
            return true;
        } catch (e) {
            return false;
        }
    }
    return false;
};
const jsonParse = json => {
    try {
        JSON.parse(json);
        return JSON.parse(json);
    } catch (e) {
        return json;
    }
};
const validarEmail = function (email) {
    let reg = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/;
    return reg.test(email);
};
const validarTelefone = function (telefone, formato, retorno) {
    let telefoneNumero = telefone.replace(/[^\d]+/g, '');
    let tamanho = telefoneNumero.length;

    if (tamanho != 10 && tamanho != 11) {
        return false;
    }

    let ddd = telefoneNumero.substr(0, 2);
    let validar =
        (ddd >= 10 && tamanho == 10 && telefoneNumero[2] != 9) ||
        (ddd >= 10 && tamanho == 11 && telefoneNumero[2] == 9);

    let regTelefone = /^\([1-9]{1}[0-9]{1}\)\ [0-9]{4}\-[0-9]{4}$/;
    let regCelular = /^\([1-9]{1}[0-9]{1}\)\ 9[0-9]{4}\-[0-9]{4}$/;

    let digito9 = telefoneNumero[2] == 9;

    if (false !== formato && true === digito9 && !regCelular.test(telefone)) {
        return false;
    } else if (false !== formato && false === digito9 && !regTelefone.test(telefone)) {
        return false;
    }

    if (validar && true === retorno && 11 === tamanho) {
        return (
            '(' + telefoneNumero.substr(0, 2) + ') ' + telefoneNumero.substr(2, 5) + '-' + telefoneNumero.substr(7, 4)
        );
    } else if (validar && true === retorno && 10 === tamanho) {
        return (
            '(' + telefoneNumero.substr(0, 2) + ') ' + telefoneNumero.substr(2, 4) + '-' + telefoneNumero.substr(6, 4)
        );
    } else if (validar) {
        return true;
    }

    return false;
};

const validarCpf = function (cpf) {};

const validarCnpj = function (cnpj) {};

const validarCep = function (cep) {
    cepNumero = cep.replace(/[^\d]+/g, '');
    if (cepNumero.length != 8) {
        return false;
    }

    let reg = /^[0-9]{5}\-[0-9]{3}$/;
    if (!reg.test(cep)) {
        return false;
    }

    return true;
};
const _validarData = function (data) {
    data = data.split('/');
    let dia = data[0];
    let mes = data[1];
    let ano = data[2];

    if (
        mes < 1 ||
        mes > 12 ||
        ((mes == 4 || mes == 6 || mes == 9 || mes == 11) && dia > 30) ||
        ((mes == 1 || mes == 3 || mes == 5 || mes == 7 || mes == 8 || mes == 10 || mes == 12) && dia > 31) ||
        (ano % 4 != 0 && mes == 2 && dia > 28) ||
        (ano % 4 == 0 && mes == 2 && dia > 29)
    ) {
        return false;
    }
    return true;
};
const _validarHora = function (horaCompleta) {
    horaCompleta = horaCompleta.split(':');

    let hora = horaCompleta[0];
    let minuto = horaCompleta[1];
    let segundo = horaCompleta[2];

    if (hora < 0 || hora > 23 || minuto < 0 || minuto > 59 || segundo < 0 || segundo > 59) {
        return false;
    }
    return true;
};
const validarData = function (data) {
    let reg = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
    if (!reg.test(data)) {
        return false;
    }
    return _validarData(data);
};
const validarDataHora = function (data) {
    let reg = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}\ [0-9]{2}\:[0-9]{2}\:[0-9]{2}$/;
    if (!reg.test(data)) {
        return false;
    }
    if (!_validarData(data.split(' ')[0])) {
        return false;
    }
    if (!_validarHora(data.split(' ')[1])) {
        return false;
    }
    return true;
};
const validarDinheiro = function (valor) {
    return /^(\d{1,3}(\.\d{3})*|\d+)(\,\d{2})?$/.test(valor);
};

const isNumeric = function (valor, string) {
    if (typeof valor == 'string' && string) {
        return /^(\+|\-|\+ |\- )?[0-9]+(\.[0-9]+)?$/.test(valor);
    }
    return isTypeOf(valor, 'number');
};
const isFloat = function (valor, string) {
    if (!isNumeric(valor, string)) {
        return false;
    }

    return /^(\-|\+)?[0-9]+\.[0-9]+/.test(valor);
};
const isInt = function (valor, string) {
    if (string && /^(\-)?[0-9]+$/.test(valor)) {
        return true;
    }
    return Number.isInteger(valor);
};
const isString = function (valor) {
    return isTypeOf(valor, 'string');
};
const isBool = function (valor) {
    return isTypeOf(valor, 'boolean');
};
const isUndefined = function (valor) {
    return isTypeOf(valor, 'undefined');
};
const isObject = function (valor) {
    return isTypeOf(valor, 'object');
};
const isFunction = function (valor) {
    return isTypeOf(valor, 'function');
};
const isArray = function (valor) {
    return Array.isArray(valor);
};

/*
|--------------------------------------------------------------------------
| FUNÇÕES PARA CRIAÇÃO DE NÚMEROS ALEATÓRIOS
|--------------------------------------------------------------------------
|
| Gerador de dados aleatórios
|
*/
const uuid = function () {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        let r = (Math.random() * 16) | 0,
            v = c == 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
};

const numeroAleatorio = function (max) {
    return Math.floor(Math.random() * max + 1);
};
function gerarId(prefixo) {
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    let id = '';
    for (let i = 0; i < 10; i++) {
        id += chars[Math.floor(Math.random() * chars.length)];
    }
    return prefixo == undefined ? id : prefixo + '_' + id;
}

const slug = function (string) {
    return string
        .toString()
        .normalize('NFD')
        .replace(/[\u0300-\u036f]/g, '')
        .toLowerCase()
        .trim()
        .replace(/\s+/g, '-')
        .replace(/[^\w\-]+/g, '')
        .replace(/\-\-+/g, '-');
};

const respostaJson = (resposta, mensagem) => {
    return new Promise(async resolve => {
        const status = resposta.status;
        if (status == 204) {
            return resolve(true);
        }

        let json;
        try {
            json = await resposta.json();
        } catch (error) {
            json = {};
        }

        const blocoLogin = document.querySelector('#bloco_usuario_relogar');
        if ((status == 200 || status == 201) && json.status == 'sucesso') {
            return resolve(json);
        } else if (status == 401 && json.status == 'deslogado' && blocoLogin) {
            blocoLogin.classList.remove('display_none');
            document.getElementById('input_relogar_senha').value = '';
            document.getElementById('bloco_usuario_relogar_inativo').classList.remove('display_none');
            setTimeout(() => {
                blocoLogin.classList.add('ativo');
            }, 50);
            return resolve(false);
        }

        if (mensagem !== undefined) {
            Alerta.notificacao(
                json.erro != undefined && json.erro.mensagem != undefined ? json.erro.mensagem : mensagem,
                false
            );
        }
        return resolve(false);
    });
};

const adicionarEventoEnter = (lista, callback) => {
    if (!('forEach' in lista)) {
        lista = [lista];
    }
    lista.forEach(input => {
        input.addEventListener('keydown', e => {
            if (e.key == 'Enter') {
                e.preventDefault();
                callback();
            }
        });
    });
};
const adicionarEvento = (evento, lista, callback) => {
    if (!('forEach' in lista)) {
        lista = [lista];
    }
    lista.forEach(input => {
        input.addEventListener(evento, () => {
            callback();
        });
    });
};

/*
|--------------------------------------------------------------------------
| ENDEREÇO
|--------------------------------------------------------------------------
*/
const buscarEnderecoPeloCep = (
    inputCep,
    inputLogradouro,
    inputNumero,
    inputBairro,
    inputCidade,
    inputEstado,
    browser,
    botao
) => {
    if (botao !== undefined) {
        botao.addEventListener('click', async () => {
            buscarEnderecoNoBackEnd(
                inputCep,
                inputLogradouro,
                inputNumero,
                inputBairro,
                inputCidade,
                inputEstado,
                browser
            );
        });
    } else {
        inputCep.addEventListener('formChange', async () => {
            buscarEnderecoNoBackEnd(
                inputCep,
                inputLogradouro,
                inputNumero,
                inputBairro,
                inputCidade,
                inputEstado,
                browser
            );
        });
    }

    const buscarEnderecoNoBackEnd = async (
        inputCep,
        inputLogradouro,
        inputNumero,
        inputBairro,
        inputCidade,
        inputEstado,
        browser
    ) => {
        const cep = inputCep.value;
        if (cep == '') {
            return;
        }
        Loading.show();
        const resposta = await ajaxPost(LINK_PADRAO + '/__endereco-cep', { cep }, '');
        Loading.hide();

        formSelectOption(inputCidade, { '': 'Escolha um estado' });
        if (false === resposta || resposta.dado == undefined) {
            return;
        }
        const dado = resposta.dado;
        formValue(inputLogradouro, dado.logradouro);
        formValue(inputBairro, dado.bairro);
        formValue(inputCidade, dado.cidade);
        formValue(inputEstado, dado.estado);

        if (dado.logradouro != '') {
            inputNumero.focus();
        }
        if (dado.estado != '' && inputCidade.classList.contains('input_select_value')) {
            if (browser == undefined) {
                buscarCidadePeloEstado(inputCidade, dado.estado, dado.cidade, 'Escolha uma cidade');
            } else {
                buscarCidadePeloEstadoViaBrowser(inputCidade, dado.estado, dado.cidade, 'Escolha uma cidade');
            }
        } else if (inputCidade.classList.contains('input_select_value')) {
            formSelectOption(inputCidade, { '': 'Escolha um estado' });
        } else {
            formValue(inputCidade, '');
        }
    };
};

buscarCidadePeloEstado = async (inputCidade, estado, valor, titulo) => {
    if (estado == '') {
        formSelectOption(inputCidade, { '': 'Escolha um estado' });
        return;
    }
    formSelectLoading(inputCidade);
    const resposta = await ajaxPost(LINK_PADRAO + '/__endereco-cidade', { estado, titulo }, '');
    if (false === resposta) {
        buscarCidadePeloEstadoViaBrowser(inputCidade, estado, valor, titulo);
        return;
    }
    formSelectOption(inputCidade, resposta.dado, valor);
};

buscarCidadePeloEstadoViaBrowser = async (inputCidade, estado, valor, titulo) => {
    if (estado == '') {
        formSelectOption(inputCidade, { '': titulo == undefined || titulo == '' ? 'Escolha um estado' : titulo });
        return;
    }
    formSelectLoading(inputCidade);
    const resposta = await fetch(
        'https://servicodados.ibge.gov.br/api/v1/localidades/estados/' + estado + '/municipios',
        {
            method: 'GET',
        }
    );
    const cidade = {};
    try {
        const json = await resposta.json();
        if (titulo != undefined && titulo != '') {
            cidade[''] = titulo;
        }
        json.forEach(item => {
            const nome = item.nome;
            cidade[nome] = nome;
        });
    } catch (error) {}
    formSelectOption(inputCidade, cidade, valor);
};
