const { marktclub } = require('../fixtures/usuarios/painel.json');

Cypress.Commands.add('painelLogin', (login, senha) => {
    login = login === undefined ? marktclub.login : login;
    senha = senha === undefined ? marktclub.senha : senha;
    cy.visit('/painel');
    cy.get('#input_cpf').type(login);
    cy.get('#input_passe').type(senha);
    cy.get('#botao_login').click();
});
Cypress.Commands.add('painelMenu', (menu, device) => {
    if (device === 'iphone-x') {
        cy.get('#menu_principal').click();
    }
    cy.get(menu).click();
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

Cypress.Commands.add('painelAdicionar', () => {
    cy.get('#botao_add_geral').click();
    cy.get('#bloco_app_add').should('be.visible');
});
Cypress.Commands.add('painelAdicionarSalvar', novo => {
    cy.get('#botao_salvar_geral').click();

    cy.wait(1000);

    cy.get('#fw_alerta_mensagem .fw_alerta_mensagem_conteudo').should(
        'contain.text',
        'Seus dados foram salvos com sucesso, escolha o que deseja fazer para continuar.'
    );

    if (novo) {
        cy.get('.fw_alerta_mensagem_botao_cancelar').click();
        return;
    }

    cy.get('.fw_alerta_mensagem_botao_confirmar').click();
    cy.get('#bloco_app_visualizar').should('be.visible');
});

Cypress.Commands.add('painelAbrirRegistro', indice => {
    // Se indice for undefined pega o primeiro
});
Cypress.Commands.add('painelBotaoSalvar', outro => {
    // Se vai clicar no botão de outro ou ir pro registro
});
Cypress.Commands.add('painelBotaoDownload', () => {
    ct;
});
Cypress.Commands.add('painelBotaoDeletar', () => {
    // Clique no botão e o click no confirmar
});
Cypress.Commands.add('painelSelect', (id, texto) => {
    cy.get(id).click();
    cy.get('#fw_form_select .option li').contains(texto).click();
});
