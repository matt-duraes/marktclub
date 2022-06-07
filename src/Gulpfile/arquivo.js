const fs = require('fs');

exports.fsVerificarSeArquivoExiste = async function (arquivo) {
    return new Promise(async (resolve, reject) => {
        if (fs.existsSync(arquivo)) {
            resolve(true);
        } else {
            reject(false);
        }
    });
};

exports.fsCriarDiretorio = async function (diretorio) {
    return new Promise(async resolve => {
        if (await !fs.existsSync(diretorio)) {
            await fs.mkdirSync(diretorio);
            resolve(true);
        } else {
            resolve(true);
        }
    });
};
exports.fsDeletarDiretorio = async function (diretorio) {
    return new Promise(async resolve => {
        if (await fs.existsSync(diretorio)) {
            await fs.rmSync(diretorio, { recursive: true });
            resolve(true);
        } else {
            resolve(true);
        }
    });
};
exports.fsRemoverArquivoSeExistir = async function (arquivo) {
    return new Promise(async (resolve, reject) => {
        if (fs.existsSync(arquivo)) {
            fs.unlink(arquivo, function (err) {
                if (err) reject(err);
                resolve(true);
            });
        } else {
            resolve(true);
        }
    });
};

exports.fsPegarConteudo = async function (arquivo) {
    return new Promise(resolve => {
        resolve(fs.readFileSync(arquivo, 'utf-8'));
    });
};

exports.fsCriarArquivo = async function (nome, conteudo) {
    return new Promise((resolve, reject) => {
        fs.appendFile(nome, conteudo, function (err) {
            if (err) reject(err);
            resolve(true);
        });
    });
};
