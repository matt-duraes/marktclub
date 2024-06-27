const { dispositivos } = require('./dispositivos');

const Teste = (descricao, local, testesCallback) => {
    if (local == 'clube') {
        Cypress.config('baseUrl', Cypress.env('urlClube'));
    }
    if (local == 'painel') {
        Cypress.config('baseUrl', Cypress.env('urlPainel'));
    }

    dispositivos.forEach(({ viewport, type }) => {
        describe(`${descricao} - ${viewport}`, () => {
            beforeEach(() => {
                cy.viewport(viewport);

                cy.session(`login-${local}`, () => {
                    if (local === 'painel') {
                        cy.painelLogin();
                    }
                    if (local === 'clube') {
                        cy.siteLogin();
                    }
                });
            });

            testesCallback(type);
        });
    });
};

module.exports = { Teste };
