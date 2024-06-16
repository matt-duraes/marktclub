const { marktclub } = require('../fixtures/usuarios/painel.json');

Cypress.Commands.add('painelLogin', (login, senha) => {
    login = login === undefined ? marktclub.login : login;
    senha = senha === undefined ? marktclub.senha : senha;
    cy.visit('');
    cy.get('#input_cpf').type(login);
    cy.get('#input_passe').type(senha);
    cy.get('#botao_login').click();
});
Cypress.Commands.add('painelMenu', (menu, device) => {
    if (device === 'iphone-xr') {
        cy.get('#menu_principal').click();
    }
    cy.get('#bloco_menu_principal .botao p').contains(menu).click();
});
Cypress.Commands.add('painelPrimeiroTextoLinha', (texto, indice) => {
    // Verifica se o primeiro texto da linha enviada no indice é == ao informado
});
Cypress.Commands.add('painelAbrirSalvar', menu => {
    cy.get('#bloco_menu_principal .botao p').contains(menu).click();
});
Cypress.Commands.add('painelAbrirEditar', () => {
    //
});
Cypress.Commands.add('painelAbrirDownload', () => {
    //
});
Cypress.Commands.add('painelAbrirRegistro', indice => {
    // Se indice for undefined pega o primeiro
});
Cypress.Commands.add('painelBotaoSalvar', outro => {
    // Se vai clicar no botão de outro ou ir pro registro
});
Cypress.Commands.add('painelBotaoDownload', () => {
    //
});
Cypress.Commands.add('painelBotaoDeletar', () => {
    // Clique no botão e o click no confirmar
});
