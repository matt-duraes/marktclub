const { uuid, cpf } = require('../../config/utils/geral');
const { Teste } = require('../../config/utils/teste');

Teste('Parceiro Loja', 'painel', type => {
    it(`Clicar menu`, () => {
        cy.visit('/dashboard');

        cy.painelMenu('#menu_parceiro_loja', type);

        cy.checkUrl('/app/parceiro-loja');
        cy.checkVisivel('#main_template');
    });

    it(`Adicionar novo parceiro`, () => {
        cy.visit('/app/parceiro-loja');

        cy.painelPaginaAdicionar();

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
                cpf: cpf(),
                telefone: '11999999999',
                email: 'testeCypress@teste.com',
            },
        };

        cy.painelSelect('#input_equipe_texto', dados.equipe);
        cy.digitar('#input_titulo_interno', dados.tituloInterno);
        cy.painelSelect('#input_tipo_loja_texto', dados.lojaTexto);
        cy.painelSelect('#input_categoria_principal_texto', dados.categoria);
        cy.digitar('#input_url', dados.url);
        cy.digitar('#input_responsavel_nome', dados.responsavel.nome);
        cy.digitar('#input_responsavel_cargo', dados.responsavel.cargo);
        cy.digitar('#input_responsavel_cpf', dados.responsavel.cpf);
        cy.digitar('#input_responsavel_telefone', dados.responsavel.telefone);
        cy.digitar('#input_responsavel_email', dados.responsavel.email);
        cy.clicar('#checkbox_empresa .marcar_todas .input_checkbox label');

        cy.painelBotaoSalvarAdicionar();
    });
});
