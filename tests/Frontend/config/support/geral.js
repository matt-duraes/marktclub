const checkUrl = url => {
    cy.url().should('include', url);
};

const checkVisivel = id => {
    cy.get(id).should('be.visible');
};

const checkTexto = (id, texto) => {
    cy.get(id).should('contain.text', texto);
};

const clicar = id => {
    cy.get(id).click();
};

const digitar = (id, texto) => {
    cy.get(id).type(texto);
};

Cypress.Commands.addAll({
    checkUrl,
    checkVisivel,
    checkTexto,
    clicar,
    digitar,
});
