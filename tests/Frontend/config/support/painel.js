Cypress.Commands.add('loginPainel', (login, senha) => {
    cy.get('#input_cpf').type(login);
    cy.get('#input_passe').type(senha);
    cy.get('#botao_login').click();
});
