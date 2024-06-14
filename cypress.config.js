const { defineConfig } = require('cypress');
const { LINK } = require('./cypress.local.json');

module.exports = defineConfig({
    downloadsFolder: 'tests/Frontend/config/downloads',
    fixturesFolder: 'tests/Frontend/config/fixtures',
    e2e: {
        baseUrl: LINK,
        specPattern: 'tests/Frontend/**/*.cy.{js,jsx,ts,tsx}',
        supportFile: 'tests/Frontend/config/support/e2e.js',
        experimentalRunAllSpecs: true,
        // testIsolation: false,
        setupNodeEvents(on, config) {
            // implement node event listeners here
        },
    },
});
