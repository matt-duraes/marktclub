const { defineConfig } = require('cypress');

module.exports = defineConfig({
    downloadsFolder: 'tests/Frontend/config/downloads',
    fixturesFolder: 'tests/Frontend/config/fixtures',
    e2e: {
        baseUrl: 'https://localhost.com:4000',
        specPattern: 'tests/Frontend/**/*.cy.{js,jsx,ts,tsx}',
        supportFile: 'tests/Frontend/config/support/e2e.js',
        experimentalRunAllSpecs: true,
        setupNodeEvents(on, config) {
            // implement node event listeners here
        },
    },
});
