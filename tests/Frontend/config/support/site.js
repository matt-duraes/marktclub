function siteLogin(login, senha) {
    cy.get('.botao_fazer_login').get('.entrar').click();
    cy.get('#input_login').type(login);
    cy.get('#input_senha').type(senha);
    cy.get('#botao_fazer_login').click();
}

Cypress.Commands.addAll({
    siteLogin,
});
