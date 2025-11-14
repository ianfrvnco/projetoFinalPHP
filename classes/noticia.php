<?php
class Noticia
{
    private $conn;
    private $table_name = "noticias";


    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function registrar($titulo, $conteudo)
    {
        $publicacao = 'now()';
        $query = "INSERT INTO " . $this->table_name . "(titulo, conteudo, hora_de_publicacao) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$titulo, $conteudo, $publicacao]);
        return $stmt;
    }

    public function ler()
    {
        $query = "SELECT * FROM " . $this->table_name . " ORDER BY id DESC;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ); // retorna objetos com propriedades
    }

    public function lerPorId($id)
    {
        $query = "SELECT * FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function atualizar($id, $nome, $sexo, $fone, $email)
    {
        $query = "UPDATE " . $this->table_name . " SET nome = ?, sexo = ?, fone = ?, email = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$nome, $sexo, $fone, $email, $id]);
        return $stmt;
    }


    public function deletar($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt;
    }
}
