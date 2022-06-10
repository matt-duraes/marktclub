exports.mensagemErro = function (mensagem) {
    console.log('\x1b[31m\x1b[1m ERROR: ' + mensagem + '\033[0m');
};
exports.mensagemSucesso = function (mensagem) {
    console.log('\x1b[32m\x1b[1m SUCCESS: ' + mensagem + '\033[0m');
};
