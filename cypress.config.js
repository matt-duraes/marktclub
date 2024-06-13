const { defineConfig } = require('cypress');

module.exports = defineConfig({
    e2e: {
        specPattern: 'tests/Frontend/**/*',
        setupNodeEvents(on, config) {
            // implement node event listeners here
        },
    },
});
