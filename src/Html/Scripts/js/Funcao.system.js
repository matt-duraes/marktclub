link = () => {
    return window.location.href.replace('://', ':||').split('/')[0].replace(':||', '://');
};
url = () => {
    return window.location.href.split('#')[0];
};
uri = () => {
    return window.location.href.replace(/http(s)\:\/\/[a-zà-úA-ZÀ-Ú0-9\-\_\.\:]+\//, '');
};
base64Encode = string => {
    return window.btoa(string);
};
base64Decode = string => {
    return window.atob(string);
};
// Get Elementos
echo = function (dado) {
    console.log(dado);
};
el = function (nome, pai) {
    if (typeof pai == 'object') {
        return pai.querySelector(nome);
    } else if (typeof pai == 'string') {
        pai = document.querySelector(pai);
        if (pai) {
            return pai.querySelector(nome);
        }
        return null;
    } else {
        return document.querySelector(nome);
    }
};
getId = function (id) {
    return document.getElementById(id);
};
getClass = function (classe, pai) {
    if (typeof pai == 'object') {
        return pai.getElementsByClassName(classe);
    } else if (typeof pai == 'string') {
        pai = document.querySelector(pai);
        if (pai) {
            return pai.getElementsByClassName(classe);
        }
        return null;
    } else {
        return document.getElementsByClassName(classe);
    }
};
getTag = function (tag, pai) {
    if (typeof pai == 'object') {
        return pai.getElementsByTagName(tag);
    } else if (typeof pai == 'string') {
        pai = document.querySelector(pai);
        if (pai) {
            return pai.getElementsByTagName(tag);
        }
        return null;
    } else {
        return document.getElementsByTagName(tag);
    }
};
getAll = (elemento, pai) => {
    if (typeof pai == 'object') {
        return pai.querySelectorAll(elemento);
    } else if (typeof pai == 'string') {
        pai = document.querySelector(pai);
        if (pai) {
            return pai.querySelectorAll(elemento);
        }
        return null;
    } else {
        return document.querySelectorAll(elemento);
    }
};
count = (elemento, pai) => {
    return getAll(elemento, pai).length;
};

// InArray
arrayCompare = function (a1, a2) {
    if (a1.length != a2.length) return false;
    let tamanho = a2.length;
    let i;
    for (i = 0; i < tamanho; i++) {
        if (a1[i] !== a2[i]) return false;
    }
    return true;
};
inArray = function (needle, haystack) {
    console.log(needle);
    console.log(haystack);
    let tamanho = haystack.length;
    let i;
    for (i = 0; i < tamanho; i++) {
        if (typeof haystack[i] == 'object') {
            if (arrayCompare(haystack[i], needle)) return true;
        } else {
            if (haystack[i] == needle) return true;
        }
    }
    return false;
};

// AJAX
ajax = function (link, option) {
    let dado = {};

    dado.method = option.metodo;
    if (typeof dado.method == 'undefined' || !inArray(dado.method, ['GET', 'POST', 'PUT', 'DELETE'])) {
        echo('Informe um METHOD para a requisição');
        return false;
    }

    if (typeof option.redirecionar == 'string') {
        dado.redirect = option.redirecionar;
    } else {
        dado.redirect = 'follow';
    }
    if (typeof option.headers == 'object') {
        dado.headers = option.headers;
    }

    if (typeof option.sucesso != 'function') {
        echo('Informa um callback de sucesso.');
        return false;
    }
    let returnoSucesso = option.sucesso;

    if (typeof option.falha != 'function') {
        echo('Informa um callback de falha.');
        return false;
    }
    let retornoFalha = option.falha;

    if (typeof option.erro != 'function') {
        echo('Informa um callback de erro.');
        return false;
    }
    let retornoErro = option.erro;

    let retornoFim = () => {};
    if (typeof option.fim == 'function') {
        retornoFim = option.fim;
    }

    let retornoTipo = 'text';
    if (typeof option.retorno == 'string' && inArray(option.retorno, ['arrayBuffer', 'blob', 'json', 'formData'])) {
        retornoTipo = option.retorno;
    }

    if (typeof option.parametro != 'undefined') {
        let parametro = [];
        Object.entries(option.parametro).forEach(val => {
            parametro.push(encodeURI(val[0]) + '=' + encodeURI(val[1]));
        });

        if (parametro.length > 0) {
            parametro = parametro.join('&');
        }

        if (link.indexOf('?') === -1) {
            link += '?' + parametro;
        } else {
            link += '&' + parametro;
        }
    }

    if (typeof option.dataJson != 'undefined') {
        dado.body = JSON.stringify(option.dataJson);
    } else if (typeof option.dataForm == 'object') {
        let formData = new FormData();
        Object.entries(option.dataForm).forEach(val => {
            formData.append(val[0], val[1]);
        });
        dado.body = formData;
    } else if (typeof option.formData == 'object') {
        let formData = new FormData();
        Object.entries(option.formData).forEach(val => {
            formData.append(val[0], val[1]);
        });
        dado.body = formData;
    } else if (typeof option.body == 'object') {
        let formData = new FormData();
        Object.entries(option.body).forEach(val => {
            formData.append(val[0], val[1]);
        });
        dado.body = formData;
    } else if (typeof option.dataUrl == 'object') {
        let urlEncoded = new URLSearchParams();
        Object.entries(option.dataUrl).forEach(val => {
            urlEncoded.append(val[0], val[1]);
        });
        dado.body = urlEncoded;
    }

    dado.mode = 'same-origin';
    if (typeof option.modo == 'string' && inArray(option.modo, ['no-cors', 'cors', 'navigate'])) {
        dado.mode = option.retorno;
    }

    dado.cache = 'default';
    if (
        typeof option.cache == 'string' &&
        inArray(option.cacbe, ['no-store', 'reload', 'no-cache', 'force-cache', 'only-if-cached'])
    ) {
        dado.cache = option.retorno;
    }

    fetch(link, dado)
        .then(response => {
            let headers = {};
            response.headers.forEach((val, ind) => {
                if (typeof ind == 'string' && ind != '') {
                    headers[ind] = val;
                }
            });

            if (inArray(dado.method, ['PUT', 'DELETE']) && response.status == 204) {
                retornoFim();
                return returnoSucesso({
                    dado: '',
                    status: 204,
                    header: headers,
                    retorno: response,
                });
            }

            let promiseValue;
            if (retornoTipo == 'json') {
                promiseValue = response.json();
            } else if (retornoTipo == 'arrayBuffer') {
                promiseValue = response.arrayBuffer();
            } else if (retornoTipo == 'blob') {
                promiseValue = response.blob();
            } else if (retornoTipo == 'formData') {
                promiseValue = response.formData();
            } else {
                promiseValue = response.text();
            }

            promiseValue
                .then(value => {
                    let dado = {
                        dado: value,
                        status: response.status,
                        header: headers,
                        retorno: response,
                    };
                    if (response.ok || response.status == 301 || response.status == 302) {
                        retornoFim();
                        return returnoSucesso(dado);
                    } else {
                        retornoFim();
                        return retornoFalha(dado);
                    }
                })
                .catch(error => {
                    retornoFim();
                    return retornoErro(error);
                });
        })
        .catch(error => {
            retornoFim();
            return retornoErro(error);
        });
};

ajaxGet = function (link, option) {
    if (option == undefined) {
        option = { metodo: 'GET' };
    } else {
        option.metodo = 'GET';
    }

    if (!('retorno' in option)) {
        option.retorno = 'json';
    }

    ajax(link, option);
};
ajaxPost = function (link, option) {
    if (option == undefined) {
        option = { metodo: 'POST' };
    } else {
        option.metodo = 'POST';
    }

    if (!('retorno' in option)) {
        option.retorno = 'json';
    }

    let contentTypePadrao = 'application/json';
    if (typeof option.dataUrl == 'object') {
        contentTypePadrao = 'application/x-www-form-urlencoded';
    }

    if (!('header' in option)) {
        option.header = {
            'Content-Type': contentTypePadrao,
        };
    }
    let header = option.header;
    if (!('Content-Type' in header)) {
        option.header['Content-Type'] = contentTypePadrao;
    }

    ajax(link, option);
};
ajaxPut = function (link, option) {
    if (option == undefined) {
        option = { metodo: 'PUT' };
    } else {
        option.metodo = 'PUT';
    }

    if (!('retorno' in option)) {
        option.retorno = 'json';
    }

    let contentTypePadrao = 'application/json';
    if (typeof option.dataUrl == 'object') {
        contentTypePadrao = 'application/x-www-form-urlencoded';
    }

    if (!('header' in option)) {
        option.header = {
            'Content-Type': contentTypePadrao,
        };
    }
    let header = option.header;
    if (!('Content-Type' in header)) {
        option.header['Content-Type'] = contentTypePadrao;
    }

    ajax(link, option);
};
ajaxDelete = function (link, option) {
    if (option == undefined) {
        option = { metodo: 'DELETE' };
    } else {
        option.metodo = 'DELETE';
    }

    if (option.retorno == undefined) {
        option.retorno = 'json';
    }

    ajax(link, option);
};

validarJson = function (json, campo) {
    if (typeof json == 'string') {
        try {
            JSON.parse(json);
            return true;
        } catch (e) {
            return false;
        }
    } else if (typeof json == 'object' && (campo == undefined || typeof json[campo] !== 'undefined')) {
        return true;
    }
    return false;
};
jsonParse = json => {
    try {
        JSON.parse(json);
        return JSON.parse(json);
    } catch (e) {
        return json;
    }
};
validarEmail = function (email) {
    let reg = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/;
    return reg.test(email);
};
validarTelefone = function (telefone, formato, retorno) {
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

validarCpf = function (cpf, formato, retorno) {};

validarCnpj = function (cnpj, formato, retorno) {};

validarCep = function (cep) {
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
_validarData = function (data) {
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
_validarHora = function (horaCompleta) {
    horaCompleta = horaCompleta.split(':');

    let hora = horaCompleta[0];
    let minuto = horaCompleta[1];
    let segundo = horaCompleta[2];

    if (hora < 0 || hora > 23 || minuto < 0 || minuto > 59 || segundo < 0 || segundo > 59) {
        return false;
    }
    return true;
};
validarData = function (data) {
    let reg = /^[0-9]{2}\/[0-9]{2}\/[0-9]{4}$/;
    if (!reg.test(data)) {
        return false;
    }
    return _validarData(data);
};
validarDataHora = function (data) {
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
validarDinheiro = function (valor) {
    return /^(\d{1,3}(\.\d{3})*|\d+)(\,\d{2})?$/.test(valor);
};

// IS verificações
isTypeOf = function (valor, tipo) {
    return typeof valor === tipo;
};
isNumeric = function (valor, string) {
    if (typeof valor == 'string' && string) {
        return /^(\+|\-|\+ |\- )?[0-9]+(\.[0-9]+)?$/.test(valor);
    }
    return isTypeOf(valor, 'number');
};
isFloat = function (valor, string) {
    if (!isNumeric(valor, string)) {
        return false;
    }

    return /^(\-|\+)?[0-9]+\.[0-9]+/.test(valor);
};
isInt = function (valor, string) {
    if (string && /^(\-)?[0-9]+$/.test(valor)) {
        return true;
    }
    return Number.isInteger(valor);
};
isString = function (valor) {
    return isTypeOf(valor, 'string');
};
isBool = function (valor) {
    return isTypeOf(valor, 'boolean');
};
isUndefined = function (valor) {
    return isTypeOf(valor, 'undefined');
};
isObject = function (valor) {
    return isTypeOf(valor, 'object');
};
isFunction = function (valor) {
    return isTypeOf(valor, 'function');
};
isArray = function (valor) {
    return Array.isArray(valor);
};

setCookie = function (indice, valor, prazo) {
    let d = new Date();
    d.setTime(d.getTime() + prazo * 24 * 60 * 60 * 1000);
    let expires = 'expires=' + d.toGMTString();
    document.cookie = indice + '=' + valor + '; ' + expires;
};

getCookie = function (indice) {
    let name = indice + '=';
    let ca = document.cookie.split(';');
    let tamanho = ca.length;
    let i, c;
    for (i = 0; i < tamanho; ++i) {
        c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return '';
};

uuid = function () {
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function (c) {
        let r = (Math.random() * 16) | 0,
            v = c == 'x' ? r : (r & 0x3) | 0x8;
        return v.toString(16);
    });
};

random = function (max) {
    return Math.floor(Math.random() * max + 1);
};

slug = function (string) {
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

fetchNotificacaoErro = async (response, mensagem) => {
    let json;
    try {
        json = await response.json();
    } catch (error) {
        json = {};
    }
    Alerta.notificacao(json.mensagem == undefined ? mensagem : json.mensagem, false);
};

fetchMensagemErro = (request, mensagem, titulo) => {
    return request
        .json()
        .then(response => {
            return {
                titulo: response.titulo || titulo || 'Erro!',
                mensagem: response.mensagem || mensagem || 'Ocorreu um erro, por favor, tente novamente.',
            };
        })
        .catch(error => {
            try {
                const response = JSON.parse(error);
                return {
                    titulo: response.titulo || titulo || 'Erro!',
                    mensagem: response.mensagem || mensagem || 'Ocorreu um erro, por favor, tente novamente.',
                };
            } catch (error) {
                return {
                    titulo: titulo || 'Erro!',
                    mensagem: mensagem || 'Ocorreu um erro, por favor, tente novamente.',
                };
            }
        });
};

appendHtml = (elemento, valor) => {
    return elemento.insertAdjacentHTML('beforeend', valor);
};

prependHtml = (elemento, valor) => {
    return elemento.insertAdjacentHTML('afterbegin', valor);
};

removerElemento = elemento => {
    return elemento.parentNode.removeChild(elemento);
};

classAdd = (elemento, classe) => {
    return elemento.classList.add(classe);
};
classRemover = (elemento, classe) => {
    return elemento.classList.remove(classe);
};
classExiste = (elemento, classe) => {
    return elemento.classList.contains(classe);
};
