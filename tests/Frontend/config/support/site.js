const { titular, dependente } = require('../../config/fixtures/usuarios/site.json');

function siteLogin(login, senha) {
    login = login ? login : titular.login;
    senha = senha ? senha : titular.senha;
    cy.visit('');

    cy.get('.botao_fazer_login').get('.entrar').click();
    cy.get('#input_login').type(login);
    cy.get('#input_senha').type(senha);
    cy.get('#botao_fazer_login').click();
    cy.checkVisivel('#bloco_index');
}

function siteBotaoMenuPerfil(tipo) {
    if (tipo === 'mobile') {
        cy.clicar('[data-test="botao_menu_mobile"]');
    }
    if (tipo === 'desktop') {
        cy.clicar('#botao_menu_perfil');
    }
}

Cypress.Commands.addAll({
    siteLogin,
    siteBotaoMenuPerfil,
});
