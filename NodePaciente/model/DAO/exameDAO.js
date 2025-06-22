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

async function insertResultadoExame(laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame, paciente_id_fk){
    if (nome_exame && tipo_exame && data_hora_exame && paciente_id_fk) {
        const result = await pool.query(`
            INSERT INTO resultados_exames(
                laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia,
                paciente_registro, data_hora_exame, paciente_id_fk
            )
            VALUES($1, $2, $3, $4, $5, $6, $7, $8)
            RETURNING *`,
            [
                laudo_id,
                nome_exame,
                tipo_exame,
                valor_absoluto,
                valor_referencia,
                paciente_registro, // Pode ser null se não for obrigatório
                data_hora_exame,
                paciente_id_fk     // OBRIGATÓRIO (NOT NULL)
            ]
        );
        console.log("Resultado do insert: ", result.rows[0]);
        if (result.rows.length > 0) {
            return result.rows[0]; // Retorna o objeto inserido com id_exame e data_cadastro
        }
        return false;
    }
    console.error("Falha ao inserir resultado de exame, faltou um campo obrigatório (nome_exame, tipo_exame, paciente_id_fk, data_hora_exame).");
    return false;
}

// READ  (listar todos os resultados de exames)
async function getResultadoExame() {
    const {rows} = await pool.query("SELECT * FROM resultados_exames ORDER BY id_exame");
    const exames = rows; 
    console.log(exames);
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

// UPDATE 
// ...
async function editResultadoExame(id_exame, laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame, paciente_id_fk) { // Adicione paciente_id_fk se for atualizado
    // Valide apenas os campos REALMENTE obrigatórios ou o ID.
    // Se a ausência de um campo opcional significa que ele deve ser setado como NULL, o DB lida com isso.
    if (id_exame && nome_exame && tipo_exame && data_hora_exame) { // Apenas campos NOT NULL aqui.
        const result = await pool.query(`
            UPDATE resultados_exames
            SET laudo_id = $1,
                nome_exame = $2,
                tipo_exame = $3,
                valor_absoluto = $4,
                valor_referencia = $5,
                paciente_registro = $6,
                data_hora_exame = $7,
                paciente_id_fk = $8  -- Inclua paciente_id_fk no UPDATE se ele pode mudar.
                                     -- Se não muda na edição, pode removê-lo do SET e da lista de parâmetros.
            WHERE id_exame = $9
            RETURNING *`,
            [laudo_id, nome_exame, tipo_exame, valor_absoluto, valor_referencia, paciente_registro, data_hora_exame, paciente_id_fk, id_exame] // Adicione paciente_id_fk aqui
        );
        console.log("Resultado do editar Resultado de Exame: ", result.rows[0]); // Use vírgula para objetos
        if (result.rows.length === 0) {
            console.warn(`Nenhum exame encontrado com ID ${id_exame} para edição.`);
            return false;
        }
        return result.rows[0];
    }
    console.error("Falha ao editar Resultado de Exame, faltou ID ou campo obrigatório (nome_exame, tipo_exame, data_hora_exame).");
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