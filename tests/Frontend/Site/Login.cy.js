const { titular, dependente } = require('../config/fixtures/usuarios/site.json');

describe('Login de todos os tipos de usuários no clube', () => {
    beforeEach(() => {
        cy.visit('/');
    });

    it('titular', () => {
        cy.loginSite(titular.login, titular.senha);
    });

    it('dependente', () => {
        cy.loginSite(dependente.login, dependente.senha);
    });
});
