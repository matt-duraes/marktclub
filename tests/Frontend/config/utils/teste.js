const { dispositivos } = require('./dispositivos');

const Teste = (descricao, testesCallback) => {
    dispositivos.forEach(({ viewport, type }) => {
        describe(`${descricao} - ${viewport}`, () => {
            testesCallback(viewport, type);
        });
    });
};

module.exports = { Teste };
