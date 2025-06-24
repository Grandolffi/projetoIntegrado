const { Pool } = require("pg"); //biblioteca postgree

const pool = new Pool({
    host: 'localhost',
    port: 5432,
    user: 'postgres',
    password: 'postgres',
    database: 'projetointegrado'
});

module.exports = pool;
