const pool = require('./db');

class Paciente {
  constructor(id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero) {
    this.id = id;
    this.nome = nome;
    this.cpf = cpf;
    this.dtnasc = dtnasc;
    this.email = email;
    this.nomeMae = nomeMae;
    this.numCelular = numCelular;
    this.genero = genero;
   }
}



async function insertPaciente(nome, cpf, dtnasc, email, nomeMae, numCelular, genero){
    if(nome, cpf, dtnasc, email, nomeMae, numCelular, genero){
        const result = await pool.query(`
            INSERT INTO pacientes(nome, cpf, dtnasc, email, nomeMae, numCelular, genero)
            VALUES($1, $2, $3, $4, $5, $6, $7)
            RETURNING id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero`,
            [nome, cpf, dtnasc, email, nomeMae, numCelular, genero]
        );
        console.log("Resultado do insert: ", result.rows[0]);
        if(result.rows.length > 0){
            return true;
        }
        return false;
    }
    
    console.error("Falha ao inserir Paciente, faltou algum dado");
    return false;
}

async function getPacientes(){
    const {rows} = await pool.query("SELECT * FROM pacientes ORDER BY id"); //função assincrona, esperando o select no bd
    const pacientes = rows;
   
    return pacientes;
}

module.exports = {getPacientes, Paciente, insertPaciente};
