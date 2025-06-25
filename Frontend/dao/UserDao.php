<?php

require_once __DIR__ . '/../model/ClasseUsuario.php';



class UserDao {

    public function listarTodos() {
    return $this->read();
    }


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
            echo "Erro ao autenticar: " . $e->getMessage();
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

    // Método para listar todos os usuários (read)
    public function read() {
        try {
            $sql = "SELECT * FROM usuarios ORDER BY nome ASC";
            $conn = ConnectionFactory::getConnection();
            $stmt = $conn->prepare($sql);
            $stmt->execute();

            $usuarios = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $usuarios[] = $this->criarUsuario($row);
            }
            return $usuarios;
        } catch (PDOException $e) {
            echo "Erro ao listar usuários: " . $e->getMessage();
            return [];
        }
    }

    // Método para atualizar um usuário (update)
    public function atualizar(Usuario $usuario) {
    try {
        $sql = "UPDATE usuarios SET nome = :nome, email = :email, tipo = :tipo";

        if (!empty($usuario->getSenha())) {
            $sql .= ", senha = :senha";
        }

        $sql .= " WHERE id = :id";

        $conn = ConnectionFactory::getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':nome', $usuario->getNome());
        $stmt->bindValue(':email', $usuario->getEmail());
        $stmt->bindValue(':tipo', $usuario->getTipo());
        $stmt->bindValue(':id', $usuario->getId(), PDO::PARAM_INT);

        if (!empty($usuario->getSenha())) {
            $stmt->bindValue(':senha', $usuario->getSenha());
        }

        return $stmt->execute();
    } catch (PDOException $e) {
        echo "Erro ao atualizar usuário: " . $e->getMessage();
        return false;
    }
}

    private function criarUsuario($linha) {
        $usuario = new Usuario();
        $usuario->setId($linha['id']);
        $usuario->setNome($linha['nome']);
        $usuario->setEmail($linha['email']);
        $usuario->setTipo($linha['tipo']);
        // Se quiser, pode guardar a senha hash, mas geralmente não expõe.
        // $usuario->setSenha($linha['senha']);
        return $usuario;
    }
}


?>
