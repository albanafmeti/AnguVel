'use strict';
const merge = require('webpack-merge');
const prodEnv = require('./prod.env');

module.exports = merge(prodEnv, {
  NODE_ENV: '"development"',
  domainUrl: 'http://localhost:8080/',
  appUrl: 'http://localhost:8000/',
  apiUrl: 'http://localhost:8000/v1/',
  client_id: '3',
  client_secret: 'wKO3TalpECsJEAI4VUL2rTKjCPIY5Ry8NedSSPmL',
});
