const { marktclub } = require('../config/fixtures/usuarios/painel.json');

describe('template spec', () => {
    beforeEach(() => {
        cy.visit('/painel');
    });

    it('super usuário', () => {
        cy.loginPainel(marktclub.login, marktclub.senha);
    });
});
