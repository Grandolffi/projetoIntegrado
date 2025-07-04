const pool = require('./db');

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

// representação de dados
class SolicitacaoExameItem { 
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
    if (pacienteId && dataSolicitacao && dataPrevistaRealizacao && solicitanteNome && exames && exames.length > 0) {
        const client = await pool.connect(); // Obtém um cliente do pool para transação
        try {
            await client.query('BEGIN'); // Inicia a transação

            // Inserir a solicitação principal
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
            console.log("Resultado do insert Solicitação: ", { idSolicitacao: novaSolicitacaoId }); // Log similar ao pacienteDAO [cite: pacienteDAO.js]
            return { success: true, idSolicitacao: novaSolicitacaoId }; // Retorna sucesso e o ID
        } catch (error) {
            await client.query('ROLLBACK'); // Desfaz a transação em caso de erro
            console.error("Erro ao inserir solicitação e itens:", error);
            // Aqui, como é uma transação interna, é lançado o erro para o controller lidar
            throw error;
        } finally {
            client.release(); // libera o cliente de volta para o pool
        }
    }
    console.error("Falha ao inserir Solicitação, faltou algum dado.");
    return false;
}

// READ (listar solicitações com filtro opcional por status)
async function getSolicitacoes(statusFilter = null) {
    let query = "SELECT * FROM solicitacoes";
    const params = [];
    if (statusFilter) {
        query += " WHERE status = $1";
        params.push(statusFilter);
    }
    query += " ORDER BY data_solicitacao DESC";
    const { rows } = await pool.query(query, params);
    const solicitacoes = rows;
    return solicitacoes;
}

// READ(1) (buscar uma solicitação por ID, incluindo seus itens)
async function getSolicitacaoById(id) {
    if (id) {
        console.log(`[SolicitacaoDAO - GET by ID - Debug] Buscando solicitação principal para ID: ${id}`); // DEBUG 1
        const solicitacaoResult = await pool.query("SELECT * FROM solicitacoes WHERE id_solicitacao = $1", [id]);
        const solicitacao = solicitacaoResult.rows[0];

        if (solicitacao) {
            console.log(`[SolicitacaoDAO - GET by ID - Debug] Solicitação principal encontrada:`, solicitacao); // DEBUG 2
            console.log(`[SolicitacaoDAO - GET by ID - Debug] Buscando itens de exame para solicitacao_id: ${id}`); // DEBUG 3
            const itensResult = await pool.query("SELECT * FROM solicitacao_exames_itens WHERE solicitacao_id = $1", [id]);
            
            console.log(`[SolicitacaoDAO - GET by ID - Debug] Resultados da busca de itens:`, itensResult.rows); // DEBUG 4
            solicitacao.exames = itensResult.rows; // <--- ESTA LINHA ANEXA OS ITENS

            console.log(`[SolicitacaoDAO - GET by ID - Debug] Solicitação final com itens anexados:`, solicitacao); // DEBUG 5: OBJETO COMPLETO AQUI
        } else {
            console.warn(`[SolicitacaoDAO - GET by ID - Debug] Solicitação ID ${id} não encontrada no DB.`);
        }
        return solicitacao;
    }
    console.error("Falha ao buscar Solicitação, não foi passado o id.");
    return false;
}

// UPDATE STATUS (atualizar apenas o status de uma solicitação)
async function updateSolicitacaoStatus(id, newStatus) {
    if (id && newStatus) {
        const result = await pool.query(`
            UPDATE solicitacoes
            SET status = $1
            WHERE id_solicitacao = $2
            RETURNING *`, // retorna o objeto atualizado
            [newStatus, id]
        );
        console.log("Resultado do update status Solicitação: ", result.rows[0]);
        if (result.rows.length === 0) return false;
        return result.rows[0]; // retorna a solicitação atualizada
    }
    console.error("Falha ao atualizar status da Solicitação, faltou id ou novo status.");
    return false;
}

// DELETE (excluir uma solicitação - a exclusão em cascata deve cuidar dos itens)
async function deleteSolicitacao(id) {
    if (id) {
        const result = await pool.query(`
            DELETE FROM solicitacoes
            WHERE id_solicitacao = $1
            RETURNING id_solicitacao`, // retornar o ID para confirmar exclusão
            [id]
        );
        if (result.rows.length === 0) return false; // se não achou o id, retorna false
        return true;
    }
    console.error("Falha ao remover a solicitação, não foi passado o id.");
    return false;
}

// UPDATE (atualizar toda a solicitação e seus itens de exame)
async function updateSolicitacao(id, pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, exames) {
  const client = await pool.connect();
  try {
    await client.query('BEGIN');

    // Atualizar dados da solicitação principal
    await client.query(`
      UPDATE solicitacoes
      SET paciente_id = $1,
          data_solicitacao = $2,
          data_prevista_realizacao = $3,
          solicitante_nome = $4,
          status = $5,
          observacoes = $6
      WHERE id_solicitacao = $7
    `, [pacienteId, dataSolicitacao, dataPrevistaRealizacao, solicitanteNome, status, observacoes, id]);

    // Remover os itens de exame atuais para esta solicitação (para substituir pelos novos)
    await client.query(`DELETE FROM solicitacao_exames_itens WHERE solicitacao_id = $1`, [id]);

    // Inserir novos itens de exame
    for (const exame of exames) {
      await client.query(`
        INSERT INTO solicitacao_exames_itens (solicitacao_id, nome_exame, tipo_exame_categoria, valor_referencia_solicitacao, status_item)
        VALUES ($1, $2, $3, $4, $5)
      `, [
        id,
        exame.nomeExame,
        exame.tipoExameCategoria,
        exame.valorReferenciaSolicitacao || null,
        exame.statusItem || 'Pendente'
      ]);
    }

    await client.query('COMMIT');
    return true;
  } catch (error) {
    await client.query('ROLLBACK');
    console.error("Erro ao atualizar solicitação:", error);
    return false;
  } finally {
    client.release();
  }
}


module.exports = {Solicitacao, SolicitacaoExameItem, insertSolicitacao, getSolicitacoes, getSolicitacaoById, updateSolicitacaoStatus, updateSolicitacao, deleteSolicitacao};