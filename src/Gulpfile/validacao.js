const fs = require('fs');

exports.arquivoExiste = (lista, erro) => {
    if (erro == undefined) {
        erro = true;
    }
    if (typeof lista === 'string' && !fs.existsSync(lista)) {
        if (erro) {
            error('O arquivo ' + lista + ' não existe.');
        }
        return false;
    } else if (typeof lista === 'object') {
        let retorno = true;
        [].forEach.call(lista, arquivo => {
            if (!fs.existsSync(arquivo)) {
                retorno = false;
                if (erro) {
                    error('O arquivo ' + arquivo + ' não existe.');
                }
                return;
            }
        });
        return retorno;
    }
    return true;
};

function error(mensagem) {
    console.log('\x1b[31m\x1b[1m ERROR: ' + mensagem);
    console.log('\033[0m ');
}
