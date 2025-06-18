const pool = require('../../db'); // Caminho para o seu arquivo db.js

class Solicitacao {
  constructor(id_solicitacao, paciente_id, data_solicitacao, data_prevista_realizacao, solicitante_nome, status, observacoes) {
    this.id_solicitacao = id_solicitacao;
    this.paciente_id = paciente_id;
    this.data_solicitacao = data_solicitacao;
    this.data_prevista_realizacao = data_prevista_realizacao;
    this.solicitante_nome = solicitante_nome;
    this.status = status;
    this.observacoes = observacoes;
  }
}

class SolicitacaoExameItem { // Mantido para representação de dados
  constructor(id, solicitacao_id, nome_exame, tipo_exame_categoria, valor_referencia_solicitacao, status_item) {
    this.id = id;
    this.solicitacao_id = solicitacao_id;
    this.nome_exame = nome_exame;
    this.tipo_exame_categoria = tipo_exame_categoria;
    this.valor_referencia_solicitacao = valor_referencia_solicitacao;
    this.status_item = status_item;
  }
}

// CREATE (Inserir uma nova Solicitação e seus Itens)
async function insertSolicitacao(pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames) {
    // Validação similar ao insertPaciente
    if (pacienteId && dataSolicitacao && dataPrevistaRealizacao && solicitanteNome && exames && exames.length > 0) {
        const client = await pool.connect(); // Obtém um cliente do pool para transação
        try {
            await client.query('BEGIN'); // Inicia a transação

            // Inserir a Solicitação principal
            const solicitacaoResult = await client.query(`
                INSERT INTO solicitacoes (paciente_id, data_solicitacao, data_prevista_realizacao, solicitante_nome, status, observacoes)
                VALUES ($1, $2, $3, $4, $5, $6)
                RETURNING id_solicitacao`,
                [pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes]
            );
            const novaSolicitacaoId = solicitacaoResult.rows[0].id_solicitacao;

            // Inserir os itens de exame para esta solicitação
            for (const exame of exames) {
                await client.query(`
                    INSERT INTO solicitacao_exames_itens (solicitacao_id, nome_exame, tipo_exame_categoria, valor_referencia_solicitacao, status_item)
                    VALUES ($1, $2, $3, $4, $5)`,
                    [novaSolicitacaoId, exame.nomeExame, exame.tipoExameCategoria, exame.valorReferenciaSolicitacao || null, exame.statusItem || 'Pendente']
                );
            }

            await client.query('COMMIT'); // Confirma a transação
            console.log("Resultado do insert Solicitação: ", { idSolicitacao: novaSolicitacaoId }); // Log similar
            return { success: true, idSolicitacao: novaSolicitacaoId }; // Retorna sucesso e o ID
        } catch (error) {
            await client.query('ROLLBACK'); // Desfaz a transação em caso de erro
            console.error("Erro ao inserir solicitação e itens:", error);
            throw error; // Propagar o erro
        } finally {
            client.release(); // Libera o cliente de volta para o pool
        }
    }
    console.error("Falha ao inserir Solicitação, faltou algum dado.");
    return false;
}

// READ ALL (Listar Solicitações com filtro opcional por status)
async function getSolicitacoes(statusFilter = null) {
    try {
        let query = "SELECT * FROM solicitacoes";
        const params = [];
        if (statusFilter) {
            query += " WHERE status = $1";
            params.push(statusFilter);
        }
        query += " ORDER BY data_solicitacao DESC";
        const { rows } = await pool.query(query, params);
        const solicitacoes = rows; // Atribuição para clareza, similar a getPacientes
        return solicitacoes;
    } catch (error) {
        console.error("Erro ao buscar solicitações:", error);
        throw error;
    }
}

// READ ONE (Buscar uma Solicitação por ID, incluindo seus itens)
async function getSolicitacaoById(id) {
    if (id) {
        try {
            const solicitacaoResult = await pool.query("SELECT * FROM solicitacoes WHERE id_solicitacao = $1", [id]);
            const solicitacao = solicitacaoResult.rows[0];

            if (solicitacao) {
                const itensResult = await pool.query("SELECT * FROM solicitacao_exames_itens WHERE solicitacao_id = $1", [id]);
                solicitacao.exames = itensResult.rows; // Adiciona os itens de exame à solicitação
            }
            return solicitacao;
        } catch (error) {
            console.error(`Erro ao buscar solicitação com ID ${id}:`, error);
            throw error;
        }
    }
    console.error("Falha ao buscar Solicitação, não foi passado o id.");
    return false;
}

// UPDATE STATUS (Atualizar apenas o status de uma solicitação)
async function updateSolicitacaoStatus(id, newStatus) {
    // Validação
    if (id && newStatus) {
        try {
            const result = await pool.query(`
                UPDATE solicitacoes
                SET status = $1
                WHERE id_solicitacao = $2
                RETURNING *`,
                [newStatus, id]
            );
            console.log("Resultado do update status Solicitação: ", result.rows[0]); // Log similar
            if (result.rows.length === 0) return false;
            return result.rows[0]; // Retorna a solicitação atualizada
        } catch (error) {
            console.error(`Erro ao atualizar status da solicitação com ID ${id}:`, error);
            throw error;
        }
    }
    console.error("Falha ao atualizar status da Solicitação, faltou id ou novo status.");
    return false;
}

// DELETE (Excluir uma Solicitação - a exclusão em cascata deve cuidar dos itens)
async function deleteSolicitacao(id) {
    if (id) {
        try {
            const result = await pool.query(`
                DELETE FROM solicitacoes
                WHERE id_solicitacao = $1
                RETURNING id_solicitacao`, // Retornar o ID para confirmar exclusão
                [id]
            );
            if (result.rows.length === 0) return false; // Se não achou o id, retorna false
            return true;
        } catch (error) {
            console.error(`Erro ao deletar solicitação com ID ${id}:`, error);
            throw error;
        }
    }
    console.error("Falha ao remover a solicitação, não foi passado o id.");
    return false;
}

module.exports = {
    Solicitacao,
    SolicitacaoExameItem,
    insertSolicitacao,
    getSolicitacoes,
    getSolicitacaoById,
    updateSolicitacaoStatus,
    deleteSolicitacao
};