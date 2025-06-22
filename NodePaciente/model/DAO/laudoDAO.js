const pool = require('./db');

class Laudo {
  constructor(id_laudo, solicitacao_id, paciente_id, responsavel_tecnico, observacoes, data_finalizacao) {
    this.id_laudo = id_laudo;
    this.solicitacao_id = solicitacao_id;
    this.paciente_id = paciente_id;
    this.responsavel_tecnico = responsavel_tecnico;
    this.observacoes = observacoes;
    this.data_finalizacao = data_finalizacao;
  }
}

// CREATE
async function insertLaudo(solicitacaoId, pacienteId, responsavelTecnico, dataFinalizacao, observacoes, resultadosExames) {
    const client = await pool.connect();
    try {
        await client.query('BEGIN');

        const laudoResult = await client.query(`
            INSERT INTO laudos (solicitacao_id, paciente_id, responsavel_tecnico, observacoes, data_finalizacao)
            VALUES ($1, $2, $3, $4, $5)
            RETURNING id_laudo`,
            [solicitacaoId, pacienteId, responsavelTecnico, observacoes, dataFinalizacao]
        );
        const novoLaudoId = laudoResult.rows[0].id_laudo;
        const {insertResultadoExame} = require('./exameDAO'); // Importar o DAO de exames

        if (resultadosExames && resultadosExames.length > 0) {
            for (const exameData of resultadosExames) {
                // Chame a função de inserção de exame
                await insertResultadoExame(
                    novoLaudoId, // laudo_id
                    exameData.nomeExame,
                    exameData.tipoExame,
                    exameData.valorAbsoluto,
                    exameData.valorReferencia,
                    exameData.pacienteRegistro, // ou pacienteId, dependendo do que `paciente_registro` guarda
                    exameData.dataHoraExame,
                    pacienteId // paciente_id_fk OBRIGATÓRIO
                );
            }
        }

        await client.query('COMMIT');
        console.log("Resultado do insert Laudo: ", { idLaudo: novoLaudoId });
        return { success: true, idLaudo: novoLaudoId };
    } catch (error) {
        await client.query('ROLLBACK');
        console.error("Erro ao inserir laudo e exames:", error);
        throw error;
    } finally {
        client.release();
    }
}

// READ todos
async function getTodosLaudos() { // Renomeado para evitar conflito com 'Exame'
    const { rows } = await pool.query("SELECT * FROM laudos ORDER BY data_finalizacao DESC, id_laudo DESC");
    return rows;
}

// READ por ID
async function getLaudoById(id) {
    if (id) {
        const { rows } = await pool.query("SELECT * FROM laudos WHERE id_laudo = $1", [id]);
        return rows[0];
    }
    console.error("Falha ao buscar Laudo, não foi passado o id.");
    return false;
}

// UPDATE
async function updateLaudo(id, solicitacaoId, pacienteId, responsavelTecnico, dataFinalizacao, observacoes) {
    if (id && solicitacaoId && pacienteId && responsavelTecnico && dataFinalizacao) {
        const result = await pool.query(`
            UPDATE laudos
            SET solicitacao_id = $1, paciente_id = $2, responsavel_tecnico = $3,
                observacoes = $4, data_finalizacao = $5
            WHERE id_laudo = $6
            RETURNING *`,
            [solicitacaoId, pacienteId, responsavelTecnico, observacoes, dataFinalizacao, id]
        );
        console.log("Resultado do update Laudo: ", result.rows[0]);
        if (result.rows.length === 0) return false;
        return result.rows[0];
    }
    console.error("Falha ao editar Laudo, faltou algum dado.");
    return false;
}

// DELETE (excluir o laudo e os exames associados, se houver FK com CASCADE)
async function deleteLaudo(id) {
    if (id) {
        const result = await pool.query(`
            DELETE FROM laudos
            WHERE id_laudo = $1
            RETURNING id_laudo`,
            [id]
        );
        if (result.rows.length === 0) return false;
        return true;
    }
    console.error("Falha ao remover o Laudo, não foi passado o id.");
    return false;
}

module.exports = {Laudo, insertLaudo, getTodosLaudos, getLaudoById, updateLaudo, deleteLaudo};