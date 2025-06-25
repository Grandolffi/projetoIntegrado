<?php
class UsuarioDao {

   public function autenticar($email, $senhaDigitada) {
    try {
        $conn = ConnectionFactory::getConnection();
        $sql = "SELECT * FROM usuarios WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && password_verify($senhaDigitada, $row['senha'])) {
            $usuario = new Usuario();
            $usuario->setId($row['id']);
            $usuario->setNome($row['nome']);
            $usuario->setEmail($row['email']);
            $usuario->setTipo($row['tipo']);
            return $usuario;
        } else {
            return null;
        }
    } catch (PDOException $e) {
        echo "Erro ao autenticar usuário: " . $e->getMessage();
        return null;
    }
}


    public function buscaPorId($id) {
        try {
            $sql = "SELECT * FROM usuarios WHERE id = :id";
            $conn = ConnectionFactory::getConnection()->prepare($sql);
            $conn->bindValue(":id", $id);
            $conn->execute();
            $row = $conn->fetch(PDO::FETCH_ASSOC);
            if ($row) {
                return $this->criarUsuario($row);
            }
            return null;
        } catch (PDOException $e) {
            echo "<p>Erro ao buscar ID: {$id}</p> <p>{$e->getMessage()}</p>";
        }
    }

    private function criarUsuario($linha) {
        $usuario = new Usuario();
        $usuario->setId($linha['id']);
        $usuario->setNome($linha['nome']);
        $usuario->setEmail($linha['email']);
        $usuario->setTipo($linha['tipo']); // médico, balconista, etc.
        return $usuario;
    }
}
?>
