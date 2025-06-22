<?php

require_once 'ConnectionFactory.php';
require_once 'Amostras.php';
require_once 'ColetaDeAmostrasDao.php';

class ColetaDeAmostrasController {
    private $dao;
    
    public function __construct() {
        $this->dao = new ColetaDeAmostrasDao();
    }

    public function processarColeta() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $amostras = new Amostras();
                
                $this->preencherAmostras($amostras);
                
                $resultado = $this->dao->inserir($amostras);
                
                if ($resultado) {
                    header("Location: coletaDeAmostras.php?status=success&message=Coleta+registrada+com+sucesso");
                    exit();
                } else {
                    throw new Exception("Falha ao inserir no banco de dados");
                }
            } catch (Exception $e) {
                header("Location: coletaDeAmostras.php?status=error&message=" . urlencode($e->getMessage()));
                exit();
            }
        }
    }

    private function preencherAmostras(Amostras $amostras) {
        $amostras->setHematologia(implode(', ', $_POST['hematologia'] ?? []));
        $amostras->setParasitologia(implode(', ', $_POST['parasitologia'] ?? []));
        $amostras->setMicrobiologia(implode(', ', $_POST['microbiologia'] ?? []));
        $amostras->setUrinalise(implode(', ', $_POST['urinalise'] ?? []));
        
        $amostras->setTipoDeColetaRealizada($_POST['tipo_coleta'] ?? 'Não informado');
        
        $amostras->setTubosUtilizados($_POST['lote_tubo'] ?? '');
        $amostras->setCoresTubos($_POST['cores_tubos'] ?? []);
        
        $seringa = [
            'tamanho' => $_POST['tamanho_seringa'] ?? '',
            'lote' => $_POST['lote_seringa'] ?? '',
            'validade' => $_POST['validade_seringa'] ?? ''
        ];
        $amostras->setSeringa(json_encode($seringa));
        
        $potesUrina = [
            'lote' => $_POST['lote_urina'] ?? '',
            'validade' => $_POST['validade_urina'] ?? ''
        ];
        $amostras->setPotesUrina(json_encode($potesUrina));
        
        $potesFezes = [
            'lote' => $_POST['lote_fezes'] ?? '',
            'validade' => $_POST['validade_fezes'] ?? ''
        ];
        $amostras->setPotesFezes(json_encode($potesFezes));
    }

    public function listarAmostras() {
        return $this->dao->buscarTodos();
    }
    
    public function obterAmostraPorId($id) {
        return $this->dao->buscarPorId($id);
    }
    
    public function atualizarAmostra($id, $dados) {
        $amostras = new Amostras();
        $this->preencherAmostras($amostras);
        return $this->dao->atualizar($amostras, $id);
    }
    
    public function excluirAmostra($id) {
        return $this->dao->deletar($id);
    }
}

if (isset($_GET['action'])) {
    $controller = new ColetaDeAmostrasController();
    
    switch ($_GET['action']) {
        case 'processar':
            $controller->processarColeta();
            break;
        case 'listar':
            $amostras = $controller->listarAmostras();
            break;
    }
}
?>