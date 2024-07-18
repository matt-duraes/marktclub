const { marktclub } = require('../fixtures/usuarios/painel.json');

const painelSelect = (id, texto) => {
    cy.clicar(id);
    cy.get('#fw_form_select .option li').contains(texto).click();
};

const painelLogin = () => {
    const login = marktclub.login;
    const senha = marktclub.senha;
    cy.visit('');
    cy.digitar('#input_cpf', login);
    cy.digitar('#input_passe', senha);
    cy.clicar('#botao_login');

    cy.checkUrl('/dashboard');
};

const painelMenu = (menu, type) => {
    if (type === 'mobile') {
        cy.clicar('#menu_principal');
    }
    cy.clicar(menu);
};

const painelPaginaAdicionar = () => {
    cy.clicar('#botao_add_geral');
    cy.checkVisivel('#bloco_app_add');
};

const painelBotaoSalvarAdicionar = novo => {
    cy.clicar('#botao_salvar_geral');

    cy.wait(1500);

    cy.checkTexto(
        '#fw_alerta_mensagem .fw_alerta_mensagem_conteudo',
        'Seus dados foram salvos com sucesso, escolha o que deseja fazer para continuar.'
    );

    if (novo) {
        cy.clicar('.fw_alerta_mensagem_botao_cancelar');
        return;
    }

    cy.clicar('.fw_alerta_mensagem_botao_confirmar');
    cy.checkVisivel('#bloco_app_visualizar');
};

Cypress.Commands.addAll({
    painelSelect,
    painelLogin,
    painelMenu,
    painelPaginaAdicionar,
    painelBotaoSalvarAdicionar,
});
