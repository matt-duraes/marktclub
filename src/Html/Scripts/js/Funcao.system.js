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
// Get Elementos
const echo = dado => {
    console.log(dado);
};
const el = function (nome, pai) {
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
const pegarId = function (id) {
    return document.getElementById(id.replace(/^\#/, ''));
};
const pegarClasse = function (classe, pai) {
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
const pegarTag = function (tag, pai) {
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
const pegarTodos = (elemento, pai) => {
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
const attr = (elemento, attr, valor) => {
    if (valor == undefined) {
        return elemento.getAttribute(attr);
    }
    return elemento.setAttribute(attr, valor);
};

const contar = (elemento, pai) => {
    return getAll(elemento, pai).length;
};

const _arrayCompare = function (a1, a2) {
    if (a1.length != a2.length) return false;
    let tamanho = a2.length;
    let i;
    for (i = 0; i < tamanho; i++) {
        if (a1[i] !== a2[i]) return false;
    }
    return true;
};
const inArray = function (valor, array) {
    let tamanho = array.length;
    let i;
    for (i = 0; i < tamanho; i++) {
        if (typeof array[i] == 'object') {
            if (_arrayCompare(array[i], valor)) return true;
        } else {
            if (array[i] == valor) return true;
        }
    }
    return false;
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

const aleatorioNumero = function (max) {
    return Math.floor(Math.random() * max + 1);
};

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

        if ((status == 200 || status == 201) && json.status == 'sucesso') {
            return resolve(json);
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

const adicionarHtml = (elemento, valor, local = 'comeco') => {
    if (local == 'comeco') {
        return elemento.insertAdjacentHTML('afterbegin', valor);
    }
    return elemento.insertAdjacentHTML('beforeend', valor);
};

const removerElemento = elemento => {
    return elemento.parentNode.removeChild(elemento);
};

/**
 * Adiciona uma classe ao elemento
 *
 * @param   {Element}           elemento    Elemento que deseja adicionar a classe
 * @param   {string}            classe      Classe que deseja adicionar
 * @returns {(Element|null)}                Retorna um objeto de elemento ou null caso o elemento não exista
 */
const addClasse = (elemento, classe) => {
    return elemento.classList.add(classe);
};
const alterarClasse = (elemento, classe) => {
    return elemento.classList.toggle(classe);
};
const removerClasse = (elemento, classe) => {
    return elemento.classList.remove(classe);
};
const classeExiste = (elemento, classe) => {
    return elemento.classList.contains(classe);
};

const pp = erro => {
    console.log(erro);
};
const ppe = erro => {
    console.log(erro);
};
