const { dispositivos } = require('./dispositivos');

const Teste = (descricao, local, testesCallback) => {
    let funcaoLogin = () => {};

    if (local == 'clube') {
        Cypress.config('baseUrl', Cypress.env('urlClube'));
        funcaoLogin = cy.siteLogin;
    }
    if (local == 'painel') {
        Cypress.config('baseUrl', Cypress.env('urlPainel'));
        funcaoLogin = cy.painelLogin;
    }

    dispositivos.forEach(({ viewport, type }) => {
        describe(`${descricao} - ${viewport}`, () => {
            beforeEach(() => {
                cy.viewport(viewport);

                cy.session(`login-${local}`, () => {
                    cy.painelLogin();
                });
            });

            testesCallback(type);
        });
    });
};

module.exports = { Teste };
