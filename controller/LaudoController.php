<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Incluindo todas as classes e DAOs necessários
include_once '../dao/ConnectionFactory.php';
include_once '../dao/LaudoDao.php';
include_once '../dao/ExameDao.php';
include_once '../model/ResultadoExames.php';

// Verifique se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Dados recebidos do formulário preencher_laudo.php
    $solicitacao_id = $_POST['solicitacao_id'];
    $resultados_preenchidos = $_POST['resultados']; // Array. Ex: ['Glicose' => '99', 'Ureia' => '40']
    
    // Supondo que você pegue o nome do técnico da sessão de login
    $responsavel_tecnico = "Fernanda (Técnica)"; 

    $dbConnection = null;
    try {
        $dbConnection = ConnectionFactory::getConnection();
        // Inicia a transação para garantir que tudo seja salvo ou nada seja salvo
        $dbConnection->beginTransaction();

        $laudoDao = new LaudoDao();
        $exameDao = new ExameDao();
        
        // 1. Cria o registro principal do LAUDO e pega o ID dele
        $novo_laudo_id = $laudoDao->inserirLaudo($dbConnection, $solicitacao_id, $responsavel_tecnico);

        // 2. Itera sobre cada resultado preenchido para salvá-lo
        foreach ($resultados_preenchidos as $nomeDoExame => $valorDoResultado) {
            if (!empty($valorDoResultado)) {
                $resultadoObj = new ResultadoExames();
                $resultadoObj->setNomeExame($nomeDoExame);
                $resultadoObj->setValorAbsoluto($valorDoResultado);
                // Outros dados como valor de referência, tipo, etc., podem ser buscados do banco ou de um array de configuração, se necessário.
                // $resultadoObj->setTipoExame(...);
                // $resultadoObj->setValorReferencia(...);

                // Chama o método 'inserir' modificado, passando o ID do novo laudo
                $exameDao->inserir($resultadoObj, $novo_laudo_id);
            }
        }
        
        // 3. Atualiza o status da solicitação original para 'Concluído'
        $laudoDao->finalizarSolicitacao($dbConnection, $solicitacao_id);

        // Se todas as operações foram bem-sucedidas, confirma as mudanças
        $dbConnection->commit();

        // Redireciona para a lista de laudos com mensagem de sucesso
        header("Location: ../lista_de_exames.php?status=success");
        exit();

    } catch (Exception $e) {
        // Se qualquer erro ocorreu, desfaz todas as operações no banco
        if ($dbConnection && $dbConnection->inTransaction()) {
            $dbConnection->rollBack();
        }

        // Redireciona de volta para o formulário com uma mensagem de erro
        header("Location: ../preencher_laudo.php?id={$solicitacao_id}&error=" . urlencode($e->getMessage()));
        exit();
    }
}