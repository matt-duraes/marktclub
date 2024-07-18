const { Teste } = require('../../config/utils/teste');

function clicarCarterinha(tipo) {
    if (tipo === 'mobile') {
        cy.clicar('#bloco_perfil_mobile > #bloco_menu_perfil > [data-test="test_botao_carteirinha"]');
    }
    if (tipo === 'desktop') {
        cy.clicar('.bloco_geral_sub_menu > #bloco_menu_perfil > [data-test="test_botao_carteirinha"]');
    }
}

Teste('Acessar perfil', 'clube', type => {
    it('Acessar menu e visualizar carteirinha', () => {
        cy.visit('/', {
            onBeforeLoad(win) {
                cy.stub(win, 'open').as('winOpen');
            },
        });
        cy.siteBotaoMenuPerfil(type);
        clicarCarterinha(type);
        //cy.get('@winOpen').should('have.been.calledOnceWith', 'https://localhost.com:4000/perfil/carteirinha');
    });
});
