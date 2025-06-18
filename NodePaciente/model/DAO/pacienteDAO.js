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

//read
async function getPacientes(){
    const {rows} = await pool.query("SELECT * FROM pacientes ORDER BY id"); //função assincrona, esperando o select no bd
    const pacientes = rows;
   
    return pacientes;
}

//create
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


//update
async function editPaciente(id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero){
    console.log("Dados: ", id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero);
    if(id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero){
        console.log("Dados: ", id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero);
        const result = await pool.query(`
            UPDATE pacientes
            SET nome = $1, cpf = $2, dtnasc = $3, email = $4, nomeMae = $5, numCelular = $6, genero = $7
            WHERE id = $8
            RETURNING id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero`,
            [nome, cpf, dtnasc, email, nomeMae, numCelular, genero, id]
        );

        console.log("Resultado do edit : " + result.rows[0]);
        
        if(result.rows.length === 0) return false; // ele entra no if quando não acha o id no campo e n retorna linha nenhuma
        return true;
    }
    console.error("Falha ao editar Paciente, faltou algum dado");
    return false;
}

//delete Paciente
async function deletePaciente(id){
    if(id){
       const result = await pool.query(`
            DELETE FROM pacientes
            WHERE id = $1
            RETURNING id`,
            [id]
        );

        if(result.rows.length === 0) return false;
        return true;
    }

    console.error("Falha ao remover o paciente, não foi passado o id");
    return false;
}

module.exports = {getPacientes, Paciente, insertPaciente, editPaciente, deletePaciente};
