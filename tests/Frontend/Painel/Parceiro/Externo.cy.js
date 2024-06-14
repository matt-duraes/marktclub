describe('Parceiro Externo', () => {
    before(() => {
        cy.painelLogin();
    });
    it('Cadastrar Parceiro Externo', () => {
        for (const device of ['macbook-16', 'iphone-xr']) {
            cy.viewport(device);
            cy.painelMenu('Indicação', device);
            cy.painelAbrirSalvar();
            salvarNovoRegisto();
            cy.painelAbrirEditar();
            validarValorSalvo();
            editarRegistro();
            validarValorAtualizado();
        }
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
