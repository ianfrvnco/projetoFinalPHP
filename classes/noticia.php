<?php
class Noticia
{
    private $conn;
    private $table_name = "noticias";

    public function __construct($db)
    {
        $this->conn = $db;
    }

    public function registrarSemImagem($titulo, $conteudo, $usuario_id)
    {
        $query = "INSERT INTO " . $this->table_name . " (titulo, conteudo, hora_de_publicacao, usuario_id) 
                  VALUES (?, ?, NOW(), ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$titulo, $conteudo, $usuario_id]);
        return $stmt;
    }

    public function registrarComImagem($titulo, $conteudo, $imagem, $usuario_id)
    {
        $query = "INSERT INTO " . $this->table_name . " (titulo, conteudo, url_imagem, hora_de_publicacao, usuario_id) 
                  VALUES (?, ?, ?, NOW(), ?)";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$titulo, $conteudo, $imagem, $usuario_id]);
        return $stmt;
    }

    public function ler()
    {
        $query = "SELECT n.*, u.nome AS autor 
                  FROM " . $this->table_name . " n
                  JOIN usuarios u ON n.usuario_id = u.id
                  ORDER BY n.id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function lerPorId($id)
    {
        $query = "SELECT n.*, u.nome AS autor 
                  FROM " . $this->table_name . " n
                  JOIN usuarios u ON n.usuario_id = u.id
                  WHERE n.id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deletar($id)
    {
        $query = "DELETE FROM " . $this->table_name . " WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt;
    }

    public function atualizar($id, $titulo, $conteudo)
    {
        $query = "UPDATE " . $this->table_name . " 
              SET titulo = ?, conteudo = ?, hora_de_publicacao = NOW() 
              WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$titulo, $conteudo, $id]);
        return $stmt;
    }

}
