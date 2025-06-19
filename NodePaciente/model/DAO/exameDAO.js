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
async function getResultadoExame() {
    const {rows} = await pool.query("SELECT * FROM resultados_exames ORDER BY id_exame");
    const exames = rows; 
    return exames;
}

// READ (1) (buscar por ID)
async function getResultadoExameById(id_exame) { // RENOMEADO 'id' para 'id_exame'
    if (id_exame) {
        const { rows } = await pool.query("SELECT * FROM resultados_exames WHERE id_exame = $1", [id_exame]);
        return rows[0];
    }
    console.error("Falha ao buscar Resultado de Exame, não foi passado o id_exame."); // MENSAGEM ATUALIZADA
    return false;
}

// CREATE (adiciona um novo resultado de exame)
// exameDAO.js
async function insertResultadoExame(
    laudo_id,             // Opcional no BD, então pode ser null
    nome_exame,
    tipo_exame,
    valor_absoluto,       // Opcional no BD
    valor_referencia,     // Opcional no BD
    paciente_registro,    // Opcional no BD
    data_hora_exame,
    data_cadastro,        // Opcional no BD (default)
    paciente_id_fk        // Este é obrigatório no BD
) {
    // Validação do lado do Node.js: apenas os campos que são NOT NULL no BD
    if (nome_exame && tipo_exame && paciente_id_fk && data_hora_exame) {
        const result = await pool.query(`
            INSERT INTO resultados_exames(
                laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia,
                paciente_registro, data_hora_exame, data_cadastro, paciente_id_fk
            )
            VALUES($1, $2, $3, $4, $5, $6, $7, $8, $9)
            RETURNING *`,
            [
                laudo_id,             // $1
                nome_exame,           // $2
                tipo_exame,           // $3
                valor_absoluto,       // $4
                valor_referencia,     // $5
                paciente_registro,    // $6
                data_hora_exame,      // $7
                data_cadastro,        // $8 - Pode ser null/undefined e o BD usará o DEFAULT
                paciente_id_fk        // $9
            ]
        );
        console.log("Resultado do insert: ", result.rows[0]);
        if (result.rows.length > 0) {
            return result.rows[0];
        }
        return false;
    }
    // Esta mensagem agora só aparecerá se um dos campos REALMENTE obrigatórios faltar
    console.error("Falha ao inserir resultado de exame, faltou um campo obrigatório (nome_exame, tipo_exame, paciente_id_fk, data_hora_exame).");
    return false;
}

// UPDATE 
async function editResultadoExame(id_exame, laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame) {
    console.log("Dados: ", id_exame, laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame, data_cadastro);
    if (id_exame && laudo_id && nome_exame && tipo_exame && valor_absoluto && valor_referencia && paciente_registro && data_hora_exame) {
        console.log("Dados: ", id_exame, laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame, data_cadastro);
        const result = await pool.query(`
            UPDATE resultados_exames
            SET laudo_id = $1, nome_exame = $2, tipo_exame = $3, valor_absoluto = $4, 
                valor_referencia = $5, paciente_registro = $6, data_hora_exame = $7
            WHERE id_exame = $8
            RETURNING *`,
            [laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame, id_exame]
        );
        console.log("Resultado do editar Resultado de Exame: " + result.rows[0]);

        if (result.rows.length === 0) return false; // Se não achou o id, retorna false
        return result.rows[0]; 
    }
    console.error("Falha ao editar Resultado de Exame, faltou algum dado.");
    return false;
}

// DELETE
async function deleteResultadoExame(id_exame) {
    if (id_exame) {
        const result = await pool.query(`
            DELETE FROM resultados_exames
            WHERE id_exame = $1
            RETURNING id_exame`,
            [id_exame]
        );
        if (result.rows.length === 0) return false; // Se não achou o id, retorna false
        return true;
    }
    console.error("Falha ao remover o Resultado de Exame, não foi passado o id.");
    return false;
}

module.exports = {Exame, getResultadoExame, getResultadoExameById, editResultadoExame, deleteResultadoExame, insertResultadoExame};