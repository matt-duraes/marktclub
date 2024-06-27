const { Teste } = require('../../config/utils/teste');
const { cpf, gerarEmailAleatorio } = require('../../config/utils/geral');

function clicarBotaoAdicionarDependente(tipo) {
    if (tipo === 'mobile') {
        cy.clicar('[data-test="bloco_menu_perfil_mobile"] [data-test="adicionar_dependente_botao"]');
    }
    if (tipo === 'desktop') {
        cy.clicar('[data-test="bloco_menu_perfil_desktop"] [data-test="adicionar_dependente_botao"]');
    }
}

Teste('Criar e excluir dependentes', 'clube', type => {
    const dados = {
        nome: 'Dependente Teste',
        cpf: cpf(),
        email: gerarEmailAleatorio(),
    };

    it(`Acessar perfil e adicionar dependente`, () => {
        cy.visit('/');
        cy.siteBotaoMenuPerfil(type);
        clicarBotaoAdicionarDependente(type);
        cy.get('#input_dependente_nome').clear().type(dados.nome);
        cy.get('#input_dependente_cpf').clear().type(dados.cpf);
        cy.get('#input_dependente_email').clear().type(dados.email);
        cy.clicar('#botao_cadastrar_dependente');
        cy.checkVisivel('.fw_alerta_mensagem_conteudo').should('contain', 'Convite enviado com sucesso');
    });

    it(`Excluir dependentes cadastrados`, () => {
        cy.visit('/perfil/dependente');
        cy.get('#bloco_dependente_lista .linha')
            .contains(dados.nome)
            .parent()
            .within(() => {
                cy.get('[data-test="Botao_deletar_dependente"]').click();
            });
        cy.get('#fw_alerta_mensagem');
        cy.wait(1000);
        cy.get('.fw_alerta_mensagem_botao_confirmar').click();
    });
});
