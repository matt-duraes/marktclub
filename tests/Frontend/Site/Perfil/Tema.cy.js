const { Teste } = require('../../config/utils/teste');

function clicarBotaoTema(tipo) {
    if (tipo === 'mobile') {
        cy.clicar('[data-test="botao_menu_perfil_tema"]');
    }
    if (tipo === 'desktop') {
        cy.clicar('#botao_tema.botao');
    }
}

Teste('Acessar perfil', 'clube', type => {
    it(`Acessar menu e alterar temas`, () => {
        cy.visit('/');
        cy.siteBotaoMenuPerfil(type);
        clicarBotaoTema(type);
        cy.clicar('[data-test="botao_tema_escura"]');
        cy.clicar('[data-test="botao_tema_sistema"]');
        cy.clicar('[data-test="botao_tema_automatico"]');
        cy.clicar('[data-test="botao_tema_clara"]');
    });
});
