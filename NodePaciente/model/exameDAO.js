const pool = require('./db');

class Exame {
  constructor(id_exame, laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame, data_cadastro) {
    this.id_exame = id_exame;
    this.laudo_id = laudo_id;
    this.nome_exame = nome_exame;
    this.tipo_exame = tipo_exame;
    this.valor_absoluto = valor_absoluto;
    this.valor_referencia = valor_referencia;
    this.paciente_registro = paciente_registro;
    this.data_hora_exame = data_hora_exame;
    this.data_cadastro = data_cadastro;
  }
}

// READ  (listar todos os resultados de exames)
async function getTodosResultadosExames() {
    const { rows } = await pool.query("SELECT * FROM resultados_exames ORDER BY data_hora_exame DESC, id_exame DESC");
    const exames = rows; 
    return exames;
}

// READ (1) (buscar por ID)
async function getResultadoExameById(id) {
    if (id) {
        const { rows } = await pool.query("SELECT * FROM resultados_exames WHERE id_exame = $1", [id]);
        return rows[0]; 
    }
    console.error("Falha ao buscar Resultado de Exame, não foi passado o id.");
    return false;
}

// UPDATE 
async function updateResultadoExame(id, laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame) {
    if (id && laudo_id && nome_exame && tipo_exame && valor_absoluto && valor_referencia && paciente_registro && data_hora_exame) {
        const result = await pool.query(`
            UPDATE resultados_exames
            SET laudo_id = $1, nome_exame = $2, tipo_exame = $3, valor_absoluto = $4, 
                valor_referencia = $5, paciente_registro = $6, data_hora_exame = $7
            WHERE id_exame = $8
            RETURNING *`,
            [laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame, id]
        );
        console.log("Resultado do edit Resultado de Exame: " + result.rows[0]);

        if (result.rows.length === 0) return false; // Se não achou o id, retorna false
        return result.rows[0]; 
    }
    console.error("Falha ao editar Resultado de Exame, faltou algum dado.");
    return false;
}

// DELETE
async function deleteResultadoExame(id) {
    if (id) {
        const result = await pool.query(`
            DELETE FROM resultados_exames
            WHERE id_exame = $1
            RETURNING id_exame`,
            [id]
        );
        if (result.rows.length === 0) return false; // Se não achou o id, retorna false
        return true;
    }
    console.error("Falha ao remover o Resultado de Exame, não foi passado o id.");
    return false;
}

// CREATE (adiciona um novo resultado de exame) - Usado ao salvar Laudo
async function insertResultadoExame(laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame) {
    if (laudo_id && nome_exame && tipo_exame && valor_absoluto && valor_referencia && paciente_registro && data_hora_exame) {
        const result = await pool.query(`
            INSERT INTO resultados_exames (laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame)
            VALUES ($1, $2, $3, $4, $5, $6, $7)
            RETURNING *`,
            [laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame]
        );
        console.log("Resultado do insert Resultado de Exame: ", result.rows[0]);
        if (result.rows.length > 0) {
            return result.rows[0];
        }
        return false;
    }
    console.error("Falha ao inserir Resultado de Exame, faltou algum dado.");
    return false;
}


module.exports = {Exame, getTodosResultadosExames, getResultadoExameById, updateResultadoExame, deleteResultadoExame, insertResultadoExame};