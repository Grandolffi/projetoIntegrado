const pool = require('./db');

class Laudo {
  constructor(id_laudo, paciente_id, responsavel_tecnico, observacoes, data_finalizacao) {
    this.id_laudo = id_laudo;
    this.paciente_id = paciente_id;
    this.responsavel_tecnico = responsavel_tecnico;
    this.observacoes = observacoes;
    this.data_finalizacao = data_finalizacao;
  }
}

// CREATE (Inserir um novo Laudo)
async function insertLaudo(paciente_id, responsavel_tecnico, observacoes, data_finalizacao) {
    // Validação similar ao insertPaciente
    if (paciente_id && responsavel_tecnico && data_finalizacao) {
        try {
            const result = await pool.query(`
                INSERT INTO laudos (paciente_id, responsavel_tecnico, observacoes, data_finalizacao)
                VALUES ($1, $2, $3, $4)
                RETURNING *`,
                [paciente_id, responsavel_tecnico, observacoes, data_finalizacao]
            );
            console.log("Resultado do insert Laudo: ", result.rows[0]); // Log similar
            if (result.rows.length > 0) {
                return result.rows[0]; // Retorna o objeto do laudo inserido
            }
            return false;
        } catch (error) {
            console.error("Erro ao inserir laudo:", error);
            throw error; // Propagar o erro para ser capturado no controller
        }
    }
    console.error("Falha ao inserir Laudo, faltou algum dado.");
    return false;
}

// READ ALL (Listar todos os Laudos)
async function getTodosLaudos() {
    try {
        const { rows } = await pool.query("SELECT * FROM laudos ORDER BY data_finalizacao DESC");
        const laudos = rows; // Atribuição para clareza, similar a getPacientes
        return laudos;
    } catch (error) {
        console.error("Erro ao buscar todos os laudos:", error);
        throw error;
    }
}

// READ ONE (Buscar um Laudo por ID)
async function getLaudoById(id) {
    if (id) {
        try {
            const { rows } = await pool.query("SELECT * FROM laudos WHERE id_laudo = $1", [id]);
            return rows[0]; // Retorna o laudo encontrado, ou undefined se não houver
        } catch (error) {
            console.error(`Erro ao buscar laudo com ID ${id}:`, error);
            throw error;
        }
    }
    console.error("Falha ao buscar Laudo, não foi passado o id.");
    return false;
}

// UPDATE (Atualizar um Laudo existente)
async function updateLaudo(id, nome, cpf, dtnasc, email, nomeMae, numCelular, genero) { // Parâmetros foram do Paciente, ajustar para Laudo
    // Validação similar ao editPaciente
    if (id && paciente_id && responsavel_tecnico && data_finalizacao) {
        try {
            const result = await pool.query(`
                UPDATE laudos
                SET paciente_id = $1, responsavel_tecnico = $2, observacoes = $3, data_finalizacao = $4
                WHERE id_laudo = $5
                RETURNING *`, // RETURNING * para retornar o objeto atualizado
                [paciente_id, responsavel_tecnico, observacoes, data_finalizacao, id]
            );
            console.log("Resultado do edit Laudo: ", result.rows[0]); // Log similar

            if (result.rows.length === 0) return false; // Se não encontrou o ID, retorna false
            return result.rows[0]; // Retorna o laudo atualizado
        } catch (error) {
            console.error(`Erro ao atualizar laudo com ID ${id}:`, error);
            throw error;
        }
    }
    console.error("Falha ao editar Laudo, faltou algum dado.");
    return false;
}

// DELETE (Excluir um Laudo)
async function deleteLaudo(id) {
    if (id) {
        try {
            const result = await pool.query(`
                DELETE FROM laudos
                WHERE id_laudo = $1
                RETURNING id_laudo`, // Retornar o ID para confirmar exclusão
                [id]
            );
            if (result.rows.length === 0) return false; // Se não achou o id, retorna false
            return true;
        } catch (error) {
            console.error(`Erro ao deletar laudo com ID ${id}:`, error);
            throw error;
        }
    }
    console.error("Falha ao remover o laudo, não foi passado o id.");
    return false;
}

module.exports = {
    Laudo, // Exporta a classe também
    insertLaudo,
    getTodosLaudos,
    getLaudoById,
    updateLaudo,
    deleteLaudo
};