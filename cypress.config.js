const { defineConfig } = require('cypress');
require('dotenv').config({ path: `.env.local` });

module.exports = defineConfig({
    downloadsFolder: 'tests/Frontend/config/downloads',
    fixturesFolder: 'tests/Frontend/config/fixtures',
    screenshotsFolder: 'tests/Frontend/config/screenshots',
    e2e: {
        specPattern: 'tests/Frontend/**/*.cy.{js,jsx,ts,tsx}',
        supportFile: 'tests/Frontend/config/support/e2e.js',
        experimentalRunAllSpecs: true,
        setupNodeEvents(on, config) {
            // implement node event listeners here
        },
    },
    env: {
        urlClube: process.env.CYPRESS_BASE_URL_CLUBE,
        urlPainel: process.env.CYPRESS_BASE_URL_PAINEL,
    },
});
