const { uuid } = require('../../config/utils/geral');

describe('Parceiro Loja', () => {
    beforeEach(() => {
        cy.session('login', () => {
            cy.painelLogin();
            cy.url().should('include', '/dashboard');
        });
    });

    ['iphone-x', 'macbook-16'].map(device => {
        it(`Clicar menu - ${device}`, () => {
            cy.viewport(device);
            cy.visit('/dashboard');

            cy.painelMenu('#menu_parceiro_loja', device);
            cy.url().should('include', '/app/parceiro-loja');
            cy.get('#main_template').should('be.visible');
        });

        it(`Adicionar novo parceiro - ${device}`, () => {
            cy.viewport(device);
            cy.visit('/app/parceiro-loja');

            cy.painelAdicionar();

            const uuidGerado = uuid();
            const dados = {
                equipe: 'Andre Rodrigues',
                tituloInterno: `Titulo Interno Cypress ${uuidGerado}`,
                lojaTexto: 'Loja',
                categoria: 'Alimentação',
                url: `url-cypress-${uuidGerado}`,
                responsavel: {
                    nome: 'Responsavel Cypress',
                    cargo: 'Cargo Cypress',
                    cpf: '12345678900',
                    telefone: '11999999999',
                    email: 'testeCypress@teste.com',
                },
            };

            cy.painelSelect('#input_equipe_texto', dados.equipe);
            cy.get('#input_titulo_interno').type(dados.tituloInterno);
            cy.painelSelect('#input_tipo_loja_texto', dados.lojaTexto);
            cy.painelSelect('#input_categoria_principal_texto', dados.categoria);
            cy.get('#input_url').type(dados.url);
            cy.get('#input_responsavel_nome').type(dados.responsavel.nome);
            cy.get('#input_responsavel_cargo').type(dados.responsavel.cargo);
            cy.get('#input_responsavel_cpf').type(dados.responsavel.cpf);
            cy.get('#input_responsavel_telefone').type(dados.responsavel.telefone);
            cy.get('#input_responsavel_email').type(dados.responsavel.email);
            cy.get('#checkbox_empresa .marcar_todas .input_checkbox label').click();
            cy.get('#checkbox_empresa .botao_mais').click();

            cy.painelAdicionarSalvar();
        });
    });
});
const salvarNovoRegisto = () => {
    setarFormulario('André Rodrigues');
    cy.painelBotaoSalvar(true);
};
const setarFormulario = titulo => {
    cy.get('#input_titulo_interno').type(titulo);
};
const validarValorSalvo = () => {
    //
};
const editarRegistro = () => {
    //
};
const validarValorAtualizado = () => {
    //
};
