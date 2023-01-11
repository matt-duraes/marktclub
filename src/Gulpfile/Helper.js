const fs = require('fs');
const { mensagemErro } = require('./mensagem');

exports.arquivoExiste = (lista, erro) => {
    if (erro == undefined) {
        erro = true;
    }
    if (typeof lista === 'string' && !fs.existsSync(lista)) {
        if (erro) {
            mensagemErro('O arquivo ' + lista + ' não existe.');
        }
        return false;
    } else if (typeof lista === 'object') {
        let retorno = true;
        [].forEach.call(lista, arquivo => {
            if (!fs.existsSync(arquivo)) {
                retorno = false;
                if (erro) {
                    mensagemErro('O arquivo ' + arquivo + ' não existe.');
                }
                return;
            }
        });
        return retorno;
    }
    return true;
};

exports.inArray = (valor, lista) => {
    var length = lista.length;
    for (var i = 0; i < length; i++) {
        if (lista[i] == valor) return true;
    }
    return false;
};
